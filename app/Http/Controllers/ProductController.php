<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category; // Asegúrate de importar el modelo Category
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $sortField = $request->input('sort_field', 'id');
        $sortDirection = $request->input('sort_direction', 'asc');

        $products = Product::orderBy($sortField, $sortDirection)->get();

        return view('livewire/products.index', compact('products', 'sortField', 'sortDirection'));
    }

    public function view(Request $request)
{
    $categoryId = $request->input('category_id');
    $searchTerm = $request->input('search_term');
    $status = $request->input('status');

    $query = Product::query(); // Elimina el filtro de estado aquí para manejarlo más tarde

    if ($status !== null) {
        $query->where('status', $status);
    } else {
        $query->whereIn('status', [0, 1]); // Muestra todos los estados si no se especifica
    }

    if ($categoryId) {
        $query->where('category_id', $categoryId);
    }

    if ($searchTerm) {
        $query->where('name', 'like', '%' . $searchTerm . '%');
    }

    $products = $query->get();
    $categories = Category::all(); // Asegúrate de pasar todas las categorías a la vista

    return view('livewire.products.view', compact('products', 'categories', 'searchTerm', 'categoryId', 'status'));
}


    public function create()
    {
        $categories = Category::all(); // Obtener todas las categorías
        return view('livewire/products.create', compact('categories'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all(); // Obtener todas las categorías
        return view('livewire/products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'description' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id', // Asegúrate de que sea un ID válido de categoría
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::delete('public/' . $product->image);
            }

            $extension = $request->file('image')->extension();
            $newImageName = 'p' . $product->id . '.' . $extension;
            $imagePath = $request->file('image')->storeAs('products', $newImageName, 'public');

            $product->image = $imagePath;
        }

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'category_id' => $request->category_id, // Asegúrate de usar category_id
        ]);

        return redirect()->route('products.index')->with('success', 'Producto actualizado con éxito.');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::delete('public/' . $product->image);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Producto eliminado con éxito.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'description' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id', // Asegúrate de que sea un ID válido de categoría
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $extension = $request->file('image')->extension();
            $newImageName = 'p' . (Product::max('id') + 1) . '.' . $extension;
            $imagePath = $request->file('image')->storeAs('products', $newImageName, 'public');
        } else {
            $imagePath = null;
        }

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'category_id' => $request->category_id, // Asegúrate de usar category_id
            'image' => $imagePath,
        ]);

        return redirect()->route('products.index')->with('success', 'Producto creado con éxito.');
    }

    public function toggleStatus($id)
    {
        $product = Product::findOrFail($id);
        $product->status = !$product->status;
        $product->save();

        return redirect()->route('products.index')->with('success', 'Estado del producto actualizado con éxito.');
    }

    public function exportToExcel(Request $request)
    {
        $products = Product::orderBy('id', 'asc')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $logoPath = public_path('images/logo.png');
        $logo = new Drawing();
        $logo->setName('Logo');
        $logo->setDescription('Logo');
        $logo->setPath($logoPath);
        $logo->setHeight(90);
        $logo->setCoordinates('E2');
        $logo->setWorksheet($sheet);

        $sheet->mergeCells('E2:J2');
        $sheet->setCellValue('E2', 'Tienda Baixiang');
        $sheet->getStyle('E2')->getFont()->setSize(40)->setBold(true);
        $sheet->getStyle('E2')->getFont()->getColor()->setARGB('FFFFFF');
        $sheet->getStyle('E2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E2:J2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('d32f2f');

        $sheet->mergeCells('E3:J3');
        $sheet->setCellValue('E3', 'Lista de Productos');
        $sheet->getStyle('E3')->getFont()->setSize(30)->setBold(true);
        $sheet->getStyle('E3')->getFont()->getColor()->setARGB('FFFFFF');
        $sheet->getStyle('E3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E3:J3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('d32f2f');

        $headers = ['N°', 'Nombre', 'Descripción', 'Cantidad', 'Precio', 'Categoría'];
        $colLetters = ['E', 'F', 'G', 'H', 'I', 'J'];

        foreach ($headers as $index => $header) {
            $cell = $colLetters[$index] . '5';
            $sheet->setCellValue($cell, $header);
            $sheet->getStyle($cell)->getFont()->setBold(true)->setSize(20);
            $sheet->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($cell)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
            $sheet->getStyle($cell)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        }

        $row = 6;
        $num = 1;
        foreach ($products as $product) {
            $sheet->setCellValue('E' . $row, $num);
            $sheet->setCellValue('F' . $row, $product->name);
            $sheet->setCellValue('G' . $row, $product->description);
            $sheet->setCellValue('H' . $row, $product->quantity);
            $sheet->setCellValue('I' . $row, '    ' . number_format($product->price, 2).'BS.');
            $sheet->setCellValue('J' . $row, $product->category->name); // Asegúrate de mostrar el nombre de la categoría
            $sheet->getStyle('E' . $row . ':J' . $row)->getFont()->setSize(18);
            $sheet->getStyle('E' . $row . ':J' . $row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $row++;
            $num++;
        }

        foreach (range('E', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'productos.xlsx';
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }
}
