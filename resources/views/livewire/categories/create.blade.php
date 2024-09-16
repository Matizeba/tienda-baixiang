@extends('layouts.app')

@section('breadcrumbs')
    <a href="{{ route('categories.index') }}" class="text-white">/ Categorías</a> <h1 class="text-white"> / Crear Nueva Categoría</h1>
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3">Registrar Nueva Categoría</h1>
        <a href="{{ route('categories.index') }}" class="btn btn-secondary">Volver</a>
    </div>

    <div class="card">
        <div class="card-header">
            Formulario de Registro de Categoría
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

            <form id="categoryForm" action="{{ route('categories.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                </div>

                <div class="form-group">
                    <label for="description">Descripción</label>
                    <textarea name="description" id="description" class="form-control" rows="3">{{ old('description') }}</textarea>
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
                <p>Estás a punto de registrar una nueva categoría con los siguientes datos:</p>
                <ul>
                    <li><strong>Nombre:</strong> <span id="modalName"></span></li>
                    <li><strong>Descripción:</strong> <span id="modalDescription"></span></li>
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
    const form = document.getElementById('categoryForm');
    const nameInput = document.getElementById('name');
    const descriptionInput = document.getElementById('description');
    const modalName = document.getElementById('modalName');
    const modalDescription = document.getElementById('modalDescription');
    const confirmButton = document.getElementById('confirmButton');

    // Load data into the modal when the "Registrar" button is clicked
    document.querySelector('[data-target="#confirmModal"]').addEventListener('click', function() {
        modalName.textContent = nameInput.value || 'No name provided';
        modalDescription.textContent = descriptionInput.value || 'No description provided';
    });

    // Submit the form when the "Confirmar" button is clicked
    confirmButton.addEventListener('click', function() {
        form.submit();
    });
});

</script>
@endsection
