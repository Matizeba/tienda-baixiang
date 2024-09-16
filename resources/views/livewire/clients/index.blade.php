@extends('layouts.app')

@section('breadcrumbs')
  <a href="{{ route('categories.index') }}" class="text-white">/ Categorías</a>
@endsection

@section('content')
@php
    use App\Models\User;
@endphp

<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3 text-danger"><i class="fas fa-tags"></i> Lista de Categorías</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('categories.export') }}" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Exportar
            </a>

            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Registrar Nueva Categoría
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header card-header-custom">
            <i class="fas fa-tags"></i> Categorías
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th scope="col"><i class="fas fa-hashtag"></i> Nro.</th>
                            <th scope="col"><i class="fas fa-tag"></i> Nombre</th>
                            <th scope="col"><i class="fas fa-info-circle"></i> Descripción</th>
                            <th scope="col">ID Usuario</th>
                            <th scope="col"><i class="fas fa-check-circle"></i> Estado</th>
                            <th scope="col"><i class="fas fa-cogs"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->description ?? 'N/A' }}</td>
                                <td>{{ optional(User::find($category->userId))->name }}</td>
                                <td>
                                    <span class="badge {{ $category->status ? 'bg-success' : 'bg-danger' }}">
                                        {{ $category->status ? 'Habilitada' : 'Deshabilitada' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-secondary">
                                        <i class="fas fa-edit"></i> 
                                    </a>
                                    <button type="button" class="btn {{ $category->status ? 'btn-danger' : 'btn-success' }}" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#toggleStatusModal"
                                            data-category-id="{{ $category->id }}"
                                            data-category-name="{{ $category->name }}"
                                            data-category-status="{{ $category->status }}">
                                        <i class="fas {{ $category->status ? 'fa-toggle-off' : 'fa-toggle-on' }}"></i>
                                        {{ $category->status ? 'Deshabilitar' : 'Habilitar' }}
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

<!-- Modal de Cambio de Estado -->
<div class="modal fade" id="toggleStatusModal" tabindex="-1" aria-labelledby="toggleStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title" id="toggleStatusModalLabel"><i class="fas fa-exclamation-triangle"></i> Confirmar Cambio de Estado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas <strong id="toggleStatusAction"></strong> la categoría <strong id="categoryName"></strong>? Esta acción cambiará el estado de la categoría.
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var toggleStatusModal = document.getElementById('toggleStatusModal');
        toggleStatusModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Botón que abrió el modal
            var categoryId = button.getAttribute('data-category-id'); // ID de la categoría
            var categoryName = button.getAttribute('data-category-name'); // Nombre de la categoría
            var categoryStatus = button.getAttribute('data-category-status'); // Estado de la categoría
            
            // Configura la acción del formulario en el modal
            var form = toggleStatusModal.querySelector('#toggleStatusForm');
            form.action = '/categories/' + categoryId + '/toggle-status';

            // Establece el texto de la acción en el modal
            var actionText = categoryStatus == 1 ? 'deshabilitar' : 'habilitar';
            var toggleStatusActionElement = document.getElementById('toggleStatusAction');
            toggleStatusActionElement.textContent = actionText;

            // Establece el nombre de la categoría en el modal
            var categoryNameElement = document.getElementById('categoryName');
            categoryNameElement.textContent = categoryName;
        });
    });
</script>
@endpush
@endsection
