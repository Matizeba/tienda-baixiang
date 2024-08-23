<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $sortField = $request->input('sort_field', 'id');
        $sortDirection = $request->input('sort_direction', 'asc');
        
        $products = Product::orderBy($sortField, $sortDirection)->get();

        return view('livewire/products.index', compact('products', 'sortField', 'sortDirection'));
    }

    public function create()
    {
        return view('livewire/products.create');
    }

    public function edit(Product $product)
    {
        return view('livewire/products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'], // Solo letras y espacios
            'description' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'], // Solo letras y espacios
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'category' => 'required|numeric', // Solo números para categorías
        ]);

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'category' => $request->category,
        ]);

        return redirect()->route('products.index')->with('success', 'Producto actualizado con éxito.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Producto eliminado con éxito.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'], // Solo letras y espacios
            'description' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'], // Solo letras y espacios
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'category' => 'required|numeric', // Solo números para categorías
        ]);

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'category' => $request->category,
        ]);

        return redirect()->route('products.index')->with('success', 'Producto creado con éxito.');
    }

    public function toggleStatus($id)
    {
        $product = Product::findOrFail($id);
        $product->status = !$product->status; // Cambiar el estado
        $product->save();

        return redirect()->route('products.index')->with('success', 'Estado del producto actualizado con éxito.');
    }
}
