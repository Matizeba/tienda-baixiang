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
        $purchases = Sale::with('customer')
            ->where('user_id', auth()->id())
            ->where('tipe_sale', 0) // Filtrar solo las compras
            ->paginate(10);

        return view('livewire.purchases.index', compact('purchases'));
    }

    public function view(Request $request)
{
    // Filtros para los productos
    $categoryId = $request->input('category_id');
    $searchTerm = $request->input('search_term');

    // Consulta base de productos con unidades
    $query = Product::with('units');  // Asegúrate de cargar las unidades asociadas

    // Filtrar por categoría
    if ($categoryId) {
        $query->where('category_id', $categoryId);
    }

    // Filtrar por nombre
    if ($searchTerm) {
        $query->where('name', 'like', '%' . $searchTerm . '%');
    }

    
    $categories = Category::all();
    $products = Product::with('productUnits.unit')->get();
    return view('livewire/purchases.view', compact('products', 'categories', 'searchTerm', 'categoryId'));
}




    
}