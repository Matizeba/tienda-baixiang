<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function Index(Request $request)
    {
        $sortField = $request->input('sort_field', 'id');
        $sortDirection = $request->input('sort_direction', 'asc');

        $products = Product::orderBy($sortField, $sortDirection)->get();

        return view('livewire/products.index', compact('products', 'sortField', 'sortDirection'));
    }
    public function Create()
    {
        return view('livewire/products.create');
    }
    public function Edit(Product $product)
    {
        return view('livewire/products.edit', compact('product'));
    }
    public function Update(Request $request, Product $product)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:255',
            'cantidad' => 'required|integer|min:0',
            'precio' => 'required|numeric|min:0',
            'categoria' => 'required|string|max:255'.$product->id,
        ]);
        $product->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'cantidad' => $request->cantidad,
            'precio' => $request->precio,
            'categoria' => $request->categoria,
        ]);
        return redirect()->route('products.index')->with('success', 'Producto actualizado correctamente');
    }
    public function Destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Producto eliminado correctamente');
    }
    public function Store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:255',
            'cantidad' => 'required|integer',
            'precio' => 'required|numeric|min:0',
            'categoria' => 'required|string|max:255',
        ]);
        Product::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'cantidad' => $request->cantidad,
            'precio' => $request->precio,
            'categoria' => $request->categoria,
        ]);
        return redirect()->route('products.index')->with('success', 'Producto creado exitosamente.');
    }
}