@extends('layouts.app')

@section('content')
<div class="container-fluid">
@if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    @if (Auth::user()->role == 1 && Auth::user()->id << 1 && Auth::user()->passwordUpdate  || Auth::user()->role == 2 && Auth::user()->passwordUpdate)
    <div class="container-fluid mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient text-white text-center py-4 rounded-top">
                    <h3 class="mb-0">Actualizar Contraseña</h3>
                </div>
                <div class="card-body p-5">
                    <p class="text-muted mb-4 text-center">
                        Para proteger su cuenta, le pedimos que actualice su contraseña. Elija una nueva contraseña que sea segura y fácil de recordar.
                    </p>
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <div class="form-group mb-4">
                            <label for="current_password" class="form-label">Contraseña Actual</label>
                            <input type="password" id="current_password" name="current_password" class="form-control @error('current_password') is-invalid @enderror rounded-pill" required>
                            @error('current_password')
                                <div class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="password" class="form-label">Nueva Contraseña</label>
                            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror rounded-pill" required>
                            @error('password')
                                <div class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control rounded-pill" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill">
                                <i class="fas fa-sync-alt"></i> Cambiar Contraseña
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer bg-light text-center py-3 rounded-bottom">
                    <small class="text-muted">Asegúrese de no compartir su nueva contraseña con nadie.</small>
                </div>
            </div>
        </div>
    </div>
</div>

        @else
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="card text-white bg-info mb-4">
                    <div class="card-body">
                        <div class="card-body-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="mr-5">45 Nuevos Usuarios!</div>
                    </div>
                    <a class="card-footer text-white clearfix small z-1" href="{{ route('users.index') }}">
                        <span class="float-left">Ver Detalles</span>
                        <span class="float-right">
                            <i class="fas fa-angle-right"></i>
                        </span>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card text-white bg-success mb-4">
                    <div class="card-body">
                        <div class="card-body-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="mr-5">15 Nuevas Ventas!</div>
                    </div>
                    <a class="card-footer text-white clearfix small z-1" href="#">
                        <span class="float-left">Ver Detalles</span>
                        <span class="float-right">
                            <i class="fas fa-angle-right"></i>
                        </span>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card text-white bg-warning mb-4">
                    <div class="card-body">
                        <div class="card-body-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="mr-5">3 Reportes Pendientes!</div>
                    </div>
                    <a class="card-footer text-white clearfix small z-1" href="#">
                        <span class="float-left">Ver Detalles</span>
                        <span class="float-right">
                            <i class="fas fa-angle-right"></i>
                        </span>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card text-white bg-danger mb-4">
                    <div class="card-body">
                        <div class="card-body-icon">
                            <i class="fas fa-bell"></i>
                        </div>
                        <div class="mr-5">Alerta de Seguridad!</div>
                    </div>
                    <a class="card-footer text-white clearfix small z-1" href="#">
                        <span class="float-left">Ver Detalles</span>
                        <span class="float-right">
                            <i class="fas fa-angle-right"></i>
                        </span>
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-chart-bar"></i>
                        Gráfico de Ventas
                    </div>
                    <div class="card-body">
                        <canvas id="salesChart" width="100%" height="40"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-chart-pie"></i>
                        Gráfico de Usuarios
                    </div>
                    <div class="card-body">
                        <canvas id="usersChart" width="100%" height="40"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table"></i>
                        Últimas Ventas
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Cliente</th>
                                        <th>Producto</th>
                                        <th>Fecha</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Juan Pérez</td>
                                        <td>Producto A</td>
                                        <td>2024-08-12</td>
                                        <td>$120</td>
                                    </tr>
                                    <!-- Más filas -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-envelope"></i>
                        Últimos Mensajes
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="media">
                                <i class="fas fa-user mr-3"></i>
                                <div class="media-body">
                                    <h5 class="mt-0 mb-1">Juan Pérez</h5>
                                    Hola, me gustaría saber más sobre el producto...
                                </div>
                            </li>
                            <!-- Más mensajes -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctxSales = document.getElementById('salesChart').getContext('2d');
    var salesChart = new Chart(ctxSales, {
        type: 'bar',
        data: {
            labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio'],
            datasets: [{
                label: 'Ventas',
                data: [12, 19, 3, 5, 2, 3],
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    var ctxUsers = document.getElementById('usersChart').getContext('2d');
    var usersChart = new Chart(ctxUsers, {
        type: 'pie',
        data: {
            labels: ['Administradores', 'Vendedores', 'Clientes'],
            datasets: [{
                label: 'Usuarios',
                data: [3, 5, 2],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true
        }
    });
</script>
@endpush

