<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
   
    public function index(Request $request)
    {
        $sortField = $request->input('sort_field', 'id');
        $sortDirection = $request->input('sort_direction', 'asc');

        $users = User::where('role', 1)
                    ->orderBy($sortField, $sortDirection)
                    ->paginate(10);

        return view('livewire/users.index', compact('users', 'sortField', 'sortDirection'));
    }

    
    public function create()
    {
        return view('livewire/users.create');
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 1, 
        ]);

        return redirect()->route('users.index')->with('success', 'Empleado creado exitosamente.');
    }

    
    public function edit(User $user)
    {
        return view('livewire/users.edit', compact('user'));
    }

    
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('users.index')->with('success', 'Empleado actualizado correctamente.');
    }

    
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Empleado eliminado correctamente.');
    }

    
    public function indexClients(Request $request)
    {
        $sortField = $request->input('sort_field', 'id');
        $sortDirection = $request->input('sort_direction', 'asc');

        $users = User::where('role', 2)
                    ->orderBy($sortField, $sortDirection)
                    ->paginate(10); 

        return view('livewire/users2.index', compact('users', 'sortField', 'sortDirection'));
    }

   
    public function createClient()
    {
        return view('livewire/users2.create');
    }

    
    public function storeClient(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 2,
        ]);

        return redirect()->route('users2.index')->with('success', 'Cliente creado exitosamente.');
    }

    
    public function editClient(User $user)
    {
        return view('livewire/users2.edit', compact('user'));
    }

    
    public function updateClient(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('users2.index')->with('success', 'Cliente actualizado correctamente.');
    }

    
    public function destroyClient(User $user)
    {
        $user->delete();
        return redirect()->route('users2.index')->with('success', 'Cliente eliminado correctamente.');
    }
}
