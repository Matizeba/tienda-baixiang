<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo de Venta</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            color: #333;
        }

        .header {
            text-align: center;
            background-color:#d32f2f;
            margin-bottom: 20px;
            color: #fff;
            
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .info {
            margin-bottom: 20px;
        }

        .info p {
            margin: 0;
            font-size: 14px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }

        .table th {
            background-color: #f2f2f2;
        }

        .footer {
            text-align: right;
            font-size: 14px;
        }

        .footer strong {
            font-size: 16px;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Tienda Baixian-Fama</h1>
    <img src="{{ ('images/logo.png') }}" alt="Logotipo" width="100">
</div>

<div class="info">
    <p><strong>Recibo de Venta #{{ $sale->id }}</p>
    <p><strong>Vendedor:</strong> {{ $sale->user->name }}</p>
    <p><strong>Cliente:</strong> {{ $sale->customer->name }}</p>
    <p><strong>Fecha:</strong> {{ $sale->updated_at->format('d/m/Y') }}</p>
</div>

<h3>Detalles de la Venta</h3>
<table class="table">
    <thead>
        <tr>
            <th>Producto</th>
            <th>Unidad</th>
            <th>Cantidad</th>
            <th>Precio Unitario</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($sale->details as $detail)
        <tr>
            <td>{{ $detail->product->name }}</td>
            <td>{{ $detail->unit->name }}</td>
            <td>{{ $detail->quantity }}</td>
            <td>{{ number_format($detail->price, 2) }} Bs</td>
            <td>{{ number_format($detail->total, 2) }} Bs</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">
    <p><strong>Monto Total: {{ number_format($sale->total_amount, 2) }} Bs</strong></p>
</div>

</body>
</html>
