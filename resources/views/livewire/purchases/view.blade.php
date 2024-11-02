@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Crear Venta</h2>

    <div class="row mb-3">
        <div class="col-md-6 text-end">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cartModal">
                <i class="fas fa-shopping-cart"></i> Carrito <span class="badge bg-secondary" id="cartCount">0</span>
            </button>
        </div>
    </div>

    <div class="row">
        @foreach($products as $product)
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">{{ $product->description }}</p>
                        <button class="btn btn-primary" onclick="openProductModal({{ $product->id }})">Ver detalles</button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal para mostrar los detalles del producto y la tabla de unidades -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel">Detalles del Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5 id="modalProductName"></h5>
                    <p id="modalProductDescription"></p>

                    <table id="unitsTable" class="table">
                        <thead>
                            <tr>
                                <th>Unidad</th>
                                <th>Descripción</th>
                                <th>Precio</th>
                                <th>Cantidad Disponible</th>
                                <th>Cantidad a Añadir</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Aquí se llenarán las unidades según el producto seleccionado -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
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
                                <th>Unidad</th>
                                <th>Descripción</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Total</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Aquí se agregarán los artículos seleccionados -->
                        </tbody>
                    </table>
                    <h5>Total General: <span id="grandTotal">0.00</span></h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="confirmSaleButton">Confirmar Venta</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulario oculto para gestionar el carrito -->
    <form id="cartForm" action="{{ route('purchases.store') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="products[]" id="productsInput">
    </form>

</div>

<script>
    var products = @json($products);
    var cart = []; // Array para almacenar los productos del carrito

    // Función para abrir el modal de producto y llenar sus detalles
    function openProductModal(productId) {
        var selectedProduct = products.find(product => product.id === productId);
        if (!selectedProduct) {
            alert("Producto no encontrado");
            return;
        }

        document.getElementById('modalProductName').innerText = selectedProduct.name;
        document.getElementById('modalProductDescription').innerText = selectedProduct.description;

        // Llenar la tabla de unidades
        var unitsTableBody = document.getElementById('unitsTable').getElementsByTagName('tbody')[0];
        unitsTableBody.innerHTML = ''; // Limpiar las filas anteriores

        if (selectedProduct.product_units && selectedProduct.product_units.length > 0) {
            selectedProduct.product_units.forEach(function(unit) {
                if (unit.stock > 0) {
                    var row = unitsTableBody.insertRow();
                    row.insertCell(0).innerText = unit.unit.name || 'N/A';
                    row.insertCell(1).innerText = unit.unit.description || 'N/A';
                    row.insertCell(2).innerText = unit.price || 'N/A';
                    row.insertCell(3).innerText = unit.stock || 'N/A';

                    // Añadir un campo de entrada para la cantidad
                    var quantityInput = document.createElement('input');
                    quantityInput.type = 'number';
                    quantityInput.className = 'form-control';
                    quantityInput.min = 0;
                    quantityInput.max = unit.stock;
                    quantityInput.value = 0;
                    quantityInput.id = 'quantity-' + unit.unit.id;

                    row.insertCell(4).appendChild(quantityInput);

                    var addButton = document.createElement('button');
                    addButton.innerText = 'Añadir';
                    addButton.className = 'btn btn-success btn-sm';
                    addButton.disabled = true;
                    row.insertCell(5).appendChild(addButton);

                    quantityInput.addEventListener('input', function() {
                        var quantity = parseInt(quantityInput.value);
                        addButton.disabled = quantity <= 0;
                    });

                    addButton.onclick = function() {
                        var quantity = parseInt(quantityInput.value);
                        if (quantity > unit.stock) {
                            alert("No se puede añadir más de " + unit.stock + " unidades.");
                            return;
                        }
                        addToCart(selectedProduct.id, unit.unit.id, unit.price, quantity, selectedProduct.name, unit.unit.description, unit.stock);
                    };
                }
            });
        } else {
            var row = unitsTableBody.insertRow();
            var cell = row.insertCell(0);
            cell.colSpan = 6;
            cell.innerText = "No hay unidades disponibles";
        }

        $('#productModal').modal('show');
    }

    function addToCart(productId, unitId, price, quantity, productName, description, unitStock) {
    var existingItem = cart.find(item => item.productId === productId && item.unitId === unitId);

    if (existingItem) {
        // Si el producto ya está en el carrito, incrementa la cantidad sin exceder el stock
        var previousQuantity = existingItem.quantity;
        var newQuantity = existingItem.quantity + quantity;

        if (newQuantity <= unitStock) {
            existingItem.quantity = newQuantity;
            alert("Cantidad de " + productName + " incrementada de " + previousQuantity + " a " + newQuantity + ".");
        } else {
            alert("No se puede añadir más de " + (unitStock - existingItem.quantity) + " unidades.");
            return;
        }
    } else {
        // Agregar nuevo producto y unidad al carrito si no existe
        var item = { 
            productId: productId, 
            unitId: unitId, 
            price: price, 
            quantity: quantity, 
            productName: productName, 
            description: description 
        };
        cart.push(item);

        // Mostrar alerta solo para productos nuevos
        alert(quantity + " unidad(es) de " + productName + " han sido añadidas al carrito.");
    }

    updateCartTable();
    updateCartCount();
}


    function updateCartTable() {
        var cartTableBody = document.getElementById('cartTable').getElementsByTagName('tbody')[0];
        cartTableBody.innerHTML = '';
        var grandTotal = 0;

        cart.forEach(function(item) {
            var row = cartTableBody.insertRow();
            row.insertCell(0).innerText = item.productName;
            row.insertCell(1).innerText = item.unitName; 
            row.insertCell(2).innerText = item.description;
            row.insertCell(3).innerText = item.price.toFixed(2);
            row.insertCell(4).innerText = item.quantity;
            var total = item.price * item.quantity;
            row.insertCell(5).innerText = total.toFixed(2);

            // Crear botón para eliminar del carrito
            var deleteButton = document.createElement('button');
            deleteButton.innerText = 'Eliminar';
            deleteButton.className = 'btn btn-danger btn-sm';
            deleteButton.onclick = function() {
                removeFromCart(item.productId, item.unitId);
            };
            row.insertCell(6).appendChild(deleteButton);

            grandTotal += total;
        });

        document.getElementById('grandTotal').innerText = grandTotal.toFixed(2);
    }

    function removeFromCart(productId, unitId) {
        cart = cart.filter(function(item) {
            return !(item.productId === productId && item.unitId === unitId);
        });
        updateCartTable();
        updateCartCount();
    }

    function updateCartCount() {
        document.getElementById('cartCount').innerText = cart.length;
    }

    // Lógica para confirmar la venta
    document.getElementById('confirmSaleButton').addEventListener('click', function() {
        var productsArray = cart.map(item => {
            return {
                id: item.productId,
                unitId: item.unitId,
                price: item.price,
                quantity: item.quantity
            };
        });
        document.getElementById('productsInput').value = JSON.stringify(productsArray);
        document.getElementById('cartForm').submit();
    });
</script>

@endsection
