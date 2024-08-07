<?php

namespace App\Livewire\Products;

use Livewire\Component;

class Edit extends Component
{
    public $productId, $nombre, $descripcion, $cantidad, $precio, $categoria;

    public function mount(Product $product)
    {
        $this->productId = $product->id;
        $this->nombre = $product->nombre;
        $this->descripcion = $product->descripcion;
        $this->cantidad = $product->cantidad;
        $this->precio = $product->precio;
        $this->categoria = $product->categoria;
    }
    public function update()
    {
        $this->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:255',
            'cantidad' => 'required|integer|min:0',
            'precio' => 'required|numeric|min:0',
            'categoria' => 'required|string|max:255',
        ]);
        $product = Product::find($this->productId);
        $product->update([
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'cantidad' => $this->cantidad,
            'precio' => $this->precio,
            'categoria' => $this->categoria,
        ]);
        return redirect()->route('products.index');
    }


    public function render()
    {

        return view('livewire.products.edit');
    }
}
