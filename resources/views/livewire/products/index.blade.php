@extends('layouts.app')

@section('breadcrumbs')
<h1 class="text-white">/ Productos</h1>
@endsection

@section('content')
@php
use App\Models\User;
@endphp

<div class="container mx-auto">
    <div class="flex justify-between items-center my-4">
        <h1 class="text-2xl text-red-600"><i class="fas fa-boxes"></i> Lista de Productos</h1>
        <div class="flex gap-2">
            <a href="{{ route('products.export') }}" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Exportar
            </a>
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Registrar Nuevo Producto
            </a>
            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#unitsModal">
                <i class="fas fa-th"></i> Unidades
            </button>
        </div>
    </div>

    <div class="card">
        <div class="card-header card-header-custom">
            <i class="fas fa-boxes"></i> Productos
        </div>
        <div class="card-body">
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr class="w-full bg-gray-200 text-gray-700">
                            <th class="py-2 px-4 border">Nro.</th>
                            <th class="py-2 px-4 border">Imagen</th>
                            <th class="py-2 px-4 border">Nombre</th>
                            <th class="py-2 px-4 border">Categoría</th>
                            <th class="py-2 px-4 border">Estado</th>
                            <th class="py-2 px-4 border">Usuario</th>
                            <th class="py-2 px-4 border">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr class="hover:bg-gray-100">
                                <td class="py-2 px-4 border">{{ $loop->iteration }}</td>
                                <td class="py-2 px-4 border">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-24 h-24 object-cover">
                                    @else
                                        <img src="{{ asset('images/default-product.png') }}" alt="Imagen por defecto" class="w-24 h-24 object-cover">
                                    @endif
                                </td>
                                <td class="py-2 px-4 border">{{ $product->name }}</td>
                                <td class="py-2 px-4 border">{{ optional($product->category)->name }}</td>
                                <td class="py-2 px-4 border">
                                    <span class="badge {{ $product->status == 1 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $product->status == 1 ? 'Disponible' : 'No disponible' }}
                                    </span>
                                </td>
                                <td class="py-2 px-4 border">{{ optional(User::find($product->user_id))->name }}</td>
                                <td class="py-2 px-4 border">
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-secondary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#unitDetailsModal" data-product="{{ json_encode($product->productUnits) }}">
                                        <i class="fas fa-info-circle"></i>
                                    </button>
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#toggleStatusModal" data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}" data-product-status="{{ $product->status }}">
                                        <i class="fas {{ $product->status ? 'fa-toggle-off' : 'fa-toggle-on' }}"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Detalles de Unidades -->
<div class="modal fade" id="unitDetailsModal" tabindex="-1" aria-labelledby="unitDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="unitDetailsModalLabel">Detalles de Unidades</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="unitDetailsContent"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Cambio de Estado -->
<div class="modal fade" id="toggleStatusModal" tabindex="-1" aria-labelledby="toggleStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title" id="toggleStatusModalLabel"><i class="fas fa-exclamation-triangle"></i> Confirmar Cambio de Estado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas <strong id="toggleStatusAction"></strong> el producto <strong id="productName"></strong>? Esta acción cambiará el estado del producto.
            </div>
            <div class="modal-footer">
                <form id="toggleStatusForm" action="" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">Confirmar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="unitsModal" tabindex="-1" aria-labelledby="unitsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="unitsModalLabel">Tipos de Unidades</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr class="w-full bg-gray-200 text-gray-700">
                                <th class="py-2 px-4 border">Unidad</th>
                                <th class="py-2 px-4 border">Descripción</th>
                                <th class="py-2 px-4 border">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($units as $unit)
                                <tr class="hover:bg-gray-100">
                                    <td class="py-2 px-4 border">{{ $unit->name }}</td>
                                    <td class="py-2 px-4 border">{{ $unit->description }}</td>
                                    <td class="py-2 px-4 border">
                                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#editUnitModal" data-unit='{{ json_encode($unit) }}'>
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUnitModal">
                    Agregar Nueva Unidad
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="createUnitModal" tabindex="-1" aria-labelledby="createUnitModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createUnitModalLabel">Crear Nueva Unidad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createUnitForm" method="POST" action="{{ route('units.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre de Unidad</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Descripción</label>
                        <input type="text" class="form-control" id="description" name="description">
                    </div>
                    <button type="submit" class="btn btn-primary">Crear Unidad</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="editUnitModal" tabindex="-1" aria-labelledby="editUnitModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUnitModalLabel">Editar Unidad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editUnitForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="editName" class="form-label">Nombre de Unidad</label>
                        <input type="text" class="form-control" id="editName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="editDescription" class="form-label">Descripción</label>
                        <input type="text" class="form-control" id="editDescription" name="description">
                    </div>
                    <button type="submit" class="btn btn-primary">Actualizar Unidad</button>
                </form>
            </div>
        </div>
    </div>
</div>


@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Modal de cambio de estado
        var toggleStatusModal = document.getElementById('toggleStatusModal');
        toggleStatusModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; 
            var productId = button.getAttribute('data-product-id'); 
            var productName = button.getAttribute('data-product-name'); 
            var productStatus = button.getAttribute('data-product-status'); 
            var form = toggleStatusModal.querySelector('#toggleStatusForm');
            form.action = '/products/' + productId + '/toggle-status';

            var actionText = productStatus == 1 ? 'deshabilitar' : 'habilitar';
            document.getElementById('toggleStatusAction').textContent = actionText;
            document.getElementById('productName').textContent = productName;
        });

        // Modal para detalles de unidades
        var unitDetailsModal = document.getElementById('unitDetailsModal');
        unitDetailsModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; 
            var productUnits = JSON.parse(button.getAttribute('data-product'));
            var productName = button.closest('tr').querySelector('td:nth-child(3)').textContent; // Obtener el nombre del producto
            var content = '<table class="table"><thead><tr><th>Unidad</th><th>Precio</th><th>Descripción</th><th>Cantidad</th></tr></thead><tbody>';
            productUnits.forEach(function(productUnit) {
                content += '<tr><td>' + productUnit.unit.name + '</td><td>' + productUnit.price + '</td><td>' + productUnit.unit.description + '</td><td>' + productUnit.stock + '</td></tr>';
            });
            content += '</tbody></table>';
            document.getElementById('unitDetailsContent').innerHTML = content;

            // Actualizar el título del modal con el nombre del producto
            document.getElementById('unitDetailsModalLabel').textContent = 'Detalles de Unidades para ' + productName;
        });
    });
    document.addEventListener('DOMContentLoaded', function () {
        // Cargar datos de unidad en el modal de edición
        var editUnitModal = document.getElementById('editUnitModal');
        editUnitModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Botón que activó el modal
            var unit = JSON.parse(button.getAttribute('data-unit')); // Extraer los datos de unidad

            // Llenar el formulario con los datos de la unidad
            document.getElementById('editUnitForm').action = '/units/' + unit.id; // Ajustar la acción del formulario
            document.getElementById('editName').value = unit.name; // Nombre
            document.getElementById('editDescription').value = unit.description; // Descripción
        });
    });
</script>
@endpush

@endsection
