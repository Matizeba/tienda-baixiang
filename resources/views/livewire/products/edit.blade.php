@extends('layouts.app')

@section('breadcrumbs')
    <a href="{{ route('products.index') }}">Productos</a> / <a href="#">Editar</a>
@endsection

@section('content')
<div class="container">
    
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3">Editar Producto</h1>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Volver</a>
    </div>
    
    <div class="card">
        <div class="card-header">
            Formulario de Edición de Productos
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

            <form action="{{ route('products.update', $product->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $product->nombre) }}" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="email">Descripción</label>
                    <input type="text" name="descripcion" id="descripcion" value="{{ old('descripcion', $product->descripcion) }}" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="cantidad">Cantidad</label>
                    <input type="number" name="cantidad" id="cantidad" value="{{ old('cantidad', $product->cantidad) }}" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="precio">Precio</label>
                    <input type="number" name="precio" id="precio" value="{{ old('precio', $product->precio) }}" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="categoria">Categoria</label>
                    <input type="text" name="categoria" id="categoria" value="{{ old('categoria', $product->categoria) }}" class="form-control" required>
                </div>


                

                <button type="submit" class="btn btn-success mt-4">Actualizar</button>
            </form>
        </div>
    </div>
</div>
@endsection
