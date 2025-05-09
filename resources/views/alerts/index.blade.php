@extends('layouts.app')

@section('content')
    <div class="container">
        <!-- Título de la página -->
        <h1 class="my-4">Alertas No Vistas</h1>

        <!-- Alertas No Vistas -->
        @if($alerts->isEmpty())
            <div class="alert alert-info">No hay alertas pendientes.</div>
        @else
            <div class="row">
                @foreach($alerts as $alert)
                    <div class="col-md-4 mb-3">
                        <div class="card text-white bg-warning">
                            <div class="card-body">
                                <h5 class="card-title">{{ $alert->title }}</h5>
                                <p class="card-text">{{ $alert->message }}</p>
                                <small class="text-muted d-block">{{ $alert->created_at->diffForHumans() }}</small>
                                <!-- Formulario para marcar la alerta como leída -->
                                <form action="{{ route('alerts.markAsRead', $alert->id) }}" method="POST" class="mt-3">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-sm btn-light">Marcar como Leída</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Alertas Vistas -->
        <h1 class="my-4">Alertas Vistas</h1>

        @if($alertsView->isEmpty())
            <div class="alert alert-info">No hay alertas vistas.</div>
        @else
            <div class="row">
                @foreach($alertsView as $alert)
                    <div class="col-md-4 mb-3">
                        <div class="card text-white bg-success">
                            <div class="card-body">
                                <h5 class="card-title">{{ $alert->title }}</h5>
                                <p class="card-text">{{ $alert->message }}</p>
                                <small class="text-muted d-block">{{ $alert->updated_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Productos con Bajo Stock -->
        <h2 class="my-4">Productos con Bajo Stock</h2>

        @if($lowStockProducts->isEmpty())
            <div class="alert alert-info">No hay productos con bajo stock.</div>
        @else
            <div class="row">
                @foreach($lowStockProducts as $productUnit)
                    <div class="col-md-4 mb-3">
                        <div class="card border-warning">
                            <div class="card-body">
                                <h5 class="card-title">{{ $productUnit->product->name }} (Bajo Stock)</h5>
                                <p class="card-text">Unidad: {{ $productUnit->unit->name }}</p>
                                <p class="card-text">Descripción: {{ $productUnit->unit->description }}</p>
                                <p class="card-text">Cantidad: {{ $productUnit->stock }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
