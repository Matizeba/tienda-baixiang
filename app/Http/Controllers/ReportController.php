<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SaleDetail;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Sale;


use Barryvdh\DomPDF\PDF; // Usar esta clase
class ReportController extends Controller
{
    public function topSellingProduct(Request $request)
{
    // Obtener las fechas del formulario, con valores predeterminados si no se proporcionan
    $startDate = $request->input('start_date', now()->subMonth()->toDateString()); // Por defecto, 1 mes atrás
    $endDate = $request->input('end_date', now()->toDateString()); // Por defecto, fecha actual

    // Obtener los productos más vendidos dentro del rango de fechas
    $topProducts = SaleDetail::selectRaw('product_id, unit_id, SUM(quantity) as total_sold, SUM(quantity * price) as total_revenue')
        ->whereBetween('created_at', [$startDate, $endDate]) // Filtrar por rango de fechas
        ->groupBy('product_id', 'unit_id')
        ->orderByDesc('total_sold')
        ->with(['product', 'unit']) // Cargar las relaciones de producto y unidad
        ->get();

    // Pasar los datos a la vista
    return view('livewire.reports.top-selling-product', compact('topProducts', 'startDate', 'endDate'));
}

protected $pdf;

    // Inyecta la instancia de PDF
    public function __construct(PDF $pdf)
    {
        $this->pdf = $pdf;
    }

    public function generatePdf(Request $request)
{
    // Obtener las fechas del formulario
    $startDate = $request->input('start_date', now()->subMonth()->toDateString()); // Por defecto, 1 mes atrás
    $endDate = $request->input('end_date', now()->toDateString()); // Por defecto, fecha actual

    // Asegurarse de que las fechas sean objetos Carbon
    $startDate = Carbon::parse($startDate);
    $endDate = $endDate ? Carbon::parse($endDate) : null; // Si no hay fecha de fin, usar null

    // Obtener los productos más vendidos dentro del rango de fechas
    $topProducts = SaleDetail::selectRaw('product_id, unit_id, SUM(quantity) as total_sold, SUM(quantity * price) as total_revenue')
        ->whereBetween('created_at', [$startDate, $endDate ?? now()])
        ->groupBy('product_id', 'unit_id')
        ->orderByDesc('total_sold')
        ->with(['product', 'unit']) // Cargar las relaciones de producto y unidad
        ->get();

    // Verificar si hay productos en el rango de fechas
    if ($topProducts->isEmpty()) {
        return response()->json(['message' => 'No products found for the selected date range.']);
    }

    // Generar el PDF con la vista y los datos
    $pdf = $this->pdf->loadView('reports.pdf', compact('topProducts', 'startDate', 'endDate'));

    // Descargar el PDF generado
    return $pdf->download('reporte_productos_mas_vendidos.pdf');
}
public function salesReport(Request $request)
{
    // Obtener las fechas del formulario
    $startDate = $request->input('start_date', now()->subMonth()->toDateString()); // Por defecto, 1 mes atrás
    $endDate = $request->input('end_date', now()->toDateString()); // Por defecto, fecha actual

    // Obtener las ventas completadas por vendedor
    $sales = Sale::selectRaw('sales.user_id,COUNT(sales.id) as total_transactions, SUM(sale_details.quantity * sale_details.price) as total_revenue')
        ->join('sale_details', 'sales.id', '=', 'sale_details.sale_id') // Unir con los detalles de las ventas
        ->whereBetween('sales.created_at', [$startDate, $endDate]) // Filtrar por fechas
        ->where('sales.status', 'completed') // Filtrar solo ventas completadas
        ->groupBy('sales.user_id')
        ->orderByDesc('total_sales') // Ordenar por total de ventas
        ->with('user') // Obtener el vendedor
        ->get();

    // Pasar los datos a la vista
    return view('livewire.reports.sales-report', compact('sales', 'startDate', 'endDate'));
}
public function generateSalesPdf(Request $request)
    {
        // Obtener las fechas del formulario
        $startDate = $request->input('start_date', now()->subMonth()->toDateString()); // Por defecto, 1 mes atrás
        $endDate = $request->input('end_date', now()->toDateString()); // Por defecto, fecha actual

        // Asegurarse de que las fechas sean objetos Carbon
        $startDate = Carbon::parse($startDate);
        $endDate = $endDate ? Carbon::parse($endDate) : null; // Si no hay fecha de fin, usar null

        // Obtener las ventas completadas por vendedor dentro del rango de fechas
        $sales = Sale::selectRaw('sales.user_id, COUNT(sales.id) as total_transactions, SUM(sale_details.quantity * sale_details.price) as total_revenue')
            ->join('sale_details', 'sales.id', '=', 'sale_details.sale_id') // Unir con los detalles de las ventas
            ->whereBetween('sales.created_at', [$startDate, $endDate ?? now()]) // Filtrar por fechas
            ->where('sales.status', 'completed') // Filtrar solo ventas completadas
            ->groupBy('sales.user_id')
            ->orderByDesc('total_revenue') // Ordenar por total de ventas
            ->with('user') // Obtener el vendedor
            ->get();

        // Verificar si hay ventas en el rango de fechas
        if ($sales->isEmpty()) {
            return response()->json(['message' => 'No sales found for the selected date range.']);
        }

        // Generar el PDF con la vista y los datos
        $pdf = $this->pdf->loadView('reports.sales-report-pdf', compact('sales', 'startDate', 'endDate'));

        // Descargar el PDF generado
        return $pdf->download('reporte_vendedores_ventas_completadas.pdf');
    }

}
