<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserRegistered;

class ClientController extends Controller
{
    public function indexClient(Request $request)
    {
        $sortField = $request->input('sort_field', 'id');
        $sortDirection = $request->input('sort_direction', 'asc');
        
        $users = User::whereIn('role', [3])
        ->orderBy($sortField, $sortDirection)
        ->get();


        return view('livewire/clients.index', compact('users', 'sortField', 'sortDirection'));
    }

    public function createCLient()
    {
        return view('livewire/clients.create');
    }

    public function editClient(User $user)
    {
        return view('livewire/clients.edit', compact('user'));
    }

    public function updateClient(Request $request, User $user)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'
            ],
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
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
            
        ]);

        return redirect()->route('clients.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $user)
    {
            $user->delete();
            return redirect()->route('clients.index')->with('success', 'cliente eliminado permanentemente.');
    }


    public function storeClient(Request $request)
{
    $request->validate([
        'name' => [
            'required',
            'string',
            'max:255',
            'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'
        ],
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ], [
        'name.required' => 'El nombre es obligatorio.',
        'name.string' => 'El nombre debe ser una cadena de texto.',
        'name.max' => 'El nombre no puede tener más de 255 caracteres.',
        'name.regex' => 'El nombre solo puede contener letras y espacios.',
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
        'email' => $request->email,
        'password' => Hash::make($password),
        'role' => 3,
    ]);

    // Enviar el correo electrónico al usuario con la contraseña generada
    \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\UserRegistered($user, $password));

    return redirect()->route('clients.index')->with('success', 'Usuario creado exitosamente.');
}

    public function toggleClientStatus($id)
    {
        $user = User::findOrFail($id);
        $user->status = !$user->status; // Alternar el estado
        $user->save();

        return redirect()->route('clients.index')->with('success', 'Estado del usuario actualizado.');
    }
}
