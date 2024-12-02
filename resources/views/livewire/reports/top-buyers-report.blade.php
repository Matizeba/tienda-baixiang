@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Reporte: Compradores con más compras completadas</h1>

        <!-- Formulario de Filtro de Fechas -->
        <form action="{{ route('reports.top_buyers_report') }}" method="GET" class="mb-4">
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
        <form action="{{ route('reports.top_buyers_report_pdf') }}" method="GET" class="d-inline">
            <input type="hidden" name="start_date" value="{{ $startDate }}">
            <input type="hidden" name="end_date" value="{{ $endDate }}">
            <button type="submit" class="btn btn-success">Generar PDF</button>
        </form>

        <p>Desde: {{ $startDate }} | Hasta: {{ $endDate }}</p>

        @if($buyers->isEmpty())
            <p>No se encontraron compras completadas en el rango de fechas.</p>
        @else
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Comprador</th>
                        <th>Total Compras</th>
                        <th>Total Productos</th>
                        <th>Total Gastado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($buyers as $index => $buyer)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $buyer->customer->name ?? 'Cliente no encontrado' }}</td>
                            <td>{{ $buyer->total_purchases }}</td>
                            <td>
                                @php
                                    $totalProducts = $buyer->saleDetails->sum('quantity');
                                @endphp
                                {{ $totalProducts }}
                            </td>
                            <td>${{ number_format($buyer->total_spent, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
