<?php

namespace App\Livewire\Products;

use Livewire\Component;

class Create extends Component
{
    public $nombre, $descripcion, $cantidad, $precio, $categoria;

    public function create()
    {
        $this->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:255',
            'cantidad' => 'required|integer|min:1',
            'precio' => 'required|numeric|min:0.01',
            'categoria' => 'required|string|max:255',
        ]);

        Product::create([
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
        return view('livewire.products.create');
    }
}
