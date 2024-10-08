@extends('layouts.app')

@section('breadcrumbs')
<h1 class="text-white">/ Clientes</h1>
@endsection

@section('content')
@php
    use App\Models\User;
@endphp

<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3 text-danger"><i class="fas fa-users"></i> Lista de Clientes</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('clients.export') }}" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Exportar
            </a>

            <a href="{{ route('clients.create') }}" class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Registrar Nuevo Cliente
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header card-header-custom">
            <i class="fas fa-users"></i> Clientes
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th scope="col"><i class="fas fa-hashtag"></i> Nro.</th>
                            <th scope="col"><i class="fas fa-user"></i> Nombre</th>
                            <th scope="col"><i class="fas fa-envelope"></i> Correo Electrónico</th>
                            <th scope="col"><i class="fas fa-user-tag"></i> Rol</th>
                            @if(Auth::user()->role == 1)
                            <th scope="col"><i class="fas fa-cogs"></i> Estado</th>
                            <th scope="col"><i class="fas fa-id-badge"></i> ID Usuario</th>
                            <th scope="col"><i class="fas fa-cogs"></i> Acciones</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if ($user->role == 1)
                                        <i class="fas fa-user-shield"></i> Administrador
                                    @elseif ($user->role == 2)
                                        <i class="fas fa-user-tie"></i> Vendedor
                                    @elseif ($user->role == 3)
                                        <i class="fas fa-user"></i> Cliente
                                    @endif
                                </td>
                                @if(Auth::user()->role == 1)
                                <td>
                                    <span class="badge {{ $user->status == 1 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $user->status == 1 ? 'Habilitado' : 'Deshabilitado' }}
                                    </span>
                                </td>

                                <td>
                                    {{ optional(User::find($user->userid))->name }}
                                </td>
                                <td>
                                    <a href="{{ route('clients.edit', $user->id) }}" class="btn btn-secondary">
                                        <i class="fas fa-edit"></i> 
                                    </a>
                                    <button type="button" class="btn {{ $user->status ? 'btn-danger' : 'btn-success' }}" data-bs-toggle="modal" data-bs-target="#toggleStatusModal" data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}" data-user-status="{{ $user->status }}">
                                        <i class="fas {{ $user->status ? 'fa-toggle-off' : 'fa-toggle-on' }}"></i> {{ $user->status ? '' : '' }}
                                    </button>
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
                ¿Estás seguro de que deseas <strong id="toggleStatusAction"></strong> al usuario <strong id="userName"></strong>? Esta acción cambiará el estado del usuario.
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
            var userId = button.getAttribute('data-user-id'); 
            var userName = button.getAttribute('data-user-name'); 
            var userStatus = button.getAttribute('data-user-status'); 
            var form = toggleStatusModal.querySelector('#toggleStatusForm');
            form.action = '{{ url('/clients') }}/' + userId + '/toggle-status';

            var actionText = userStatus == 1 ? 'deshabilitar' : 'habilitar';
            var toggleStatusActionElement = document.getElementById('toggleStatusAction');
            toggleStatusActionElement.textContent = actionText;

            var userNameElement = document.getElementById('userName');
            userNameElement.textContent = userName;
        });
    });
</script>
@endpush
@endsection
