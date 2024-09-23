<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\User;
use App\Models\Product;
use App\Models\Unit;
use App\Models\ProductUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SaleDetail;
use App\Services\PdfService;
use PDF; 



class SaleController extends Controller
{
    public function index(Request $request)
{
    
    $sales = Sale::with('user', 'customer', 'details.product')->paginate(5);

    return view('livewire.sales.index', compact('sales'));
}


public function show($id)
{
    // Mostrar detalles de la venta
    $sale = Sale::with(['user', 'customer', 'details.product', 'details.unit'])->findOrFail($id);
    
    return view('livewire.sales.show', compact('sale'));
}

public function generatePDF($id)
{
    $sale = Sale::with(['user', 'customer', 'details.product', 'details.unit'])->findOrFail($id);
    $pdf = PDF::loadView('pdf.sale', compact('sale'));
    return $pdf->download('venta_'.$sale->id.'.pdf');
}
public function changeStatus(Request $request, $id)
{
    $sale = Sale::findOrFail($id);
    $sale->status = $request->input('status');
    $sale->save();

    return redirect()->route('sales.show', $id)->with('success', 'Estado de la venta actualizado con éxito.');
}


public function create()
{
    // Obtener todos los productos
    $products = Product::with('productUnits.unit')->get();
    
    // Obtener todos los clientes con rol 3
    $customers = User::where('role', 3)->get();

    return view('livewire/sales.create', compact('products', 'customers'));
}


public function getUnits($id)
{
    $productUnits = ProductUnit::with('unit')->where('product_id', $id)->get();

    return response()->json($productUnits);
}

public function store(Request $request)
{
    $validatedData = $request->validate([
        'customer_id' => 'required|exists:users,id',
        'products' => 'required|array',
    ]);

    // Iniciar una transacción
    DB::beginTransaction();
    try {
        // Crear la venta
        $sale = new Sale();
        $sale->user_id = auth()->id();
        $sale->customer_id = $validatedData['customer_id'];
        $sale->total_amount = 0; // Asigna el total más tarde
        $sale->status = 'pending'; // Cambiar el estado a 'completed' al finalizar
        $sale->save();

        // Procesar cada producto
        $totalAmount = 0;

        foreach ($validatedData['products'] as $productData) {
            $productData = json_decode($productData, true); // Decodificar el JSON

            foreach ($productData as $item) {
                // Crear el detalle de la venta
                $saleDetail = new SaleDetail();
                $saleDetail->sale_id = $sale->id;
                $saleDetail->product_id = $item['id']; // ID del producto
                $saleDetail->unit_id = $item['unitId']; // Asegúrate de que unitId esté presente
                $saleDetail->quantity = $item['quantity'];
                $saleDetail->price = $item['price'];
                $saleDetail->total = $item['price'] * $item['quantity'];
                $saleDetail->save();

                // Actualizar el monto total
                $totalAmount += $saleDetail->total;

                // Actualizar el stock del producto
                $productUnit = ProductUnit::where('product_id', $item['id'])
                    ->where('unit_id', $item['unitId'])
                    ->first();

                if ($productUnit) {
                    $productUnit->stock -= $item['quantity'];
                    $productUnit->save();
                }
            }
        }

        // Actualizar el monto total de la venta
        $sale->total_amount = $totalAmount;
        $sale->save();

        // Confirmar la transacción
        DB::commit();

        return redirect()->route('sales.index')->with('success', 'Venta creada con éxito.');
    } catch (\Exception $e) {
        // Deshacer la transacción si algo falla
        DB::rollBack();
        return redirect()->route('sales.index')->with('error', 'Error al crear la venta: ' . $e->getMessage());
    }
}
public function edit($id)
{
    $sale = Sale::with(['user', 'customer', 'details.product', 'details.unit'])->findOrFail($id);
    $customers = User::where('role', 3)->get();
    $products = Product::with('productUnits.unit')->get();

    // Crear un arreglo con los detalles de la venta
    $cartDetails = $sale->details->map(function($detail) {
        return [
            'id' => $detail->product->id,
            'unitId' => $detail->unit->id ?? null,
            'productName' => $detail->product->name,
            'description' => $detail->unit->description ?? 'Sin descripción',
            'price' => (float)$detail->price,
            'quantity' => (int)$detail->quantity
        ];
    })->toArray();

    // Datos de la venta
    $saleData = [
        'saleId' => $sale->id,
        'userId' => $sale->user_id,
        'customerId' => $sale->customer_id,
        'totalAmount' => (float)$sale->total_amount,
        'status' => $sale->status
    ];


    return view('livewire/sales.edit', compact('sale', 'customers', 'products', 'cartDetails', 'saleData'));
}
public function update(Request $request, $id)
{
    DB::beginTransaction();

    try {
        $sale = Sale::with('details')->findOrFail($id);

        $oldTotalAmount = $sale->total_amount;
        $sale->customer_id = $request->input('customer_id');
        $sale->user_id = $request->input('user_id');

        // Decodifica el carrito recibido
        $cart = json_decode($request->input('cart'), true);

        // Crear un arreglo para almacenar los detalles ya procesados
        $processedDetails = [];

        // Recorrer el carrito y manejar cada producto
        foreach ($cart as $item) {
            // Buscar si ya existe un detalle con el mismo product_id y unit_id
            $existingDetail = $sale->details->first(function($detail) use ($item) {
                return $detail->product_id == $item['id'] && $detail->unit_id == $item['unitId'];
            });

            if ($existingDetail) {
                // Si el producto y unidad ya existen, verificar la cantidad
                $oldQuantity = $existingDetail->quantity;

                // Actualizar la cantidad y el total
                $existingDetail->quantity = $item['quantity'];
                $existingDetail->total = $existingDetail->quantity * $existingDetail->price;
                $existingDetail->save();

                // Ajustar el stock
                $productUnit = ProductUnit::where('product_id', $item['id'])
                                          ->where('unit_id', $item['unitId'])
                                          ->first();
                if ($productUnit) {
                    if ($item['quantity'] > $oldQuantity) {
                        // Incrementar stock
                        $difference = $item['quantity'] - $oldQuantity;
                        $productUnit->stock -= $difference;
                    } elseif ($item['quantity'] < $oldQuantity) {
                        // Reducir stock
                        $difference = $oldQuantity - $item['quantity'];
                        $productUnit->stock += $difference;
                    }
                    $productUnit->save();
                }
            } else {
                // Si no existe, agregar un nuevo detalle de venta
                $sale->details()->create([
                    'product_id' => $item['id'],
                    'unit_id' => $item['unitId'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['quantity'] * $item['price'],
                ]);

                // Ajustar el stock al agregar un nuevo detalle
                $productUnit = ProductUnit::where('product_id', $item['id'])
                                          ->where('unit_id', $item['unitId'])
                                          ->first();
                if ($productUnit) {
                    $productUnit->stock -= $item['quantity'];
                    $productUnit->save();
                }
            }

            // Agregar el detalle procesado a la lista
            $processedDetails[] = $item;
        }

        // Eliminar detalles de productos que no están en el carrito
        foreach ($sale->details as $detail) {
            $existsInCart = collect($processedDetails)->first(function($item) use ($detail) {
                return $item['id'] == $detail->product_id && $item['unitId'] == $detail->unit_id;
            });

            if (!$existsInCart) {
                // Ajustar el stock al eliminar un detalle
                $productUnit = ProductUnit::where('product_id', $detail->product_id)
                                          ->where('unit_id', $detail->unit_id)
                                          ->first();
                if ($productUnit) {
                    $productUnit->stock += $detail->quantity;
                    $productUnit->save();
                }

                $detail->delete();
            }
        }

        // Recalcular el total de la venta
        $newTotalAmount = $sale->details->sum('total');

        // Actualizar el total_amount solo si ha cambiado
        if ($newTotalAmount !== $oldTotalAmount) {
            $sale->total_amount = $newTotalAmount;
        }

        $sale->save();

        DB::commit(); // Confirmar la transacción

        return redirect()->route('sales.index')->with('success', 'Venta actualizada con éxito.');

    } catch (\Exception $e) {
        DB::rollBack(); // Revertir la transacción en caso de error
        return redirect()->route('sales.index')->with('error', 'Error al actualizar la venta: ' . $e->getMessage());
    }
}
public function printReceipt($id)
{
    // Cargar la venta con sus detalles
    $sale = Sale::with(['user', 'customer', 'details.product', 'details.unit'])->findOrFail($id);

    // Generar la vista de recibo como PDF
    $pdf = PDF::loadView('livewire.sales.receipt', compact('sale'));

    // Descargar el PDF directamente
    return $pdf->download('recibo-venta-' . $sale->id . '.pdf');
}

}