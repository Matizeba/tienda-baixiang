<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'first_surname', // Nuevo campo
        'second_surname', // Nuevo campo (opcional)
        'ci', // Nuevo campo
        'phone', // Nuevo campo (opcional)
        'email',
        'password',
        'role',
        'userid',
        'passwordUpdate', // Este campo ya existente también se puede incluir si es necesario
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    // Relación con las ventas donde el usuario es el cliente
    public function purchases()
    {
        return $this->hasMany(Sale::class, 'customer_id');
    }
}
