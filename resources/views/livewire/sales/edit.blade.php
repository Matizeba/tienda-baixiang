@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Venta</h2>

    <div class="row mb-3">
        <div class="col-md-6">
            <label for="customerSelect" class="form-label">Cliente</label>
            <select id="customerSelect" class="form-select" name="customer_id">
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}" {{ $sale->customer_id == $customer->id ? 'selected' : '' }}>
                        {{ $customer->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 text-end">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cartModal">
                <i class="fas fa-shopping-cart"></i> Carrito <span class="badge bg-secondary" id="cartCount">{{ is_countable($sale->details) ? count($sale->details) : 0 }}</span>
            </button>
        </div>
    </div>
    
    <div class="mb-3">
        <label for="productSelect" class="form-label">Seleccionar Producto</label>
        <select id="productSelect" class="form-select" onchange="updateUnits()">
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

    <!-- Modal de Confirmación de Cantidad -->
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
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <th>Total</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($sale->details) && is_iterable($sale->details))
                                @foreach($sale->details as $detail)
                                    <tr>
                                        <td>{{ $detail->product->name }}</td>
                                        <td>{{ $detail->unit ? $detail->unit->name : 'Sin unidad' }}</td>
                                        <td>{{ $detail->unit ? $detail->unit->description : 'Sin descripción' }}</td>
                                        <td>
                                            <input type="number" class="form-control" value="{{ $detail->quantity }}" min="1" onchange="updateQuantity(this, '{{ $detail->product->id }}', '{{ $detail->unit->id }}')">
                                        </td>
                                        <td>{{ $detail->price }} Bs</td>
                                        <td>{{ $detail->total }} Bs</td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('{{ $detail->product->id }}', '{{ $detail->unit->id }}')">Eliminar</button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="text-center">No hay artículos en la venta.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    <h5>Total General: <span id="grandTotal">{{ number_format($sale->details->sum('total'), 2) }}</span> Bs</h5>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" onclick="updateCartInputAndSubmit()">Guardar Cambios</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación para cancelar la venta -->
    <div class="modal fade" id="cancelSaleModal" tabindex="-1" aria-labelledby="cancelSaleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelSaleModalLabel">Cancelar Venta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas cancelar esta venta? Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <form id="cancelSaleForm" action="{{ route('sales.update', $sale->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="cancelled">
                        <input type="hidden" name="sale_id" value="{{ $sale->id }}">

                        <button type="submit" class="btn btn-danger">Confirmar Cancelación</button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulario oculto para enviar datos -->
    <form id="editCartForm" action="{{ route('sales.update', $sale->id) }}" method="POST" style="display: none;">
        @csrf
        @method('PUT')

        <input type="hidden" name="sale_id" value="{{ $sale->id }}">
        <input type="hidden" name="customer_id" value="{{ $sale->customer_id }}">
        <input type="hidden" name="user_id" value="{{ $sale->user_id }}">
        <input type="hidden" name="total_amount" value="{{ $sale->total_amount }}">
        <input type="hidden" name="status" value="{{ $sale->status }}">
        <input type="hidden" name="cart" id="cartInput" value='{{ json_encode($cartDetails) }}'> <!-- Campo oculto para el carrito -->
    </form>

    <!-- Botón para cancelar la venta -->
    

</div>

<script>
    // Inicializar array del carrito
    var cart = {!! json_encode($cartDetails) !!};

    // Actualizar contador del carrito
    function updateCartCount() {
        document.getElementById('cartCount').innerText = cart.length;
    }

    function updateGrandTotal() {
        var grandTotal = cart.reduce((total, item) => total + item.quantity * item.price, 0);
        document.getElementById('grandTotal').innerText = grandTotal.toFixed(2);
    }

    function updateCartTable() {
        var cartTableBody = document.querySelector('#cartTable tbody');
        cartTableBody.innerHTML = ''; // Limpiar la tabla

        cart.forEach(function(item) {
            var row = cartTableBody.insertRow();
            row.insertCell(0).innerText = item.productName;
            row.insertCell(1).innerText = item.unitId ? item.unitId : 'Sin unidad';
            row.insertCell(2).innerText = item.description;
            var quantityCell = row.insertCell(3);
            var quantityInput = document.createElement('input');
            quantityInput.type = 'number';
            quantityInput.className = 'form-control';
            quantityInput.value = item.quantity;
            quantityInput.min = 1;
            quantityInput.onchange = function() {
                updateQuantity(this, item.id, item.unitId);
            };
            quantityCell.appendChild(quantityInput);
            row.insertCell(4).innerText = item.price.toFixed(2) + ' Bs';
            row.insertCell(5).innerText = (item.price * item.quantity).toFixed(2) + ' Bs';
            var actionsCell = row.insertCell(6);
            var deleteButton = document.createElement('button');
            deleteButton.className = 'btn btn-danger btn-sm';
            deleteButton.innerText = 'Eliminar';
            deleteButton.onclick = function() {
                confirmDelete(item.id, item.unitId);
            };
            actionsCell.appendChild(deleteButton);
        });

        updateGrandTotal();
    }

    function confirmDelete(productId, unitId) {
        // Eliminar el producto del carrito
        cart = cart.filter(item => !(item.id === productId && item.unitId === unitId));
        updateCartTable();
        updateCartCount();
    }

    function updateQuantity(input, productId, unitId) {
        var quantity = parseInt(input.value);
        var item = cart.find(item => item.id === productId && item.unitId === unitId);
        if (item) {
            item.quantity = quantity;
        }
        updateGrandTotal();
    }

    function addToCart(productId, unitId, price, quantity, productName, description) {
        var existingItem = cart.find(item => item.id === productId && item.unitId === unitId);

        if (existingItem) {
            alert(`El producto ${productName} con esta unidad ya está en el carrito.`);
            return;
        } else {
            cart.push({
                id: productId,
                unitId: unitId,
                productName: productName,
                description: description,
                price: parseFloat(price),
                quantity: parseInt(quantity)
            });
        }

        updateCartTable();
        updateCartCount();
        $('#confirmQuantityModal').modal('hide');
    }

    function updateCartInputAndSubmit() {
        document.getElementById('cartInput').value = JSON.stringify(cart);
        document.getElementById('editCartForm').submit();
    }

    function updateUnits() {
        var productId = document.getElementById('productSelect').value;
        var unitsTableBody = document.getElementById('unitsTable').getElementsByTagName('tbody')[0];
        unitsTableBody.innerHTML = ''; // Limpiar la tabla

        @foreach($products as $product)
            if (productId == "{{ $product->id }}") {
                @foreach($product->productUnits as $productUnit)
                    var unitId = "{{ $productUnit->unit->id }}";
                    var productId = "{{ $product->id }}";

                    var row = unitsTableBody.insertRow();
                    row.insertCell(0).innerText = "{{ $productUnit->product->name }}";
                    row.insertCell(1).innerText = "{{ $productUnit->unit->name }}";
                    row.insertCell(2).innerText = "{{ $productUnit->unit->description }}";
                    row.insertCell(3).innerText = "{{ $productUnit->price }}";
                    row.insertCell(4).innerText = "{{ $productUnit->stock }}";

                    var existingItem = cart.find(item => item.id == productId && item.unitId == unitId);
                    var cell = row.insertCell(5);
                    if (existingItem) {
                        var message = document.createElement('span');
                        message.innerText = 'Producto ya agregado';
                        message.className = 'text-success';
                        cell.appendChild(message);
                    } else {
                        var addButton = document.createElement('button');
                        addButton.className = 'btn btn-success btn-sm';
                        addButton.innerText = 'Añadir';
                        addButton.onclick = (function(unitId, price, productId, productName, description, stock) {
                            return function() {
                                openConfirmModal(unitId, price, productId, productName, description, stock);
                            };
                        })("{{ $productUnit->unit->id }}", "{{ $productUnit->price }}", "{{ $product->id }}", "{{ $product->name }}", "{{ $productUnit->unit->description }}", "{{ $productUnit->stock }}");
                        cell.appendChild(addButton);
                    }
                @endforeach
            }
        @endforeach
    }

    function openConfirmModal(unitId, price, productId, productName, description, stock) {
        document.getElementById('confirmProductName').innerText = productName;
        document.getElementById('confirmDescription').innerText = description;
        document.getElementById('confirmPrice').innerText = price;

        var quantityInput = document.getElementById('confirmQuantityInput');
        quantityInput.value = 1;
        quantityInput.max = stock;
        updateTotal(quantityInput.value, price);

        quantityInput.oninput = function() {
            var quantity = parseInt(this.value);
            if (quantity > stock) {
                this.value = stock;
                alert("No se puede seleccionar más de " + stock + " unidades.");
            }
            updateTotal(this.value, price);
        };

        document.getElementById('addToCartButton').onclick = function() {
            var quantity = parseInt(quantityInput.value);
            if (quantity > stock) {
                alert("No se puede añadir más de " + stock + " unidades.");
                return;
            }
            addToCart(productId, unitId, price, quantity, productName, description);
        };

        $('#confirmQuantityModal').modal('show');
    }

    function updateTotal(quantity, price) {
        var total = quantity * price;
        document.getElementById('confirmTotal').innerText = total.toFixed(2);
    }

    // Inicializar la tabla del carrito al cargar la página
    updateCartTable();
    updateCartCount();
</script>

<style>
    .modalSale {
        max-width: 80%;
        width: auto;
    }
</style>
@endsection
