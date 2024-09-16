@extends('layouts.app')

@section('breadcrumbs')
    <a href="{{ route('products.index') }}" class="text-white">/ Productos </a>
    <h1 class="text-white"> / Editar</h1>
@endsection

@section('content')
<div class="container">
    
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3">Editar Producto</h1>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Volver</a>
    </div>
    
    <div class="card">
        <div class="card-header">
            Formulario de Edición de Producto
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

            <form id="productForm" action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="description">Descripción</label>
                    <textarea name="description" id="description" class="form-control" rows="4" required>{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="form-group">
                    <label for="quantity">Cantidad</label>
                    <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $product->quantity) }}" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="price">Precio</label>
                    <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}" class="form-control" min="1" step="0.50" required>
                </div>

                <div class="form-group">
                    <label for="category_id">Categoría</label>
                    <select name="category_id" id="category_id" class="form-control" required>
                        <option value="">Selecciona una categoría</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Mostrar la imagen actual -->
                <div class="form-group mt-4">
                    <label for="current_image">Imagen Actual</label>
                    <div class="mb-2">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="Imagen del Producto" class="img-thumbnail" width="150">
                        @else
                            <p>No hay imagen disponible</p>
                        @endif
                    </div>
                </div>

                <!-- Subir nueva imagen -->
                <div class="form-group">
                    <label for="image">Reemplazar Imagen</label>
                    <input type="file" name="image" id="image" class="form-control">
                    <small class="form-text text-muted">Opcional. Si subes una nueva imagen, la anterior será reemplazada.</small>
                </div>

                <button type="button" class="btn btn-success mt-4" data-toggle="modal" data-target="#confirmModal">
                    Actualizar
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
                <h5 class="modal-title" id="confirmModalLabel">Confirmar Actualización</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Estás a punto de actualizar los datos del producto con los siguientes detalles:</p>
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
        // Cargar datos en el modal cuando se hace clic en "Actualizar"
        const nameInput = document.getElementById('name');
        const descriptionInput = document.getElementById('description');
        const quantityInput = document.getElementById('quantity');
        const priceInput = document.getElementById('price');
        const categoryInput = document.getElementById('category_id');

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
            modalCategory.textContent = categoryInput.options[categoryInput.selectedIndex].text;
        });

        // Enviar el formulario al confirmar
        document.getElementById('confirmButton').addEventListener('click', function() {
            document.getElementById('productForm').submit();
        });
    });
</script>
@endsection
