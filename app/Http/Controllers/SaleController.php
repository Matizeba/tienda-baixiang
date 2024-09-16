<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SaleDetail;
use App\Services\PdfService;


class SaleController extends Controller
{
    public function index(Request $request)
    {
        // Cargar ventas con detalles
        $sales = Sale::with('user', 'customer', 'details.product')->get();
        return view('livewire.sales.index', compact('sales'));
    }

    public function show($id)
    {
        // Mostrar detalles de la venta
        $sale = Sale::with('details.product')->findOrFail($id);
        return view('livewire.sales.show', compact('sale'));
    }

    public function create()
    {
        // Obtener productos y clientes para la vista de creación
        $products = Product::all();
        $customers = User::where('role', 3)->get(); // Clientes tienen el role 3

        return view('livewire/sales.create', compact('products', 'customers'));
    }



    public function store(Request $request, PdfService $pdfService)
    {
        // Validación de los datos de la venta
        $request->validate([
            'customer_id' => 'required|exists:users,id',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);
    
        // Iniciar una transacción
        DB::beginTransaction();
        try {
            // Crear la venta
            $sale = Sale::create([
                'user_id' => auth()->id(),
                'customer_id' => $request->input('customer_id'),
                'total_amount' => 0,
                'status' => 'pending',
            ]);
    
            $totalAmount = 0;
    
            foreach ($request->input('products') as $product) {
                $productId = $product['id'];
                $quantity = $product['quantity'];
                $productModel = Product::findOrFail($productId);
    
                if ($productModel->quantity < $quantity) {
                    throw new \Exception('Stock insuficiente para el producto: ' . $productModel->name);
                }
    
                $price = $productModel->price;
                $total = $price * $quantity;
    
                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total' => $total,
                ]);
    
                $totalAmount += $total;
                $productModel->decrement('quantity', $quantity);
            }
    
            $sale->update(['total_amount' => $totalAmount]);
    
            DB::commit();
    
            // Generar el PDF de la nota de venta
            $pdf = $pdfService->generatePdf('livewire.sales.receipt', ['sale' => $sale]);
    
            // Devolver el PDF para su descarga
            return response()->stream(
                function () use ($pdf) {
                    echo $pdf;
                },
                200,
                [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'attachment; filename="nota_de_venta_' . $sale->id . '.pdf"',
                ]
            );
    
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Hubo un error al crear la venta. ' . $e->getMessage()])->withInput();
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


