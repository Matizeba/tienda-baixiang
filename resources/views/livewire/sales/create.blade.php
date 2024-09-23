@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Crear Venta</h2>

    <div class="row mb-3">
        <div class="col-md-6">
            <label for="customerSelect" class="form-label">Seleccionar Cliente</label>
            <select id="customerSelect" class="form-select" required>
                <option value="">Seleccione un cliente</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 text-end">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cartModal">
                <i class="fas fa-shopping-cart"></i> Carrito <span class="badge bg-secondary" id="cartCount">0</span>
            </button>
        </div>
    </div>

    <div class="mb-3">
        <label for="productSelect" class="form-label">Seleccionar Producto</label>
        <select id="productSelect" class="form-select" onchange="updateUnits()">
            <option value="">Seleccione un producto</option>
            @foreach($products as $product)
                <option value="{{ $product->id }}">{{ $product->name }}</option>
            @endforeach
        </select>
    </div>

    <table id="unitsTable" class="table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Unidad</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Cantidad Disponible</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <!-- Las unidades se llenarán aquí según el producto seleccionado -->
        </tbody>
    </table>

    <!-- Modal para confirmar la cantidad -->
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
                    <button type="button" class="btn btn-primary" id="addToCartButton">Añadir al Carrito</button>
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
    <form id="cartForm" action="{{ route('sales.store') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="customer_id" id="customer_id">
        <input type="hidden" name="products[]" id="productsInput">
    </form>

</div>

<script>
    var cart = []; // Array para almacenar los productos seleccionados

    function updateUnits() {
        var productId = document.getElementById('productSelect').value;
        var unitsTableBody = document.getElementById('unitsTable').getElementsByTagName('tbody')[0];
        unitsTableBody.innerHTML = ''; // Limpiar la tabla

        @foreach($products as $product)
            if (productId == "{{ $product->id }}") {
                @foreach($product->productUnits as $productUnit)
                    var row = unitsTableBody.insertRow();
                    row.insertCell(0).innerText = "{{ $productUnit->product->name }}";
                    row.insertCell(1).innerText = "{{ $productUnit->unit->name }}";
                    row.insertCell(2).innerText = "{{ $productUnit->unit->description }}";
                    row.insertCell(3).innerText = "{{ $productUnit->price }}";
                    row.insertCell(4).innerText = "{{ $productUnit->stock }}";

                    // Agregar botón para añadir al carrito
                    var addButton = document.createElement('button');
                    addButton.innerText = 'Añadir';
                    addButton.className = 'btn btn-success btn-sm';
                    addButton.onclick = (function(unitId, price, productId, productName, description, stock) {
                        return function() {
                            openConfirmModal(unitId, price, productId, productName, description, stock);
                        };
                    })("{{ $productUnit->unit->id }}", "{{ $productUnit->price }}", "{{ $product->id }}", "{{ $product->name }}", "{{ $productUnit->unit->description }}", "{{ $productUnit->stock }}");
                    var cell = row.insertCell(5);
                    cell.appendChild(addButton);
                @endforeach
            }
        @endforeach
    }

    function openConfirmModal(unitId, price, productId, productName, description, stock) {
        document.getElementById('confirmProductName').innerText = productName;
        document.getElementById('confirmDescription').innerText = description;
        document.getElementById('confirmPrice').innerText = price;

        var quantityInput = document.getElementById('confirmQuantityInput');
        quantityInput.value = 1; // Valor por defecto
        quantityInput.max = stock; // Establecer el máximo permitido
        updateTotal(quantityInput.value, price);

        // Manejar el cambio en el input para actualizar el total
        quantityInput.oninput = function() {
            var quantity = parseInt(this.value);
            if (quantity > stock) {
                this.value = stock; // No permitir más que el stock disponible
                alert("No se puede seleccionar más de " + stock + " unidades.");
            }
            updateTotal(this.value, price);
        };

        // Guardar el producto en el botón de añadir al carrito
        document.getElementById('addToCartButton').onclick = function() {
            var quantity = parseInt(quantityInput.value);
            if (quantity > stock) {
                alert("No se puede añadir más de " + stock + " unidades.");
                return;
            }
            addToCart(productId, unitId, price, quantity, productName, description);
            $('#confirmQuantityModal').modal('hide'); // Cerrar el modal
        };

        $('#confirmQuantityModal').modal('show'); // Mostrar el modal
    }

    function updateTotal(quantity, price) {
        var total = quantity * price;
        document.getElementById('confirmTotal').innerText = total.toFixed(2); // Mostrar el total formateado
    }

    function addToCart(id, unitId, price, quantity, productName, description) {
        // Añadir el artículo al carrito
        cart.push({ 
            id: id, 
            unitId: unitId, // Ahora guardamos el ID de la unidad
            description: description, 
            price: price, 
            quantity: quantity 
        });

        // Actualizar la tabla del carrito
        updateCartTable();
        updateCartCount();
    }

    function updateCartTable() {
        var cartTableBody = document.getElementById('cartTable').getElementsByTagName('tbody')[0];
        cartTableBody.innerHTML = ''; // Limpiar la tabla

        var grandTotal = 0;

        cart.forEach(function(item) {
            var row = cartTableBody.insertRow();
            row.insertCell(0).innerText = item.id; // Nombre del producto
            row.insertCell(1).innerText = item.unitId; // ID de la unidad
            row.insertCell(2).innerText = item.description;
            row.insertCell(3).innerText = item.price;
            row.insertCell(4).innerText = item.quantity;

            var total = item.price * item.quantity;
            row.insertCell(5).innerText = total.toFixed(2);

            var deleteButton = document.createElement('button');
            deleteButton.innerText = 'Eliminar';
            deleteButton.className = 'btn btn-danger btn-sm';
            deleteButton.onclick = function() {
                removeFromCart(item.id);
            };
            var cell = row.insertCell(6);
            cell.appendChild(deleteButton);

            grandTotal += total; // Sumar al total general
        });

        document.getElementById('grandTotal').innerText = grandTotal.toFixed(2); // Mostrar el total general
    }

    function updateCartCount() {
        document.getElementById('cartCount').innerText = cart.length; // Actualizar el contador del carrito
    }

    function removeFromCart(id) {
        cart = cart.filter(item => item.id !== id); // Filtrar el carrito
        updateCartTable(); // Actualizar la tabla
        updateCartCount(); // Actualizar el contador
    }

    document.getElementById('confirmSaleButton').onclick = function() {
        // Guardar el ID del cliente
        document.getElementById('customer_id').value = document.getElementById('customerSelect').value;

        // Guardar los productos en un campo oculto
        document.getElementById('productsInput').value = JSON.stringify(cart);

        // Enviar el formulario
        document.getElementById('cartForm').submit();
    };
</script>
<style>
    .modalSale {
        max-width: 65%;
        width: auto;
    }
</style>
@endsection
