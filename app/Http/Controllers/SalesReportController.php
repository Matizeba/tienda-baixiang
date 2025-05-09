<?php

namespace App\Http\Controllers;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Chart\Axis;

class SalesReportController extends Controller
{
    public function salesByMonth(Request $request)
{
    // Obtener el año seleccionado, o el año actual por defecto
    $year = $request->input('year', \Carbon\Carbon::now()->year);

    // Construir las fechas de inicio y fin del año
    $startDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', "$year-01-01 00:00:00");
    $endDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', "$year-12-31 23:59:59");

    // Ventas agrupadas por mes y estado filtradas por rango de fechas
    $salesByMonth = DB::table('sales')
        ->selectRaw("strftime('%m', created_at) as month, status, SUM(total_amount) as total_sales")
        ->whereBetween('created_at', [$startDate, $endDate])
        ->whereIn('status', ['completed', 'pending']) // Filtrar por estado
        ->groupBy('month', 'status')
        ->orderBy('month')
        ->get();

    // Crear arrays para ventas completadas y pendientes
    $completedSalesData = array_fill(1, 12, 0); // Inicializa con ceros para cada mes
    $pendingSalesData = array_fill(1, 12, 0); // Inicializa con ceros para cada mes

    // Llenar los arrays con los datos obtenidos
    foreach ($salesByMonth as $sale) {
        $month = (int)$sale->month; // Convertir a entero
        if ($sale->status === 'completed') {
            $completedSalesData[$month] = $sale->total_sales; // Asignar el total de ventas completadas al mes correspondiente
        } elseif ($sale->status === 'pending') {
            $pendingSalesData[$month] = $sale->total_sales; // Asignar el total de ventas pendientes al mes correspondiente
        }
    }

    // Retornar la vista con los datos
    return view('reports.sales_by_month', compact('completedSalesData', 'pendingSalesData', 'year'));
}
public function exportToExcel(Request $request)
{
    // Obtener las ventas completadas y pendientes por mes
    $completedSalesData = [1000, 1500, 2000, 2500, 3000, 3500, 4000, 4500, 5000, 5500, 6000, 6500];
    $pendingSalesData = [500, 700, 800, 600, 900, 750, 950, 850, 700, 600, 800, 900];
    $months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

    // Crear una nueva hoja de cálculo
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Títulos
    $sheet->setCellValue('A1', 'Ventas Completadas y Pendientes por Mes');
    $sheet->mergeCells('A1:C1');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);

    // Encabezados
    $sheet->setCellValue('A2', 'Mes');
    $sheet->setCellValue('B2', 'Ventas Completadas');
    $sheet->setCellValue('C2', 'Ventas Pendientes');

    // Llenar los datos
    foreach ($months as $index => $month) {
        $sheet->setCellValue('A' . ($index + 3), $month);
        $sheet->setCellValue('B' . ($index + 3), $completedSalesData[$index]);
        $sheet->setCellValue('C' . ($index + 3), $pendingSalesData[$index]);
    }

    // Crear el gráfico
    $dataSeriesLabels = [
        new DataSeriesValues('String', 'Hoja1!$B$2', null, 1), // Ventas Completadas
        new DataSeriesValues('String', 'Hoja1!$C$2', null, 1), // Ventas Pendientes
    ];

    $xAxisTickValues = [
        new DataSeriesValues('String', 'Hoja1!$A$3:$A$14', null, 12), // Meses
    ];

    $dataSeriesValues = [
        new DataSeriesValues('Number', 'Hoja1!$B$3:$B$14', null, 12), // Ventas Completadas
        new DataSeriesValues('Number', 'Hoja1!$C$3:$C$14', null, 12), // Ventas Pendientes
    ];

    // Definir la serie de datos
    $series = new DataSeries(
        DataSeries::TYPE_LINECHART,
        DataSeries::GROUPING_STANDARD,
        range(0, count($dataSeriesValues) - 1),
        $dataSeriesLabels,
        $xAxisTickValues,
        $dataSeriesValues
    );

    // Crear el área del gráfico
    $plotArea = new PlotArea(null, [$series]);

    // Crear el gráfico
    $chart = new Chart(
        'sales_chart',
        new Title('Ventas Completadas vs Pendientes'), // Este es el título del gráfico
        new Legend(Legend::POSITION_RIGHT, null, false), // Esta es la leyenda
        $plotArea,
        true,
        0,
        null // Cambiado a null para el xAxisLabel
    );

    // Agregar el gráfico a la hoja
    $chart->setTopLeftPosition('E5');
    $chart->setBottomRightPosition('L20');
    $sheet->addChart($chart);

    // Ajustar automáticamente el ancho de las columnas
    foreach (range('A', 'C') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Crear el archivo Excel
    $writer = new Xlsx($spreadsheet);
    $filename = 'ventas_completadas_y_pendientes.xlsx';

    // Configurar los headers para la descarga del archivo
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    // Enviar el archivo al navegador
    $writer->save('php://output');
    exit;
}


}