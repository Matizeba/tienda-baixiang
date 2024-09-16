<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SaleDetail;

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

    public function store(Request $request)
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
            'user_id' => auth()->id(), // Suponiendo que el usuario está autenticado
            'customer_id' => $request->input('customer_id'),
            'total_amount' => 0, // El total se actualizará más tarde
            'status' => 'pending', // Puedes cambiar el estado según sea necesario
        ]);

        $totalAmount = 0;

        foreach ($request->input('products') as $product) {
            $productId = $product['id'];
            $quantity = $product['quantity'];
            $productModel = Product::findOrFail($productId);

            // Verificar si hay suficiente stock
            if ($productModel->quantity < $quantity) {
                throw new \Exception('Stock insuficiente para el producto: ' . $productModel->name);
            }

            $price = $productModel->price;
            $total = $price * $quantity;

            // Crear detalles de la venta
            SaleDetail::create([
                'sale_id' => $sale->id,
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $price,
                'total' => $total,
            ]);

            // Sumar el total a la venta
            $totalAmount += $total;

            // Actualizar el stock del producto
            $productModel->decrement('quantity', $quantity);
        }

        // Actualizar el monto total de la venta
        $sale->update(['total_amount' => $totalAmount]);

        // Confirmar la transacción
        DB::commit();

        return redirect()->route('sales.index')->with('success', 'Venta creada exitosamente.');
    } catch (\Exception $e) {
        // Revertir la transacción en caso de error
        DB::rollBack();
        return back()->withErrors(['error' => 'Hubo un error al crear la venta. ' . $e->getMessage()])->withInput();
    }
}


}