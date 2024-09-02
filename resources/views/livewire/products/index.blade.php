@extends('layouts.app')

@section('breadcrumbs')
<h1 class="text-white">/ Productos</h1>
@endsection

@section('content')
@php
    use App\Models\User;
@endphp

<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3 text-danger"><i class="fas fa-boxes"></i> Lista de Productos</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('products.export') }}" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Exportar
            </a>

            <a href="{{ route('products.create') }}" class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Registrar Nuevo Usuario
            </a>
        </div>
       
    </div>

    <div class="card">
        <div class="card-header card-header-custom">
            <i class="fas fa-boxes"></i> Productos
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th scope="col"><i class="fas fa-hashtag"></i> Nro.</th>
                            <th scope="col"><i class="fas fa-box"></i> Nombre</th>
                            <th scope="col"><i class="fas fa-dollar-sign"></i> Precio</th>
                            <th scope="col"><i class="fas fa-dollar-sign"></i> Cantidad</th>
                            <th scope="col"><i class="fas fa-clipboard-list"></i> Categoría</th>
                            @if(Auth::user()->role == 1)
                            <th scope="col"><i class="fas fa-cogs"></i> Estado</th>
                            <th scope="col"><i class="fas fa-cogs"></i> Usuario</th>

                            <th scope="col"><i class="fas fa-cogs"></i> Acciones</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $product->name }} </td>
                                <td>{{ $product->price }}<span> Bs</span></td>
                                <td>{{ $product->quantity }}</td>
                                <td>{{ $product->category}}</td>
                                @if(Auth::user()->role == 1)
                                <td>
                                
                                    <span class="badge {{ $product->status == 1 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $product->status == 1 ? 'Disponible' : 'No disponible' }}
                                    </span>
                                </td>
                                <td>{{ optional(User::find($product->userId))->name }}</td>
                                <td>
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-secondary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn {{ $product->status ? 'btn-danger' : 'btn-success' }}" data-bs-toggle="modal" data-bs-target="#toggleStatusModal" data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}" data-product-status="{{ $product->status }}">
                                        <i class="fas {{ $product->status ? 'fa-toggle-off' : 'fa-toggle-on' }}"></i>
                                    </button>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm" style="background-color: black; color: white;">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                                @endif
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var toggleStatusModal = document.getElementById('toggleStatusModal');
        toggleStatusModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; 
            var productId = button.getAttribute('data-product-id'); 
            var productName = button.getAttribute('data-product-name'); 
            var productStatus = button.getAttribute('data-product-status'); 
            var form = toggleStatusModal.querySelector('#toggleStatusForm');
            form.action = '/products/' + productId + '/toggle-status';

            var actionText = productStatus == 1 ? 'deshabilitar' : 'habilitar';
            var toggleStatusActionElement = document.getElementById('toggleStatusAction');
            toggleStatusActionElement.textContent = actionText;

            var productNameElement = document.getElementById('productName');
            productNameElement.textContent = productName;
        });
    });
</script>
@endpush
@endsection
