@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-2xl mb-4">Surtir Productos</h1>

    @if($products->isEmpty())
        <div class="alert alert-warning">No hay productos disponibles para surtir.</div>
    @else
    <div class="table-responsive">
                <table class="table table-custom">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>Nombre del Producto</th>
                        <th>Unidad</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Cantidad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        @php
                            $firstUnit = true; // Variable para controlar si se muestra el nombre del producto
                        @endphp
                        @foreach ($product->productUnits as $unit)
                            <tr>
                                @if ($firstUnit)
                                    <td rowspan="{{ $product->productUnits->count() }}">{{ $product->name }}</td>
                                    @php $firstUnit = false; @endphp
                                @endif
                                <td>{{ $unit->unit->name }}</td>
                                <td>{{ $unit->unit->description }}</td>
                                <td>${{ number_format($unit->price, 2) }}</td>
                                <td>{{ $unit->stock }}</td>
                                <td>
                                    <input type="number" class="form-control quantity-input" data-unit-id="{{ $unit->unit->id }}" min="0" value="0" style="width: 80px;">
                                </td>
                                <td>
                                    <button class="btn btn-success btn-remove" data-unit-id="{{ $unit->unit->id }}" data-product-name="{{ $product->name }}">
                                        Surtir
                                    </button>
                                    <button class="btn btn-danger  btn-supply" data-unit-id="{{ $unit->unit->id }}" data-product-name="{{ $product->name }}">
                                        Quitar
                                    </button>
                                    
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Manejar el evento click para el botón "Surtir"
        document.querySelectorAll('.btn-supply').forEach(function (button) {
            button.addEventListener('click', function () {
                var unitId = this.getAttribute('data-unit-id');
                var quantityInput = document.querySelector('.quantity-input[data-unit-id="' + unitId + '"]');
                var quantity = parseInt(quantityInput.value);

                if (quantity > 0) {
                    // Hacer una solicitud AJAX para surtir el producto
                    fetch('{{ route("products.supply") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}' // Incluyendo el token CSRF
                        },
                        body: JSON.stringify({
                            unit_id: unitId,
                            quantity: quantity
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => { throw new Error(err.message); });
                        }
                        return response.json();
                    })
                    .then(data => {
                        alert(data.message); // Muestra el mensaje de éxito
                        location.reload(); // Recargar la página
                    })
                    .catch(error => {
                        alert('Error: ' + error.message);
                    });
                } else {
                    alert('Por favor, ingresa una cantidad válida  a quitar.');
                }
            });
        });

        // Manejar el evento click para el botón "Quitar"
        document.querySelectorAll('.btn-remove').forEach(function (button) {
            button.addEventListener('click', function () {
                var unitId = this.getAttribute('data-unit-id');
                var quantityInput = document.querySelector('.quantity-input[data-unit-id="' + unitId + '"]');
                var quantity = parseInt(quantityInput.value);

                if (quantity > 0) {
                    // Hacer una solicitud AJAX para quitar el producto
                    fetch('{{ route("products.remove") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}' // Incluyendo el token CSRF
                        },
                        body: JSON.stringify({
                            unit_id: unitId,
                            quantity: quantity
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => { throw new Error(err.message); });
                        }
                        return response.json();
                    })
                    .then(data => {
                        alert(data.message); // Muestra el mensaje de éxito
                        location.reload(); // Recargar la página
                    })
                    .catch(error => {
                        alert('Error: ' + error.message);
                    });
                } else {
                    alert('Por favor, ingresa una cantidad válida para surtir.');
                }
            });
        });
    });
</script>
@endpush
@endsection
