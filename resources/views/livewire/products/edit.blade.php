@extends('layouts.app')

@section('breadcrumbs')
    <a href="{{ route('products.index') }}" class="text-white">/ Productos</a> <h1 class="text-white"> / Editar</h1>
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
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                </div>

                <div class="form-group">
                    <label for="description">Descripción</label>
                    <textarea name="description" id="description" class="form-control" rows="3" required>{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="form-group">
                    <label for="category">Categoría</label>
                    <select name="category_id" id="category_id" class="form-control" required>
                        <option value="">Selecciona una categoría</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ $category->id == old('category_id', $product->category_id) ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="image">Imagen del Producto</label>
                    <input type="file" name="image" id="image" class="form-control-file" accept="image/*">
                    @if ($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="Imagen del producto" class="img-fluid mt-2" style="max-width: 150px;">
                    @endif
                </div>

                <h5>Unidades</h5>
                <div id="unitsContainer">
                    @foreach ($product->productUnits as $index => $productUnit)
                        <div class="unit">
                            <select name="units[{{ $index }}][id]" required>
                                <option value="">Selecciona una unidad</option>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}" {{ $unit->id == $productUnit->unit_id ? 'selected' : '' }}>{{ $unit->name }} ({{ $unit->description }})</option>
                                @endforeach
                            </select>
                            <input type="number" name="units[{{ $index }}][price]" placeholder="Precio" value="{{ old('units.' . $index . '.price', $productUnit->price) }}" required>
                            <input type="number" name="units[{{ $index }}][stock]" placeholder="Stock" value="{{ old('units.' . $index . '.stock', $productUnit->stock) }}" required>
                            <button type="button" class="btn btn-danger" onclick="removeUnit(this)">Eliminar</button>
                        </div>
                    @endforeach
                </div>
                <button type="button" class="btn btn-primary" onclick="addUnit()">Agregar Unidad</button>

                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#confirmModal">
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
                <p>Estás a punto de actualizar el producto con los siguientes datos:</p>
                <ul>
                    <li><strong>Nombre:</strong> <span id="modalName"></span></li>
                    <li><strong>Descripción:</strong> <span id="modalDescription"></span></li>
                    <li><strong>Categoría:</strong> <span id="modalCategory"></span></li>
                    <li><strong>Imagen:</strong> <span id="modalImage"></span></li>
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
    let unitIndex = {{ count($product->productUnits) }}; // Inicializa el índice basado en la cantidad de unidades

    function addUnit() {
        const unitsContainer = document.getElementById('unitsContainer');
        const unitDiv = document.createElement('div');
        unitDiv.classList.add('unit');
        unitDiv.innerHTML = `
            <select name="units[${unitIndex}][id]" required>
                <option value="">Selecciona una unidad</option>
                @foreach ($units as $unit)
                    <option value="{{ $unit->id }}">{{ $unit->name }} ({{ $unit->description }})</option>
                @endforeach
            </select>
            <input type="number" name="units[${unitIndex}][price]" placeholder="Precio" required>
            <input type="number" name="units[${unitIndex}][stock]" placeholder="Stock" required>
            <button type="button" class="btn btn-danger" onclick="removeUnit(this)">Eliminar</button>
        `;
        unitsContainer.appendChild(unitDiv);
        unitIndex++;
    }

    function removeUnit(button) {
        button.parentElement.remove();
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Cargar datos en el modal cuando se hace clic en "Actualizar"
        const nameInput = document.getElementById('name');
        const descriptionInput = document.getElementById('description');
        const categoryInput = document.getElementById('category_id');
        const imageInput = document.getElementById('image');

        const modalName = document.getElementById('modalName');
        const modalDescription = document.getElementById('modalDescription');
        const modalCategory = document.getElementById('modalCategory');
        const modalImage = document.getElementById('modalImage');

        document.querySelector('[data-target="#confirmModal"]').addEventListener('click', function() {
            modalName.textContent = nameInput.value;
            modalDescription.textContent = descriptionInput.value;
            modalCategory.textContent = categoryInput.options[categoryInput.selectedIndex].text;
            modalImage.textContent = imageInput.files.length ? imageInput.files[0].name : 'No seleccionada';
        });

        // Enviar el formulario al confirmar
        document.getElementById('confirmButton').addEventListener('click', function() {
            document.getElementById('productForm').submit();
        });
    });
</script>
@endsection
