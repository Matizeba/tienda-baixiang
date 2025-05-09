<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Vendedores con Más Ventas Completadas</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        h1 {
            text-align: center;
            color: #c62828;
            font-size: 30px;
            margin-bottom: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            background-color: #c62828;
            color: white;
            padding: 10px 20px;
            margin-bottom: 20px;
        }

        .header p {
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #c62828;
            color: white;
            font-size: 14px;
        }

        td {
            font-size: 13px;
            color: #555;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 12px;
            color: #777;
        }

        .footer p {
            margin: 0;
        }

        .total-revenue {
            font-weight: bold;
            color: #c62828;
        }

    </style>
</head>
<body>
    <h1>Reporte de Vendedores con Más Ventas Completadas</h1>
    
    <div class="header">
        <p><strong>Desde:</strong> {{ $startDate->format('d/m/Y') }} <strong>Hasta:</strong> {{ $endDate ? $endDate->format('d/m/Y') : 'Hoy' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Vendedor</th>
                <th>Total Ventas</th>
                <th>Ganancia Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $sale)
                <tr>
                    <td>{{ $sale->user->name }} {{ $sale->user->first_surname }} {{ $sale->user->second_surname }}</td>
                    <td>{{ $sale->total_transactions }}</td>
                    <td class="total-revenue">${{ number_format($sale->total_revenue, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Reporte generado el {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>
