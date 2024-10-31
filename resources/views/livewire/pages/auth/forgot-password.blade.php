<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = '';

    /**
     * Enviar un enlace de restablecimiento de contraseña al correo proporcionado.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        // Enviar el enlace de restablecimiento de contraseña al usuario. Si el envío
        // es exitoso, mostraremos un mensaje de confirmación. De lo contrario, 
        // se indicará un error al usuario.
        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));
            return;
        }

        $this->reset('email');
        session()->flash('status', __($status));
    }
}; 
?>

<div>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('¿Olvidaste tu contraseña? No te preocupes. Solo indícanos tu dirección de correo electrónico y te enviaremos un enlace para que puedas restablecerla.') }}
    </div>

    <!-- Estado de la sesión -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="sendPasswordResetLink">
        <!-- Dirección de correo electrónico -->
        <div>
            <x-input-label for="email" :value="__('Correo Electrónico')" />
            <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Enviar enlace de restablecimiento de contraseña') }}
            </x-primary-button>
        </div>
    </form>
</div>
