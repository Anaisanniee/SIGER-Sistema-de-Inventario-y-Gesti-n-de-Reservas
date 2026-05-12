<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AulasModels extends Model
{

    protected $table = 'AULAS'; 

    // 1. Nombre de la llave primaria
    protected $primaryKey = 'AULA_ID';

    // 2. Campos que se pueden llenar (Mass Assignment)
    protected $fillable = [
        'AULA_NOMBRE',
        'AULA_CAPACIDAD',
        'AULA_ESTADO',
        'AULA_RESERVABLE',
        'TIP_AULA_ID'
    ];

    // Relación inversa: Un aula tiene muchos activos
    public function activos()
    {
        return $this->hasMany(ActivosModels::class, 'aula_id', 'AULA_ID');
    }
}
