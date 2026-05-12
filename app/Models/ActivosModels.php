<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActivosModels extends Model
{
    use HasFactory;

    protected $table = 'activos';
    protected $primaryKey = 'act_id';

    protected $fillable = [
        'act_nombre',
        'act_serial',
        'act_foto',
        'act_estado_fisico',
        'act_reservable',
        'act_fecha_ingreso',
        'aula_id',
        'cate_id'
    ];

    // Relación para ver el nombre del aula en la Ficha Técnica
    public function aula()
    {
        // Conecta el aula_id del activo con el AULA_ID de la tabla AULAS
        return $this->belongsTo(AulasModels::class, 'aula_id', 'AULA_ID');
    }
}