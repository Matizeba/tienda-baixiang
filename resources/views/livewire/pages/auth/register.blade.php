<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $first_surname = ''; 
    public string $second_surname = ''; 
    public string $ci = '';
    public string $phone = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Manejar una solicitud de registro entrante.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'first_surname' => ['required', 'string', 'max:255'], // Validación del primer apellido
            'second_surname' => ['nullable', 'string', 'max:255'], // Validación del segundo apellido
            'ci' => ['required', 'string', 'unique:'.User::class, 'max:255'], // Validación de la cédula de identidad
            'phone' => ['nullable', 'string', 'max:15'], // Validación del teléfono
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        
        if (User::count() === 1) {
            $user->role = 0;
            $user->save();
        } else {
            $user->role = 2;
            $user->save();
        }

        event(new Registered($user));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
};
?>

<div>
    <form wire:submit="register">
        <!-- Nombre -->
        <div>
            <x-input-label for="name" :value="__('Nombre')" />
            <x-text-input wire:model="name" id="name" class="block mt-1 w-full" type="text" name="name" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Primer Apellido -->
        <div class="mt-4">
            <x-input-label for="first_surname" :value="__('Primer Apellido')" />
            <x-text-input wire:model="first_surname" id="first_surname" class="block mt-1 w-full" type="text" name="first_surname" required />
            <x-input-error :messages="$errors->get('first_surname')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="second_surname" :value="__('Segundo Apellido (opcional)')" />
            <x-text-input wire:model="second_surname" id="second_surname" class="block mt-1 w-full" type="text" name="second_surname" />
            <x-input-error :messages="$errors->get('second_surname')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="ci" :value="__('Cédula de Identidad')" />
            <x-text-input wire:model="ci" id="ci" class="block mt-1 w-full" type="text" name="ci" required />
            <x-input-error :messages="$errors->get('ci')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="phone" :value="__('Teléfono (opcional)')" />
            <x-text-input wire:model="phone" id="phone" class="block mt-1 w-full" type="text" name="phone" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" :value="__('Correo Electrónico')" />
            <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Contraseña')" />
            <x-text-input wire:model="password" id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" />
            <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}" wire:navigate>
                {{ __('¿Ya estás registrado?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Registrarse') }}
            </x-primary-button>
        </div>
    </form>
</div>
