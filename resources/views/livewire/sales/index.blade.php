<!-- resources/views/sales/index.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Ventas</h1>

    <!-- Tabla de Ventas -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Vendedor</th>
                <th>Cliente</th>
                <th>Monto Total</th>
                <th>Estado</th>
                <th>Fecha de Creación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $sale)
                <tr>
                    <td>{{ $sale->id }}</td>
                    <td>{{ $sale->user ? $sale->user->name : 'Desconocido' }}</td>
                    <td>{{ $sale->customer ? $sale->customer->name : 'Desconocido' }}</td>
                    <td>{{ $sale->total_amount }} Bs</td>
                    <td>
                        @if ($sale->status == 'completed')
                            <span class="badge bg-success">Completada</span>
                        @elseif ($sale->status == 'pending')
                            <span class="badge bg-warning">Pendiente</span>
                        @else
                            <span class="badge bg-danger">Cancelada</span>
                        @endif
                    </td>
                    <td>{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-info btn-sm">
                            Ver Detalles
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
