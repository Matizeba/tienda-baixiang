@extends('layouts.app')

@section('breadcrumbs')
    <a href="{{ route('products.index') }}" class="text-white">/ Productos</a> <h1 class="text-white"> / Registrar</h1>
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3">Registrar Nuevo Producto</h1>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Volver</a>
    </div>

    <div class="card">
        <div class="card-header">
            Formulario de Registro de Producto
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="productForm" action="{{ route('products.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                </div>

                <div class="form-group">
                    <label for="description">Descripción</label>
                    <textarea name="description" id="description" class="form-control" rows="3" required>{{ old('description') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="quantity">Cantidad</label>
                    <input type="number" min="0" step="1" name="quantity" id="quantity" class="form-control" value="{{ old('quantity') }}" required>
                </div>

                <div class="form-group">
                    <label for="price">Precio</label>
                    <input type="number" min="0" step="0.50" name="price" id="price" class="form-control" value="{{ old('price') }}" required>
                </div>

                <div class="form-group">
                    <label for="category">Categoría</label>
                    <input type="number" min="1" step="1" name="category" id="category" class="form-control" value="{{ old('category') }}" required>
                </div>

                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#confirmModal">
                    Registrar
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Confirmación -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Confirmar Registro</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Estás a punto de registrar un nuevo producto con los siguientes datos:</p>
                <ul>
                    <li><strong>Nombre:</strong> <span id="modalName"></span></li>
                    <li><strong>Descripción:</strong> <span id="modalDescription"></span></li>
                    <li><strong>Cantidad:</strong> <span id="modalQuantity"></span></li>
                    <li><strong>Precio:</strong> <span id="modalPrice"></span></li>
                    <li><strong>Categoría:</strong> <span id="modalCategory"></span></li>
                </ul>
                <p>¿Estás seguro de que deseas continuar?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirmButton">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Cargar datos en el modal cuando se hace clic en "Registrar"
        const nameInput = document.getElementById('name');
        const descriptionInput = document.getElementById('description');
        const quantityInput = document.getElementById('quantity');
        const priceInput = document.getElementById('price');
        const categoryInput = document.getElementById('category');

        const modalName = document.getElementById('modalName');
        const modalDescription = document.getElementById('modalDescription');
        const modalQuantity = document.getElementById('modalQuantity');
        const modalPrice = document.getElementById('modalPrice');
        const modalCategory = document.getElementById('modalCategory');

        document.querySelector('[data-target="#confirmModal"]').addEventListener('click', function() {
            modalName.textContent = nameInput.value;
            modalDescription.textContent = descriptionInput.value;
            modalQuantity.textContent = quantityInput.value;
            modalPrice.textContent = priceInput.value;
            modalCategory.textContent = categoryInput.value;
        });

        // Enviar el formulario al confirmar
        document.getElementById('confirmButton').addEventListener('click', function() {
            document.getElementById('productForm').submit();
        });
    });
</script>
@endsection
