<?php

namespace App\Livewire\Products;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $products = Product::all();
        return view('livewire.products.index', compact('products') );
    }
}
