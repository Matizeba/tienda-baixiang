<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserRegistered;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class UserController extends Controller
{
    public function index(Request $request)
{
    
    $statusFilter = $request->input('status', 'all');

    // Filtrar por roles 1 y 2
    $query = User::whereIn('role', [1, 2]);

    // Filtrar según el estado
    if ($statusFilter === 'enabled') {
        $query->where('status', 1);
    } elseif ($statusFilter === 'disabled') {
        $query->where('status', 0);
    }

    // Obtener usuarios sin ordenamiento
    $users = $query->get();
    $users = $query->paginate(5);


    return view('livewire.users.index', compact('users', 'statusFilter'));
}


    public function create()
    {
        return view('livewire/users.create');
    }

    public function edit(User $user)
    {
        return view('livewire/users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'
            ],
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|string',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',
            'name.regex' => 'El nombre solo puede contener letras y espacios.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser una dirección de correo válida.',
            'email.max' => 'El correo electrónico no puede tener más de 255 caracteres.',
            'email.unique' => 'El correo electrónico ya está en uso.',
            'role.required' => 'El rol es obligatorio.',
            'role.string' => 'El rol debe ser una cadena de texto.',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuario eliminado permanentemente.');
    }


    public function store(Request $request)
{
    $request->validate([
        'name' => [
            'required',
            'string',
            'max:255',
            'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ]+( [a-zA-ZáéíóúÁÉÍÓÚñÑ]+)?$/' // Permite un solo espacio para separar dos nombres
        ],
        'first_surname' => [
            'required',
            'string',
            'max:255',
            'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ]+$/' // Sin espacios en el primer apellido
        ],
        'second_surname' => [
            'nullable', // Cambiar 'required' a 'nullable' para hacerlo opcional
            'string',
            'max:255',
            'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ]*$/' // Sin espacios en el segundo apellido
        ],
        'ci' => [
            'required',
            'string',
            'max:20', // O el tamaño que consideres apropiado
            'regex:/^[0-9]{1,20}$/',
            'unique:users' // Añadir validación para que el CI sea único
        ],
        'phone' => [
            'required',
            'string',
            'max:15', // O el tamaño que consideres apropiado
            'regex:/^[0-9\s\-\+\(\)]*$/',
            'unique:users' // Añadir validación para que el teléfono sea único
        ],
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'role' => 'required|string',
    ], [
        'name.required' => 'El nombre es obligatorio.',
        'name.string' => 'El nombre debe ser una cadena de texto.',
        'name.max' => 'El nombre no puede tener más de 255 caracteres.',
        'name.regex' => 'El nombre solo puede contener letras y un único espacio para separar nombres.',
        'first_surname.required' => 'El primer apellido es obligatorio.',
        'first_surname.string' => 'El primer apellido debe ser una cadena de texto.',
        'first_surname.max' => 'El primer apellido no puede tener más de 255 caracteres.',
        'first_surname.regex' => 'El primer apellido solo puede contener letras sin espacios.',
        'second_surname.nullable' => 'El segundo apellido es opcional.',
        'second_surname.string' => 'El segundo apellido debe ser una cadena de texto.',
        'second_surname.max' => 'El segundo apellido no puede tener más de 255 caracteres.',
        'second_surname.regex' => 'El segundo apellido solo puede contener letras sin espacios.',
        'ci.required' => 'La cédula de identidad es obligatoria.',
        'ci.string' => 'La cédula de identidad debe ser una cadena de texto.',
        'ci.max' => 'La cédula de identidad no puede tener más de 20 caracteres.',
        'ci.regex' => 'La cédula de identidad solo puede contener números.',
        'ci.unique' => 'La cédula de identidad ya está en uso.',
        'phone.required' => 'El teléfono es obligatorio.',
        'phone.string' => 'El teléfono debe ser una cadena de texto.',
        'phone.max' => 'El teléfono no puede tener más de 15 caracteres.',
        'phone.regex' => 'El teléfono solo puede contener números y caracteres especiales permitidos.',
        'phone.unique' => 'El teléfono ya está en uso.',
        'email.required' => 'El correo electrónico es obligatorio.',
        'email.string' => 'El correo electrónico debe ser una cadena de texto.',
        'email.email' => 'El correo electrónico debe ser una dirección de correo válida.',
        'email.max' => 'El correo electrónico no puede tener más de 255 caracteres.',
        'email.unique' => 'El correo electrónico ya está en uso.',
        'password.required' => 'La contraseña es obligatoria.',
        'password.string' => 'La contraseña debe ser una cadena de texto.',
        'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        'password.confirmed' => 'La confirmación de la contraseña no coincide.',
        'role.required' => 'El rol es obligatorio.',
        'role.string' => 'El rol debe ser una cadena de texto.',
    ]);

    // Definir la contraseña antes de crear el usuario
    $password = $request->password;

    // Crear el usuario
    $user = User::create([
        'name' => $request->name,
        'first_surname' => $request->first_surname,
        'second_surname' => $request->second_surname, // Este campo puede ser nulo
        'ci' => $request->ci,
        'phone' => $request->phone,
        'email' => $request->email,
        'password' => Hash::make($password),
        'role' => $request->role,
    ]);


    \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\UserRegistered($user, $password));

    return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente.');
}



    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->status = !$user->status; // Alternar el estado
        $user->save();

        return redirect()->route('users.index')->with('success', 'Estado del usuario actualizado.');
    }

    public function exportToExcel(Request $request)
    {
        // Obtener los usuarios
        $users = User::whereIn('role', [1, 2])
            ->orderBy('id', 'asc')
            ->get();

        // Crear una nueva hoja de cálculo
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Insertar el logo en E2
        $logoPath = public_path('images/logo.png');
        $logo = new Drawing();
        $logo->setName('Logo');
        $logo->setDescription('Logo');
        $logo->setPath($logoPath);
        $logo->setHeight(120); // Ajusta la altura del logo según sea necesario
        $logo->setCoordinates('E2'); // Establece la celda en la que se ubicará el logo
        $logo->setWorksheet($sheet);

        // Título y subtítulo
        $sheet->mergeCells('E2:I2'); // Ajustar el rango para desplazar el contenido
        $sheet->setCellValue('E2', 'Tienda Baixiang');
        $sheet->getStyle('E2')->getFont()->setSize(40)->setBold(true); // Aumentar tamaño de texto
        $sheet->getStyle('E2')->getFont()->getColor()->setARGB('FFFFFF'); // Color del texto blanco
        $sheet->getStyle('E2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E2:I2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('d32f2f'); // Color de fondo

        $sheet->mergeCells('E3:I3'); // Ajustar el rango para desplazar el contenido
        $sheet->setCellValue('E3', 'Lista de Trabajadores');
        $sheet->getStyle('E3')->getFont()->setSize(30)->setBold(true); // Aumentar tamaño de texto
        $sheet->getStyle('E3')->getFont()->getColor()->setARGB('FFFFFF'); // Color del texto blanco
        $sheet->getStyle('E3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E3:I3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('d32f2f'); // Color de fondo

        // Ajustar el ancho de las columnas para los encabezados
        $headers = ['N°', 'Nombre', 'Correo Electrónico', 'Rol', 'Estado'];
        $colLetters = ['E', 'F', 'G', 'H', 'I'];

        foreach ($headers as $index => $header) {
            $cell = $colLetters[$index] . '5'; // Ajustar la fila de encabezado a la fila 5
            $sheet->setCellValue($cell, $header);

            // Aplicar formato de encabezado
            $sheet->getStyle($cell)->getFont()->setBold(true)->setSize(20); // Tamaño de texto en encabezado
            $sheet->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($cell)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00'); // Color de fondo amarillo
            $sheet->getStyle($cell)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        }

        // Agregar datos de usuarios
        $row = 6; // Iniciar en la fila 6
        $num = 1; // Iniciar enumeración en 1
        foreach ($users as $user) {
            $sheet->setCellValue('E' . $row, $num); // Número de enumeración
            $sheet->setCellValue('F' . $row, $user->name);
            $sheet->setCellValue('G' . $row, $user->email);
            $sheet->setCellValue('H' . $row, $user->role == 1 ? 'Administrador' : 'Vendedor');
            $sheet->setCellValue('I' . $row, $user->status ? 'Activo' : 'Inactivo');
            
            // Aplicar formato a las celdas de datos
            $sheet->getStyle('E' . $row . ':I' . $row)->getFont()->setSize(18); // Tamaño de texto en datos
            $sheet->getStyle('E' . $row . ':I' . $row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            $row++;
            $num++;
        }

        // Ajustar automáticamente el ancho de las columnas
        foreach (range('E', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Crear el archivo Excel
        $writer = new Xlsx($spreadsheet);
        $filename = 'usuarios.xlsx';
        
        // Configurar los headers para la descarga del archivo
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"'); 
        header('Cache-Control: max-age=0');
        
        // Enviar el archivo al navegador
        $writer->save('php://output');
        exit;

        
    }

}
