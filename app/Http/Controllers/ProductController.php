<?php

namespace App\Http\Controllers;

use App\Models\Product;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $sortField = $request->input('sort_field', 'id');
        $sortDirection = $request->input('sort_direction', 'asc');
        
        $products = Product::orderBy($sortField, $sortDirection)->get();

        return view('livewire/products.index', compact('products', 'sortField', 'sortDirection'));
    }

    public function create()
    {
        return view('livewire/products.create');
    }

    public function edit(Product $product)
    {
        return view('livewire/products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'], // Solo letras y espacios
            'description' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'], // Solo letras y espacios
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'category' => 'required|numeric', // Solo números para categorías
        ]);

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'category' => $request->category,
        ]);

        return redirect()->route('products.index')->with('success', 'Producto actualizado con éxito.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Producto eliminado con éxito.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'], // Solo letras y espacios
            'description' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'], // Solo letras y espacios
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'category' => 'required|numeric', // Solo números para categorías
        ]);

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'category' => $request->category,
        ]);

        return redirect()->route('products.index')->with('success', 'Producto creado con éxito.');
    }

    public function toggleStatus($id)
    {
        $product = Product::findOrFail($id);
        $product->status = !$product->status; // Cambiar el estado
        $product->save();

        return redirect()->route('products.index')->with('success', 'Estado del producto actualizado con éxito.');
    }

    public function exportToExcel(Request $request)
    {
        // Obtener los productos
        $products = Product::orderBy('id', 'asc')->get();

        // Crear una nueva hoja de cálculo
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Insertar el logo en E2
        $logoPath = public_path('images/logo.png');
        $logo = new Drawing();
        $logo->setName('Logo');
        $logo->setDescription('Logo');
        $logo->setPath($logoPath);
        $logo->setHeight(90); // Ajusta la altura del logo según sea necesario
        $logo->setCoordinates('E2'); // Establece la celda en la que se ubicará el logo
        $logo->setWorksheet($sheet);

        // Título y subtítulo
        $sheet->mergeCells('E2:J2'); // Ajustar el rango para desplazar el contenido
        $sheet->setCellValue('E2', 'Tienda Baixiang');
        $sheet->getStyle('E2')->getFont()->setSize(40)->setBold(true); // Aumentar tamaño de texto
        $sheet->getStyle('E2')->getFont()->getColor()->setARGB('FFFFFF'); // Color del texto blanco
        $sheet->getStyle('E2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E2:J2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('d32f2f'); // Color de fondo

        $sheet->mergeCells('E3:J3'); // Ajustar el rango para desplazar el contenido
        $sheet->setCellValue('E3', 'Lista de Productos');
        $sheet->getStyle('E3')->getFont()->setSize(30)->setBold(true); // Aumentar tamaño de texto
        $sheet->getStyle('E3')->getFont()->getColor()->setARGB('FFFFFF'); // Color del texto blanco
        $sheet->getStyle('E3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E3:J3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('d32f2f'); // Color de fondo

        // Ajustar el ancho de las columnas para los encabezados
        $headers = ['N°', 'Nombre', 'Descripción', 'Cantidad', 'Precio', 'Categoría'];
        $colLetters = ['E', 'F', 'G', 'H', 'I', 'J'];

        foreach ($headers as $index => $header) {
            $cell = $colLetters[$index] . '5'; // Ajustar la fila de encabezado a la fila 5
            $sheet->setCellValue($cell, $header);

            // Aplicar formato de encabezado
            $sheet->getStyle($cell)->getFont()->setBold(true)->setSize(20); // Tamaño de texto en encabezado
            $sheet->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($cell)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00'); // Color de fondo amarillo
            $sheet->getStyle($cell)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        }

        // Agregar datos de productos
        $row = 6; // Iniciar en la fila 6
        $num = 1; // Iniciar enumeración en 1
        foreach ($products as $product) {
            $sheet->setCellValue('E' . $row, $num); // Número de enumeración
            $sheet->setCellValue('F' . $row, $product->name);
            $sheet->setCellValue('G' . $row, $product->description);
            $sheet->setCellValue('H' . $row, $product->quantity);
            $sheet->setCellValue('I' . $row, '    ' . number_format($product->price, 2).'BS.'); // Concatenar 'BS.' al precio y formatear como número decimal
            $sheet->setCellValue('J' . $row, $product->category);
            
            // Aplicar formato a las celdas de datos
            $sheet->getStyle('E' . $row . ':J' . $row)->getFont()->setSize(18); // Tamaño de texto en datos
            $sheet->getStyle('E' . $row . ':J' . $row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            $row++;
            $num++;
        }

        // Ajustar automáticamente el ancho de las columnas
        foreach (range('E', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Crear el archivo Excel
        $writer = new Xlsx($spreadsheet);
        $filename = 'productos.xlsx';
        
        // Configurar los headers para la descarga del archivo
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"'); 
        header('Cache-Control: max-age=0');
        
        // Enviar el archivo al navegador
        $writer->save('php://output');
        exit;
    }
}
