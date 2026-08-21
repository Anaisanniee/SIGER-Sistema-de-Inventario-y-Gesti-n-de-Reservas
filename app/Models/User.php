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
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id'; /*cambie USU_ID por user_id para que se reconozca este ultimo como primarykey*/

    protected $keyType = 'int'; /*le dice a laravel que la clave primaria es un entero*/

    public $incrementing = true; //dice a laravel que es un A_I

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'USU_CONTRASEÑA' => 'hashed', 
        ];
    }


    public function role()
    {
        return $this->belongsTo(Role::class, 'ROL_ID', 'id');
    }

    public function getAuthIdentifierName()
    {
        return 'user_id';
        
    }

    public function getAuthIdentifier()
    {
        return $this->user_id;
    }

    
    public function getAuthPassword()
    {
        return $this->USU_CONTRASEÑA;
    }
}