<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $sortField = $request->input('sort_field', 'id');
        $sortDirection = $request->input('sort_direction', 'asc');

        $categories = Category::orderBy($sortField, $sortDirection)->get();

        return view('livewire/categories.index', compact('categories', 'sortField', 'sortDirection'));
    }

    public function create()
    {
        return view('livewire/categories.create');
    }

    public function edit(Category $category)
    {
        return view('livewire/categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'
            ],
            'description' => 'nullable|string|max:255',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',
            'name.regex' => 'El nombre solo puede contener letras y espacios.',
            'description.string' => 'La descripción debe ser una cadena de texto.',
            'description.max' => 'La descripción no puede tener más de 255 caracteres.',
        ]);

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('categories.index')->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Categoría eliminada permanentemente.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'
            ],
            'description' => 'nullable|string|max:255',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',
            'name.regex' => 'El nombre solo puede contener letras y espacios.',
            'description.string' => 'La descripción debe ser una cadena de texto.',
            'description.max' => 'La descripción no puede tener más de 255 caracteres.',
        ]);

        Category::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('categories.index')->with('success', 'Categoría creada exitosamente.');
    }
    public function toggleStatus($id)
    {
        $category = Category::findOrFail($id);

        // Alternar el estado
        $category->status = !$category->status;
        $category->save();

        return redirect()->route('categories.index')->with('success', 'Estado de la categoría actualizado.');
    }


    public function exportToExcel(Request $request)
    {
        // Obtener las categorías
        $categories = Category::orderBy('id', 'asc')->get();

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
        $logo->setCoordinates('E2');
        $logo->setWorksheet($sheet);

        // Título y subtítulo
        $sheet->mergeCells('E2:J2');
        $sheet->setCellValue('E2', 'Tienda Baixiang');
        $sheet->getStyle('E2')->getFont()->setSize(40)->setBold(true);
        $sheet->getStyle('E2')->getFont()->getColor()->setARGB('FFFFFF');
        $sheet->getStyle('E2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E2:J2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('d32f2f');

        $sheet->mergeCells('E3:J3');
        $sheet->setCellValue('E3', 'Lista de Categorías');
        $sheet->getStyle('E3')->getFont()->setSize(30)->setBold(true);
        $sheet->getStyle('E3')->getFont()->getColor()->setARGB('FFFFFF');
        $sheet->getStyle('E3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E3:J3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('d32f2f');

        // Encabezados
        $headers = ['N°', 'Nombre', 'Descripción'];
        $colLetters = ['E', 'F', 'G'];

        foreach ($headers as $index => $header) {
            $cell = $colLetters[$index] . '5';
            $sheet->setCellValue($cell, $header);
            $sheet->getStyle($cell)->getFont()->setBold(true)->setSize(20);
            $sheet->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($cell)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
            $sheet->getStyle($cell)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        }

        // Agregar datos de categorías
        $row = 6;
        $num = 1;
        foreach ($categories as $category) {
            $sheet->setCellValue('E' . $row, $num);
            $sheet->setCellValue('F' . $row, $category->name);
            $sheet->setCellValue('G' . $row, $category->description ?? 'N/A');
            $sheet->getStyle('E' . $row . ':G' . $row)->getFont()->setSize(18);
            $sheet->getStyle('E' . $row . ':G' . $row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $row++;
            $num++;
        }

        // Ajustar el ancho de las columnas
        foreach (range('E', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Crear el archivo Excel
        $writer = new Xlsx($spreadsheet);
        $filename = 'categorias.xlsx';

        // Configurar los headers para la descarga
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}

