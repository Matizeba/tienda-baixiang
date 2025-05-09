@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Reporte: Vendedores con más ventas completadas</h1>

        <!-- Formulario de Filtro de Fechas -->
        <form action="{{ route('reports.salesReport') }}" method="GET" class="mb-4">
            <div class="form-row">
                <div class="col-md-5">
                    <label for="start_date">Desde</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $startDate }}">
                </div>
                <div class="col-md-5">
                    <label for="end_date">Hasta</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $endDate }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                </div>
            </div>
        </form>

        <!-- Botón para generar PDF -->
        <form action="{{ route('reports.generateSalesPdf') }}" method="GET" class="d-inline">
            <input type="hidden" name="start_date" value="{{ $startDate }}">
            <input type="hidden" name="end_date" value="{{ $endDate }}">
            <button type="submit" class="btn btn-success">Generar PDF</button>
        </form>

        <p>Desde: {{ $startDate }} | Hasta: {{ $endDate }}</p>

        @if($sales->isEmpty())
            <p>No se encontraron ventas completadas en el rango de fechas.</p>
        @else
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Vendedor</th>
                        <th>Total Ventas</th>
                        <th>Ganancia Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sales as $sale)
                        <tr>
                            <td>{{ $sale->user->name }} {{ $sale->user->first_surname }} {{ $sale->user->second_surname }}</td>
                            <td>{{ $sale->total_transactions }}</td>
                            <td>${{ number_format($sale->total_revenue, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
