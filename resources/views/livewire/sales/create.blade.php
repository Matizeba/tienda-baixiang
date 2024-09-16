@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Crear Venta</h2>
    
    <!-- Mostrar mensajes de éxito o error -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('sales.store') }}" method="POST">
        @csrf
        
        <!-- Seleccionar vendedor -->
        <div class="form-group">
            <label for="user_id">Vendedor</label>
            <select name="user_id" id="user_id" class="form-control" required>
                <option value="">Seleccione un vendedor</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Seleccionar cliente -->
        <div class="form-group">
            <label for="customer_id">Cliente</label>
            <select name="customer_id" id="customer_id" class="form-control" required>
                <option value="">Seleccione un cliente</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Selección de productos y cantidad -->
        <div class="form-group">
            <label for="products">Productos</label>
            <div id="product-list">
                <!-- Productos seleccionados se mostrarán aquí -->
            </div>
            <div class="input-group mb-3">
                <select id="product_id" class="form-control" required>
                    <option value="">Seleccione un producto</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                            {{ $product->name }} - {{ $product->price }} Bs
                        </option>
                    @endforeach
                </select>
                <input type="number" id="quantity" class="form-control" placeholder="Cantidad" min="1" required>
                <div class="input-group-append">
                    <button type="button" id="add-product" class="btn btn-primary">Agregar</button>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-success">Guardar Venta</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const productList = document.getElementById('product-list');
    const productSelect = document.getElementById('product_id');
    const quantityInput = document.getElementById('quantity');
    const addProductButton = document.getElementById('add-product');

    addProductButton.addEventListener('click', function () {
        const productId = productSelect.value;
        const quantity = quantityInput.value;
        const productOption = productSelect.options[productSelect.selectedIndex];
        const productName = productOption.text;
        const productPrice = productOption.getAttribute('data-price');
        
        if (!productId || !quantity) {
            alert('Por favor, seleccione un producto y una cantidad.');
            return;
        }

        const existingProduct = document.querySelector(`#product-${productId}`);
        if (existingProduct) {
            alert('El producto ya está en la lista.');
            return;
        }

        const itemDiv = document.createElement('div');
        itemDiv.id = `product-${productId}`;
        itemDiv.className = 'mb-2';
        itemDiv.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <span>${productName} - ${quantity} x ${productPrice} Bs</span>
                <button type="button" class="btn btn-danger btn-sm remove-product" data-product-id="${productId}">Eliminar</button>
            </div>
            <input type="hidden" name="items[${productId}][product_id]" value="${productId}">
            <input type="hidden" name="items[${productId}][quantity]" value="${quantity}">
        `;

        productList.appendChild(itemDiv);
        quantityInput.value = '';
        productSelect.value = '';
    });

    productList.addEventListener('click', function (event) {
        if (event.target.classList.contains('remove-product')) {
            const productId = event.target.getAttribute('data-product-id');
            const itemDiv = document.getElementById(`product-${productId}`);
            productList.removeChild(itemDiv);
        }
    });
});
</script>
@endsection
