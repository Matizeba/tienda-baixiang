@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <!-- Filtro por Búsqueda y Categoría -->
        <div class="col-md-12 mb-3">
            <form action="{{ route('products.view') }}" method="GET">
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <div class="input-group">
                            <input type="text" name="search_term" id="search_term" class="form-control" placeholder="Buscar producto" value="{{ request('search_term', $searchTerm) }}">
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <div class="input-group">
                            <select name="category_id" id="category_id" class="form-control">
                                <option value="">Todas las Categorías</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id', $categoryId) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <div class="input-group">
                            <select name="status" id="status" class="form-control">
                                <option value="">Todos los Estados</option>
                                <option value="1" {{ request('status', $status) == '1' ? 'selected' : '' }}>Habilitados</option>
                                <option value="0" {{ request('status', $status) == '0' ? 'selected' : '' }}>Deshabilitados</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 mb-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter"></i> Aplicar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Listado de Productos en Tarjetas -->
    <div class="row">
        @forelse ($products as $product)
            <div class="col-md-4 mb-4">
                <div class="card h-100 {{ $product->status == 0 ? 'border-danger' : '' }}">
                    <!-- Imagen del producto -->
                    @if ($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                    @else
                        <img src="https://via.placeholder.com/150" class="card-img-top" alt="Imagen no disponible">
                    @endif

                    <!-- Información del producto -->
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">
                            <strong>Cantidad:</strong> {{ $product->quantity }}
                        </p>
                    </div>

                    <!-- Precio y botón de detalles -->
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <span class="text-muted">{{ $product->price }} Bs</span>
                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#productModal" data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-description="{{ $product->description }}" data-price="{{ $product->price }}" data-quantity="{{ $product->quantity }}" data-category="{{ $product->category->name }}" data-image="{{ asset('storage/' . $product->image) }}">
                            Ver Detalles
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p>No hay productos disponibles.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Modal de Detalles del Producto -->
<div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Detalles del Producto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <img id="modalImage" src="" class="img-fluid" alt="Imagen del Producto">
                </div>
                <p><strong>Nombre:</strong> <span id="modalName"></span></p>
                <p><strong>Descripción:</strong> <span id="modalDescription"></span></p>
                <p><strong>Cantidad:</strong> <span id="modalQuantity"></span></p>
                <p><strong>Precio:</strong> <span id="modalPrice"></span> Bs</p>
                <p><strong>Categoría:</strong> <span id="modalCategory"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#productModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id');
            var name = button.data('name');
            var description = button.data('description');
            var price = button.data('price');
            var quantity = button.data('quantity');
            var category = button.data('category');
            var image = button.data('image');

            var modal = $(this);
            modal.find('.modal-title').text('Detalles del Producto');
            modal.find('#modalImage').attr('src', image);
            modal.find('#modalName').text(name);
            modal.find('#modalDescription').text(description);
            modal.find('#modalPrice').text(price);
            modal.find('#modalQuantity').text(quantity);
            modal.find('#modalCategory').text(category);
        });
    });
</script>
@endsection
