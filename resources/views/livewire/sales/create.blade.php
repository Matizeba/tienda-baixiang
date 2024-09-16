@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Crear Venta</h1>

    <form action="{{ route('sales.store') }}" method="POST">
        @csrf

        <!-- Selección del cliente -->
        <div class="mb-3">
            <label for="customer_id" class="form-label">Cliente</label>
            <select id="customer_id" name="customer_id" class="form-select" required>
                <option value="">Selecciona un cliente</option>
                @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Tabla de productos -->
        <table id="products-table" class="table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Fila de productos agregados aquí -->
            </tbody>
        </table>

        <!-- Selección del producto -->
        <div class="mb-3">
            <label for="product-select" class="form-label">Agregar Producto</label>
            <select id="product-select" class="form-select">
                <option value="">Selecciona un producto</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}" 
                            data-price="{{ $product->price }}" 
                            data-quantity="{{ $product->quantity }}">
                        {{ $product->name }} (Disponible: {{ $product->quantity }})
                    </option>
                @endforeach
            </select>
            <button type="button" id="add-product-btn" class="btn btn-secondary mt-2">Agregar Producto</button>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Venta</button>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const productSelect = document.getElementById('product-select');
    const addProductBtn = document.getElementById('add-product-btn');
    const productsTableBody = document.querySelector('#products-table tbody');

    addProductBtn.addEventListener('click', function() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const productId = selectedOption.value;
        const productName = selectedOption.text;
        const productPrice = selectedOption.getAttribute('data-price');
        const productQuantity = selectedOption.getAttribute('data-quantity'); // Cantidad disponible

        if (!productId) return;

        // Verificar si el producto ya está en la tabla
        const existingRow = Array.from(productsTableBody.rows).find(row => row.dataset.productId === productId);
        if (existingRow) {
            const quantityInput = existingRow.querySelector('input[name*="[quantity]"]');
            const newQuantity = parseInt(quantityInput.value) + 1;

            if (newQuantity <= productQuantity) {
                quantityInput.value = newQuantity;
            } else {
                alert('No puedes agregar más de la cantidad disponible');
            }

            return;
        }

        // Crear una nueva fila en la tabla de productos
        const row = document.createElement('tr');
        row.dataset.productId = productId;
        row.innerHTML = `
            <td>${productName}</td>
            <td>
                <input type="number" name="products[${productsTableBody.rows.length}][quantity]" 
                       value="1" min="1" max="${productQuantity}" 
                       class="form-control" required>
                <input type="hidden" name="products[${productsTableBody.rows.length}][id]" value="${productId}">
            </td>
            <td>${productPrice} Bs</td>
            <td>
                <button type="button" class="btn btn-danger btn-sm remove-product-btn">Eliminar</button>
            </td>
        `;

        productsTableBody.appendChild(row);

        // Limpiar selección
        productSelect.selectedIndex = 0;
    });

    productsTableBody.addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-product-btn')) {
            event.target.closest('tr').remove();
        }
    });
});
</script>
@endpush
@endsection
