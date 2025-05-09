<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\User;
use App\Models\Product;
use App\Models\Unit;
use App\Models\Category;
use App\Models\ProductUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SaleDetail;
use App\Services\PdfService;
use PDF; 

class PurchaseController extends Controller
{
    public function index(Request $request)
{
    $user = auth()->user(); // Obtén el usuario autenticado

    if ($user->role == 3) { // Verifica si el rol es 3
        // Muestra solo las compras del cliente asociado al usuario
        $purchases = Sale::with('customer')
            ->where('customer_id', $user->id) // Filtra por customer_id del usuario
            ->where('tipe_sale', 0) // Filtrar solo las compras
            ->paginate(10);
    } else {
        // Muestra todas las compras para otros roles
        $purchases = Sale::with('customer')
            ->where('tipe_sale', 0) // Filtrar solo las compras
            ->paginate(10);
    }

    return view('livewire.purchases.index', compact('purchases'));
}


public function view(Request $request)
{
    $categoryId = $request->input('category_id');
    $searchTerm = $request->input('search_term');
    $status = $request->input('status', 1); 

    // Consulta base de productos con sus unidades
    $query = Product::with('productUnits.unit') // Cargar unidades asociadas a través de product_units
                    ->whereHas('productUnits', function($query) {
                        $query->where('stock', '>', 0); // Filtrar solo las unidades con stock mayor que 0
                    })
                    ->where('status', 1); // Mostrar solo productos habilitados por defecto

    // Filtrar por categoría
    if ($categoryId) {
        $query->where('category_id', $categoryId);
    }

    // Filtrar por nombre
    if ($searchTerm) {
        $query->where('name', 'like', '%' . $searchTerm . '%');
    }

    // Filtrar por estado (si se aplica)
    if ($status !== null) { // Comprobamos que el estado no sea nulo
        $query->where('status', $status);
    }

    // Ejecutar la consulta para obtener los productos filtrados
    $products = $query->get(); // Obtener productos después de aplicar los filtros

    $categories = Category::all(); // Obtener todas las categorías

    // Retornar la vista con los productos y categorías
    return view('livewire/purchases.view', compact('products', 'categories', 'searchTerm', 'categoryId', 'status'));
}



public function store(Request $request)
{
    $validatedData = $request->validate([
        'products' => 'required|array',
    ]);

    // Iniciar una transacción
    DB::beginTransaction();
    try {
        // Crear la venta
        $sale = new Sale();
        $sale->user_id =auth()->id();
        $sale->customer_id = auth()->id();
        $sale->total_amount = 0; 
        $sale->tipe_sale = 0;
        $sale->status = 'completed'; 
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

        return redirect()->route('purchases.index')->with('success', 'Venta creada con éxito.');
    } catch (\Exception $e) {
        // Deshacer la transacción si algo falla
        DB::rollBack();
        return redirect()->route('purchases.view')->with('error', 'Error al crear la venta: ' . $e->getMessage());
    }
    
}

public function show($id)
{
    // Mostrar detalles de la venta
    $sale = Sale::with(['user', 'customer', 'details.product', 'details.unit'])->findOrFail($id);
    
    return view('livewire.purchases.show', compact('sale'));
}

    
}