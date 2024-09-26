@extends('layouts.app')

@section('breadcrumbs')
<h1 class="text-white">/ Compras</h1>
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3 text-danger"><i class="fas fa-shopping-bag"></i> Lista de Compras</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('purchases.view') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Registrar Nueva Compra
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header card-header-custom">
            <i class="fas fa-shopping-bag"></i> Compras
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th scope="col"><i class="fas fa-hashtag"></i> ID</th>
                            <th scope="col"><i class="fas fa-user"></i> Comprador</th>
                            <th scope="col"><i class="fas fa-user"></i> Proveedor</th>
                            <th scope="col"><i class="fas fa-money-bill"></i> Monto Total</th>
                            <th scope="col"><i class="fas fa-info-circle"></i> Estado</th>
                            <th scope="col"><i class="fas fa-calendar-alt"></i> Fecha de Creación</th>
                            <th scope="col"><i class="fas fa-cogs"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchases as $purchase)
                            <tr>
                                <th scope="row">{{ $purchase->id }}</th>
                                <td>{{ $purchase->user ? $purchase->user->name : 'Desconocido' }}</td>
                                <td>{{ $purchase->supplier ? $purchase->supplier->name : 'Desconocido' }}</td>
                                <td>{{ $purchase->total_amount }} Bs</td>
                                <td>
                                    @if ($purchase->status == 'completed')
                                        <span class="badge bg-success">Completada</span>
                                    @elseif ($purchase->status == 'pending')
                                        <span class="badge bg-warning">Pendiente</span>
                                    @else
                                        <span class="badge bg-danger">Cancelada</span>
                                    @endif
                                </td>
                                <td>{{ $purchase->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('purchases.show', $purchase->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <a href="{{ route('purchases.edit', $purchase->id) }}" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <button type="button" class="btn btn-danger btn-sm" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deletePurchaseModal"
                                            data-purchase-id="{{ $purchase->id }}"
                                            data-purchase-amount="{{ $purchase->total_amount }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Agregar los enlaces de paginación -->
            <div class="d-flex justify-content-center">
            {{ $purchases->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal para Confirmar Eliminación -->
<div class="modal fade" id="deletePurchaseModal" tabindex="-1" aria-labelledby="deletePurchaseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title" id="deletePurchaseModalLabel"><i class="fas fa-exclamation-triangle"></i> Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar la compra de <strong id="purchaseAmount"></strong> Bs con ID <strong id="purchaseId"></strong>? Esta acción no puede deshacerse.
            </div>
            <div class="modal-footer">
                <form id="deletePurchaseForm" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var deletePurchaseModal = document.getElementById('deletePurchaseModal');
        deletePurchaseModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var purchaseId = button.getAttribute('data-purchase-id');
            var purchaseAmount = button.getAttribute('data-purchase-amount');

            var purchaseIdElement = document.getElementById('purchaseId');
            var purchaseAmountElement = document.getElementById('purchaseAmount');
            purchaseIdElement.textContent = purchaseId;
            purchaseAmountElement.textContent = purchaseAmount;

            var form = deletePurchaseModal.querySelector('#deletePurchaseForm');
            form.action = '/purchases/' + purchaseId;
        });
    });
</script>
@endpush
@endsection
