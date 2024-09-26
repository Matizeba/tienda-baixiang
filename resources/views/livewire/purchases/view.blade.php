@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Listado de productos -->
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
                        <p class="card-text"><strong>Descripción:</strong> {{ $product->description }}</p>
                        <p class="card-text"><strong>Categoría:</strong> {{ $product->category->name }}</p>
                    </div>

                    <!-- Precio y botón de detalles -->
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <span class="text-muted">{{ $product->price }} Bs</span>
                        <button class="btn btn-primary btn-sm" 
        data-toggle="modal" 
        data-target="#productModal" 
        data-id="{{ $product->id }}" 
        data-name="{{ $product->name }}" 
        data-description="{{ $product->description }}" 
        data-category="{{ $product->category->name }}" 
        data-units="{{ json_encode($product->units) }}" 
        data-image="{{ asset('storage/' . $product->image) }}">
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

    <!-- Modal de detalles del producto -->
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
                    <p><strong>Categoría:</strong> <span id="modalCategory"></span></p>

                    <div id="modalUnits">
                        <p><strong>Unidades Disponibles:</strong></p>
                        <ul id="unitList"></ul>
                    </div>

                    <button type="button" class="btn btn-success" id="addToCartButton">Añadir al Carrito</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para confirmar cantidad -->
    <div class="modal fade" id="confirmQuantityModal" tabindex="-1" aria-labelledby="confirmQuantityModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmQuantityModalLabel">Confirmar Cantidad</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Producto: <span id="confirmProductName"></span></p>
                    <p>Descripción: <span id="confirmDescription"></span></p>
                    <p>Precio: <span id="confirmPrice"></span></p>
                    <label for="confirmQuantityInput" class="form-label">Cantidad:</label>
                    <input type="number" id="confirmQuantityInput" class="form-control" min="1" value="1">
                    <p>Total: <span id="confirmTotal"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="addToCartConfirmButton">Añadir al Carrito</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal del carrito -->
    <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
        <div class="modal-dialog modalSale">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cartModalLabel">Carrito de Compras</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table id="cartTable" class="table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Descripción</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Total</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Aquí se mostrará el contenido del carrito -->
                        </tbody>
                    </table>
                    <p><strong>Total General:</strong> <span id="cartTotal">0</span> Bs</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-success" id="checkoutButton">Confirmar Compra</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let cart = [];
    let cartCountElement = document.getElementById('cartCount');
    let cartTableBody = document.querySelector('#cartTable tbody');
    let cartTotalElement = document.getElementById('cartTotal');

    // Manejo del modal de detalles del producto
    $('#productModal').on('show.bs.modal', function (event) {
    let button = $(event.relatedTarget); // Botón que activó el modal
    let productId = button.data('id');
    let productName = button.data('name');
    let productDescription = button.data('description');
    let productCategory = button.data('category');
    let productImage = button.data('image');
    let productUnits = button.data('units'); // Unidades del producto

    let modal = $(this);
    modal.find('#modalName').text(productName);
    modal.find('#modalDescription').text(productDescription);
    modal.find('#modalCategory').text(productCategory);
    modal.find('#modalImage').attr('src', productImage);

    // Añadir las unidades al modal
    let unitList = modal.find('#unitList');
    unitList.empty(); // Limpiar las unidades anteriores
    for (let i = 0; i < productUnits.length; i++) {
        let unit = productUnits[i];
        unitList.append(`<li>${unit.name} - ${unit.quantity} disponibles</li>`);
    }
});


        let addToCartButton = modal.find('#addToCartButton');
        addToCartButton.off('click');
        addToCartButton.on('click', function () {
            $('#confirmQuantityModal').modal('show');
            $('#confirmProductName').text(productName);
            $('#confirmDescription').text(productDescription);
            $('#confirmPrice').text(productPrice);
            $('#confirmQuantityInput').attr('max', productQuantity);
            calculateTotal();
        });
    });

    document.getElementById('confirmQuantityInput').addEventListener('input', function () {
        calculateTotal();
    });

    document.getElementById('addToCartConfirmButton').addEventListener('click', function () {
        let productName = document.getElementById('confirmProductName').textContent;
        let productDescription = document.getElementById('confirmDescription').textContent;
        let productPrice = parseFloat(document.getElementById('confirmPrice').textContent);
        let quantity = parseInt(document.getElementById('confirmQuantityInput').value);

        let total = productPrice * quantity;
        
        let product = {
            name: productName,
            description: productDescription,
            price: productPrice,
            quantity: quantity,
            total: total
        };

        addToCart(product);
$('#confirmQuantityModal').modal('hide');  // Ocultamos el modal de confirmación de cantidad después de añadir el producto al carrito.
});

// Función para calcular el total en el modal de confirmación
function calculateTotal() {
    let price = parseFloat(document.getElementById('confirmPrice').textContent);  // Obtenemos el precio del producto.
    let quantity = parseInt(document.getElementById('confirmQuantityInput').value);  // Obtenemos la cantidad seleccionada.
    let total = price * quantity;  // Calculamos el total.
    document.getElementById('confirmTotal').textContent = total.toFixed(2);  // Mostramos el total en el modal.
}

// Función para añadir un producto al carrito
function addToCart(product) {
    let exists = false;

    // Verificamos si el producto ya está en el carrito
    cart.forEach(function(item) {
        if (item.name === product.name && item.description === product.description) {
            // Si el producto ya está en el carrito, solo sumamos la cantidad
            item.quantity += product.quantity;
            item.total += product.total;
            exists = true;
        }
    });

    // Si el producto no existe en el carrito, lo añadimos
    if (!exists) {
        cart.push(product);
    }

    // Actualizamos la vista del carrito
    renderCart();
}

// Función para mostrar el contenido del carrito
function renderCart() {
    cartTableBody.innerHTML = '';  // Limpiamos el contenido anterior del carrito
    let totalGeneral = 0;

    // Iteramos sobre los productos del carrito para mostrarlos en la tabla
    cart.forEach(function(product, index) {
        let row = document.createElement('tr');
        row.innerHTML = `
            <td>${product.name}</td>
            <td>${product.description}</td>
            <td>${product.price.toFixed(2)} Bs</td>
            <td>${product.quantity}</td>
            <td>${product.total.toFixed(2)} Bs</td>
            <td><button class="btn btn-danger btn-sm" onclick="removeFromCart(${index})">Eliminar</button></td>
        `;
        cartTableBody.appendChild(row);  // Añadimos la fila del producto a la tabla

        // Calculamos el total general de todos los productos
        totalGeneral += product.total;
    });

    // Actualizamos el total general y la cantidad de productos en el carrito
    cartTotalElement.textContent = totalGeneral.toFixed(2);
    cartCountElement.textContent = cart.length;
}

// Función para eliminar un producto del carrito
window.removeFromCart = function(index) {
    cart.splice(index, 1);  // Eliminamos el producto del array del carrito según el índice
    renderCart();  // Volvemos a renderizar el carrito después de eliminar el producto
};
<script>
@endsection
