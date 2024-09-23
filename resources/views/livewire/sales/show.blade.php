@extends('layouts.app')

@section('breadcrumbs')
<h1 class="text-white">/ Detalles de la Venta</h1>
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3 text-primary"><i class="fas fa-receipt"></i> Detalles de la Venta #{{ $sale->id }}</h1>
        <div class="d-flex align-items-center">
            <a href="{{ route('sales.index') }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left"></i> Volver a la Lista de Ventas
            </a>
            <a href="{{ route('sales.receipt', $sale->id) }}" class="btn btn-primary me-2" id="generate-pdf">
                <i class="fas fa-file-pdf"></i> Generar PDF
            </a>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#statusModal">
                Cambiar Estado
            </button>
        </div>
    </div>

    <div class="card">
        <div class="card-header card-header-custom">
            <i class="fas fa-receipt"></i> Información de la Venta
        </div>
        <div class="card-body">
            <p><strong>Vendedor:</strong> {{ $sale->user ? $sale->user->name : 'Desconocido' }}</p>
            <p><strong>Cliente:</strong> {{ $sale->customer ? $sale->customer->name : 'Desconocido' }}</p>
            <p><strong>Monto Total:</strong> {{ $sale->total_amount }} Bs</p>
            <p><strong>Estado:</strong> {{ ucfirst($sale->status) }}</p>
            <p><strong>Fecha de Creación:</strong> {{ $sale->updated_at->format('d/m/Y H:i') }}</p>

            <h3 class="mt-4">Detalles de los Productos</h3>
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th scope="col"><i class="fas fa-box"></i> Producto</th>
                            <th scope="col"><i class="fas fa-cube"></i> Unidad</th>
                            <th scope="col"><i class="fas fa-info-circle"></i> Descripción</th>
                            <th scope="col"><i class="fas fa-hashtag"></i> Cantidad</th>
                            <th scope="col"><i class="fas fa-dollar-sign"></i> Precio</th>
                            <th scope="col"><i class="fas fa-dollar-sign"></i> Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->details as $detail)
                            <tr>
                                <td>{{ $detail->product->name }}</td>
                                <td>{{ $detail->unit ? $detail->unit->name : 'Sin unidad' }}</td>
                                <td>{{ $detail->unit ? $detail->unit->description : 'Sin descripción' }}</td>
                                <td>{{ $detail->quantity }}</td>
                                <td>{{ $detail->price }} Bs</td>
                                <td>{{ $detail->total }} Bs</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">Cambiar Estado de la Venta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de continuar? Esta acción solo podrá ser modificada por un administrador.</p>
                <form id="statusForm" action="{{ route('sales.changeStatus', $sale->id) }}" method="POST">
                    @csrf
                    <select name="status" class="form-select" id="statusSelect">
                        <option value="pending" {{ $sale->status == 'pending' ? 'selected' : '' }}>Pendiente</option>
                        <option value="canceled" {{ $sale->status == 'canceled' ? 'selected' : '' }}>Cancelado</option>
                        <option value="completed" {{ $sale->status == 'completed' ? 'selected' : '' }}>Completado</option>
                    </select>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirmStatusChange">Confirmar</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.getElementById('confirmStatusChange').addEventListener('click', function() {
        const statusSelect = document.getElementById('statusSelect');
        const selectedStatus = statusSelect.value;

        // Si el estado es 'completed', se redirige a la generación del PDF
        if (selectedStatus === 'completed') {
            document.getElementById('statusForm').submit();
            setTimeout(() => {
                window.location.href = "{{ route('sales.receipt', $sale->id) }}";
            }, 100); // Esperar un momento para que el formulario se envíe
        } else {
            document.getElementById('statusForm').submit();
        }
    });
</script>
@endsection
