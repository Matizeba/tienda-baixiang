@extends('layouts.app')

@section('breadcrumbs')
     <h1 class="text-white" >Productos</h1>
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3">Lista de Productos</h1>
        <a href="{{ route('products.create') }}" class="btn btn-primary">Registrar Nuevo Producto</a>
    </div>

    <div class="card">
        <div class="card-header">
            Productos
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Descripción</th>
                        <th scope="col">Cantidad</th>
                        <th scope="col">Precio</th>
                        <th scope="col">Categoría</th>

                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <th scope="row">{{ $product->id }}</th>
                            <td>{{ $product->nombre }}</td>
                            <td>{{ $product->descripcion }}</td>
                            <td>{{ $product->cantidad }}</td>
                            <td>{{ $product->precio}} <span>Bs</span> </td>
                            <td>{{ $product->categoria }}</td>
                           
                            <td>
                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-secondary">Editar</a>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
