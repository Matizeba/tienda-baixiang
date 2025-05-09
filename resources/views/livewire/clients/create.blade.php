@extends('layouts.app')

@section('breadcrumbs')
 <a href="{{ route('clients.index') }}" class="text-white"> Cliente</a> <h1 class="text-white" > / Registrar</h1>
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3">Registrar Nuevo Cliente</h1>
        <a href="{{ route('clients.index') }}" class="btn btn-secondary">Volver</a>
    </div>

    <div class="card">
        <div class="card-header">
            Formulario de Registro de Cliente
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

            <form action="{{ route('clients.storeClient') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                </div>

                <div class="form-group">
                    <label for="first_surname">Primer Apellido</label>
                    <input type="text" name="first_surname" id="first_surname" class="form-control" value="{{ old('first_surname') }}" required>
                </div>

                <div class="form-group">
                    <label for="second_surname">Segundo Apellido</label>
                    <input type="text" name="second_surname" id="second_surname" class="form-control" value="{{ old('second_surname') }}">
                </div>

                <div class="form-group">
                    <label for="ci">Cédula de Identidad</label>
                    <input type="text" name="ci" id="ci" class="form-control" value="{{ old('ci') }}" required>
                </div>

                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                </div>

                <div class="form-group">
                    <label for="phone">Teléfono</label>
                    <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}">
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Registrar</button>
            </form>
        </div>
    </div>
</div>
@endsection
