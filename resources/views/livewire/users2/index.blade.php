@extends('layouts.app')

@section('breadcrumbs')
  <h1 class="text-white" >Clientes</h1>  
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3">Lista de Clientes</h1>
        <a href="{{ route('users.createClient') }}" class="btn btn-primary">Registrar Nuevo Cliente</a>
    </div>

    <div class="card">
        <div class="card-header">
            Clientes
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Correo Electrónico</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $contador=1
                    @endphp
                    @foreach ($users as $user)
                        <tr>
                            <th scope="row">{{ $contador++ }}</th>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <a href="{{ route('users.editClient', $user->id) }}" class="btn btn-secondary">Editar</a>
                                <form action="{{ route('users.destroyClient', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $users->links() }} <!-- Paginación -->
        </div>
    </div>
</div>
@endsection
