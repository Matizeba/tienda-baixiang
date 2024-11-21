@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Reporte: Productos más vendidos</h1>

        <!-- Formulario de Filtro de Fechas -->
        <form action="{{ route('reports.topSellingProduct') }}" method="GET" class="mb-4">
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
<form action="{{ route('reports.generatePdf') }}" method="GET" class="d-inline">
    <input type="hidden" name="start_date" value="{{ $startDate }}">
    <input type="hidden" name="end_date" value="{{ $endDate }}">
    <button type="submit" class="btn btn-success">Generar PDF</button>
</form>

        <p>Desde: {{ $startDate }} | Hasta: {{ $endDate }}</p>

        @if($topProducts->isEmpty())
            <p>No se encontraron productos vendidos en el rango de fechas.</p>
        @else
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Unidad</th>
                        <th>Descripción</th>
                        <th>Cantidad Vendida</th>
                        <th>Total Recaudado</th> <!-- Nueva columna para el total recaudado -->
                    </tr>
                </thead>
                <tbody>
                    @foreach($topProducts as $product)
                        <tr>
                            <td>{{ $product->product->name }}</td>
                            <td>{{ $product->unit->name }}</td>
                            <td>{{ $product->unit->description }}</td>
                            <td>{{ $product->total_sold }}</td>
                            <td>{{ number_format($product->total_revenue, 2) }}</td> <!-- Mostrar el total recaudado con 2 decimales -->
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
