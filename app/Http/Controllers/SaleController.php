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
    
    // Variables para obtener la información requerida
    $productName = $sale->details->pluck('product.name'); // Nombres de los productos
    $unitName = $sale->details->pluck('unit.name'); // Nombres de las unidades
    $productPrice = $sale->details->pluck('price'); // Precios de los productos

    // Retorna la vista con los datos de la venta
    return view('livewire.sales.show', compact('sale', 'productName', 'unitName', 'productPrice'));
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
        $sale->status = 'completed'; // Cambiar el estado a 'completed' al finalizar
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




    // Ejemplo de controlador para la vista 'edit'
    public function edit($id)
    {
        $sale = Sale::with('saleDetails.product')->find($id);
        if (!$sale) {
            return redirect()->route('sales.index')->with('error', 'Venta no encontrada.');
        }

        // Obtener productos y clientes
        $products = Product::all();
        $customers = \App\Models\User::where('role', 3)->get(); // Obtener clientes (usuarios con role 3)

        return view('livewire/sales.edit', compact('sale', 'products', 'customers'));
    }

    // Método para actualizar una venta
    public function update(Request $request, $id)
{
    $sale = Sale::find($id);
    if (!$sale) {
        return redirect()->route('sales.index')->with('error', 'Venta no encontrada.');
    }

    // Validar y actualizar venta
    $validated = $request->validate([
        'customer_id' => 'required|exists:users,id',
        'products' => 'required|array',
        'products.*.quantity' => 'required|integer|min:1',
        'products.*.id' => 'required|exists:products,id',
    ]);

    // Obtener detalles anteriores de la venta para restaurar stock
    $previousDetails = $sale->saleDetails()->get();

    // Restaurar el stock anterior
    foreach ($previousDetails as $detail) {
        $product = Product::find($detail->product_id);
        $product->increment('quantity', $detail->quantity);
    }

    // Actualizar información básica de la venta
    $sale->customer_id = $validated['customer_id'];
    $sale->save();

    // Eliminar detalles de venta antiguos
    $sale->saleDetails()->delete();

    // Inicializar el monto total
    $totalAmount = 0;

    // Crear nuevos detalles de venta y calcular el monto total
    foreach ($validated['products'] as $product) {
        $productId = $product['id'];
        $quantity = $product['quantity'];
        $productModel = Product::findOrFail($productId);
        $price = $productModel->price;
        $total = $price * $quantity;

        // Crear detalle de venta
        SaleDetail::create([
            'sale_id' => $sale->id,
            'product_id' => $productId,
            'quantity' => $quantity,
            'price' => $price,
            'total' => $total,
        ]);

        // Acumulación del monto total
        $totalAmount += $total;

        // Reducir el stock del producto
        $productModel->decrement('quantity', $quantity);
    }

    // Actualizar el monto total de la venta
    $sale->update(['total_amount' => $totalAmount]);

    return redirect()->route('sales.index')->with('success', 'Venta actualizada con éxito.');
}

public function destroy($id)
{
    $sale = Sale::find($id);
    if (!$sale) {
        return redirect()->route('sales.index')->with('error', 'Venta no encontrada.');
    }

    // Iniciar una transacción
    DB::beginTransaction();
    try {
        // Recuperar detalles de la venta para restaurar el stock
        foreach ($sale->saleDetails as $detail) {
            $product = Product::find($detail->product_id);
            $product->increment('quantity', $detail->quantity);
        }

        // Eliminar los detalles de la venta
        $sale->saleDetails()->delete();

        // Eliminar la venta
        $sale->delete();

        // Confirmar la transacción
        DB::commit();

        return redirect()->route('sales.index')->with('success', 'Venta eliminada con éxito.');
    } catch (\Exception $e) {
        // Revertir la transacción en caso de error
        DB::rollBack();
        return redirect()->route('sales.index')->with('error', 'Hubo un error al eliminar la venta. ' . $e->getMessage());
    }
}

}


