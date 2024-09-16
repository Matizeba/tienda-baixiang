<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index(Request $request)
{
    // Asegúrate de que las ventas se cargan con los detalles
    $sales = Sale::with('user', 'customer', 'details.product')->get();
    return view('livewire/sales.index', compact('sales'));
}

// app/Http/Controllers/SaleController.php

public function show($id)
{
    $sale = Sale::with('details.product')->findOrFail($id);

    return view('livewire/sales.show', compact('sale'));
}





}
