@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Total de Ventas por Mes - Año {{ $year }}</h1>

        <!-- Selector de Año -->
        <form method="GET" action="{{ route('reports.sales_by_month') }}">
            <div class="form-group">
                <label for="year">Seleccionar Año:</label>
                <select name="year" id="year" class="form-control" onchange="this.form.submit()">
                    @foreach (range(2020, date('Y')) as $yearOption)
                        <option value="{{ $yearOption }}" {{ $yearOption == $year ? 'selected' : '' }}>
                            {{ $yearOption }}
                        </option>
                    @endforeach
                </select>
            </div>
            <a href="{{ route('reports.exportExcel', ['year' => $year]) }}" class="btn btn-success">
    Exportar Reporte a Excel
</a>
        </form>

        <!-- Gráfico de Ventas -->
        <canvas id="salesChart" width="400" height="200"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var ctx = document.getElementById('salesChart').getContext('2d');

        var salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                datasets: [{
                    label: 'Ventas Completadas',
                    data: @json($completedSalesData),
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: true
                }, {
                    label: 'Ventas Pendientes',
                    data: @json($pendingSalesData),
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    fill: true
                }]
            },
            options: {
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Meses'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Total Ventas'
                        }
                    }
                }
            }
        });
    </script>
@endsection
