<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SaleDetail;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Sale;

class reportController2 extends Controller
{
    public function topBuyersReport(Request $request)
    {
        // Obtener las fechas del formulario, con valores predeterminados
        $startDate = $request->input('start_date', now()->subMonth()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());
    
        // Consultar los compradores con más compras completadas y sus productos
        $buyers = Sale::selectRaw('sales.customer_id, COUNT(sales.id) as total_purchases, SUM(sale_details.quantity * sale_details.price) as total_spent')
            ->join('sale_details', 'sales.id', '=', 'sale_details.sale_id') // Unir con detalles de ventas
            ->whereBetween('sales.created_at', [$startDate, $endDate]) // Filtrar por rango de fechas
            ->where('sales.status', 'completed') // Filtrar solo compras completadas
            ->groupBy('sales.customer_id')
            ->orderByDesc('total_spent') // Ordenar por total gastado
            ->with(['customer', 'saleDetails.product']) // Obtener el comprador y sus productos
            ->get();
    
        // Pasar los datos a la vista
        return view('livewire.reports.top-buyers-report', compact('buyers', 'startDate', 'endDate'));
    }
    
    public function generateTopBuyersPdf(Request $request)
    {
        // Obtener las fechas del formulario
        $startDate = $request->input('start_date', now()->subMonth()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());
    
        // Asegurarse de que las fechas sean objetos Carbon
        $startDate = Carbon::parse($startDate);
        $endDate = $endDate ? Carbon::parse($endDate) : null;
    
        // Consultar los compradores con más compras completadas y sus productos
        $buyers = Sale::selectRaw('sales.customer_id, COUNT(sales.id) as total_purchases, SUM(sale_details.quantity * sale_details.price) as total_spent')
            ->join('sale_details', 'sales.id', '=', 'sale_details.sale_id') // Unir con detalles de ventas
            ->whereBetween('sales.created_at', [$startDate, $endDate ?? now()]) // Filtrar por fechas
            ->where('sales.status', 'completed') // Filtrar solo compras completadas
            ->groupBy('sales.customer_id')
            ->orderByDesc('total_spent') // Ordenar por total gastado
            ->with(['customer', 'saleDetails.product']) // Obtener el comprador y sus productos
            ->get();
    
        // Verificar si hay datos en el rango de fechas
        if ($buyers->isEmpty()) {
            return response()->json(['message' => 'No purchases found for the selected date range.']);
        }
    
        // Generar el PDF con la vista y los datos
        $pdf = $this->pdf->loadView('reports.top-buyers-report-pdf', compact('buyers', 'startDate', 'endDate'));
    
        // Descargar el PDF
        return $pdf->download('reporte_compradores_mas_compras.pdf');
    }
    
}
