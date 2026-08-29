<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'USU_CEDULA', 
    'USU_PRIMER_NOMBRE', 
    'USU_SEGUNDO_NOMBRE', 
    'USU_PRIMER_APELLIDO', 
    'USU_SEGUNDO_APELLIDO', 
    'USU_CORREO', 
    'USU_CONTRASEÑA', 
    'USU_ESTADO', 
    'ROL_ID'
])]
#[Hidden(['USU_CONTRASEÑA', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Ajustado a minúsculas como lo muestra tu base de datos
    protected $primaryKey = 'usu_id'; 

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'ROL_ID', 'id');
    }

    public function getAuthPassword()
    {
        return $this->USU_CONTRASEÑA;
    }
}