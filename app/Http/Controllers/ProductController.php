<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category; // Asegúrate de importar el modelo Category
use App\Models\Unit;
use App\Models\ProductUnit;
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
    public function index()
{
    $products = Product::with(['productUnits.unit', 'category'])->get();
    $units = Unit::all(); // Asegúrate de obtener todas las unidades
    $categories = Category::all(); // Asegúrate de obtener todas las categorías

    return view('livewire.products.index', compact('products', 'units', 'categories'));
}

    
    public function getUnit($id)
    {
        $unit = Unit::find($id);
        return response()->json($unit);
    }

    public function view(Request $request)
    {
        $categoryId = $request->input('category_id');
        $searchTerm = $request->input('search_term');
        $status = $request->input('status');

        $query = Product::query();

        if ($status !== null) {
            $query->where('status', $status);
        } else {
            $query->whereIn('status', [0, 1]);
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($searchTerm) {
            $query->where('name', 'like', '%' . $searchTerm . '%');
        }

        $products = $query->get();
        $categories = Category::all();

        return view('livewire.products.view', compact('products', 'categories', 'searchTerm', 'categoryId', 'status'));
    }

    public function create()
    {
        // Obtener las categorías y unidades disponibles para el formulario
        $categories = Category::all(); // Suponiendo que tienes un modelo Category
        $units = Unit::all(); // Suponiendo que tienes un modelo Unit

        return view('livewire/products.create', compact('categories', 'units'));
    }


    

    public function edit($id)
    {
        $product = Product::with('productUnits.unit')->findOrFail($id);
        $categories = Category::all(); 
        $units = Unit::all();
    
        return view('livewire/products.edit', compact('product', 'categories', 'units'));
    }
    
    public function update(Request $request, $id)
{
    $request->validate([
        'name' => ['required', 'string', 'max:255', 'regex:/^[0-9a-zA-Z\s]+$/'],
        'description' => ['required', 'string', 'max:255', 'regex:/^[0-9a-zA-Z\s]+$/'], // Permitir números y espacios
        'category_id' => 'required|exists:categories,id',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'units' => 'required|array',
        'units.*.id' => 'required|exists:units,id',
        'units.*.price' => 'required|numeric|min:0',
        'units.*.stock' => 'required|integer|min:0',
    ], [
        'name.required' => 'El nombre es obligatorio.',
        'name.regex' => 'El nombre solo puede contener letras, números y espacios.',
        'description.required' => 'La descripción es obligatoria.',
        'description.regex' => 'La descripción solo puede contener letras, números y espacios.',
        'category_id.required' => 'La categoría es obligatoria.',
        'category_id.exists' => 'La categoría seleccionada no es válida.',
        'image.image' => 'El archivo debe ser una imagen.',
        'image.mimes' => 'La imagen debe ser de tipo jpeg, png, jpg o gif.',
        'image.max' => 'La imagen no puede ser mayor de 2048 kilobytes.',
        'units.required' => 'Las unidades son obligatorias.',
        'units.array' => 'Las unidades deben ser un arreglo.',
        'units.*.id.required' => 'La unidad es obligatoria.',
        'units.*.id.exists' => 'La unidad seleccionada no es válida.',
        'units.*.price.required' => 'El precio es obligatorio.',
        'units.*.stock.required' => 'El stock es obligatorio.',
    ]);
    
    // Encontrar el producto
    $product = Product::findOrFail($id);
    
    // Manejar la imagen
    if ($request->hasFile('image')) {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $extension = $request->file('image')->extension();
        $newImageName = 'p' . $product->id . '.' . $extension;
        $imagePath = $request->file('image')->storeAs('products', $newImageName, 'public');
    } else {
        $imagePath = $product->image; // Mantener la imagen actual si no se subió una nueva
    }
    
    // Actualizar el producto
    $product->update([
        'name' => $request->name,
        'description' => $request->description,
        'category_id' => $request->category_id,
        'image' => $imagePath,
    ]);
    
    // Eliminar unidades existentes
    $product->productUnits()->delete();
    
    // Guardar unidades
    foreach ($request->units as $unit) {
        $product->productUnits()->create([
            'unit_id' => $unit['id'],
            'price' => $unit['price'],
            'stock' => $unit['stock'],
        ]);
    }
    
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
        'category_id' => 'required|exists:categories,id',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'units' => 'required|array', // Se espera un array de unidades
        'units.*.id' => 'required|exists:units,id', // Validar cada unidad
        'units.*.price' => 'required|numeric|min:0', // Precio por unidad
        'units.*.stock' => 'required|integer|min:0', // Stock por unidad
    ]);

    // Manejo de la imagen
    if ($request->hasFile('image')) {
        $extension = $request->file('image')->extension();
        $newImageName = 'p' . (Product::max('id') + 1) . '.' . $extension;
        $imagePath = $request->file('image')->storeAs('products', $newImageName, 'public');
    } else {
        $imagePath = null;
    }

    // Crear el producto
    $product = Product::create([
        'name' => $request->name,
        'description' => $request->description,
        'category_id' => $request->category_id,
        'image' => $imagePath,
        'user_id' => auth()->id(), // Asignar el usuario autenticado
    ]);

    // Guardar unidades
    foreach ($request->units as $unit) {
        $product->productUnits()->create([
            'unit_id' => $unit['id'],
            'price' => $unit['price'],
            'stock' => $unit['stock'],
        ]);
    }

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
    $products = Product::with('units')->orderBy('id', 'asc')->get();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Configuración del logo
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

    $headers = ['N°', 'Nombre', 'Descripción', 'Categoría', 'Unidad', 'Precio', 'Stock'];
    $colLetters = ['E', 'F', 'G', 'H', 'I', 'J', 'K'];

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
        foreach ($product->units as $unit) {
            $sheet->setCellValue('E' . $row, $num);
            $sheet->setCellValue('F' . $row, $product->name);
            $sheet->setCellValue('G' . $row, $product->description);
            $sheet->setCellValue('H' . $row, $product->category->name);
            $sheet->setCellValue('I' . $row, $unit->name); // Nombre de la unidad
            $sheet->setCellValue('J' . $row, '    ' . number_format($unit->price, 2) . ' BS.'); // Precio
            $sheet->setCellValue('K' . $row, $unit->stock); // Stock
            $sheet->getStyle('E' . $row . ':K' . $row)->getFont()->setSize(18);
            $sheet->getStyle('E' . $row . ':K' . $row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $row++;
            $num++;
        }
    }

    foreach (range('E', 'K') as $col) {
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

public function createUnit(Request $request)
{
    return view('units.create'); // Cargar vista del modal para crear
}

// Almacena una nueva unidad en la base de datos
public function unitsStore(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:255',
    ]);

    Unit::create($request->all());

    return redirect()->route('products.index')->with('success', 'Unidad creada exitosamente.');
}

// Muestra el modal para editar una unidad existente
public function unitsEdit($id)
{
    $unit = Unit::findOrFail($id);
    return view('units.edit', compact('unit')); // Cargar vista del modal para editar
}

// Actualiza una unidad existente en la base de datos
public function unitsUpdate(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:255',
    ]);

    $unit = Unit::findOrFail($id);
    $unit->update($request->all());

    return redirect()->route('products.index')->with('success', 'Unidad actualizada exitosamente.');
}
}
