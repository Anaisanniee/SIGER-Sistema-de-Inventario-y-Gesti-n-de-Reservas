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
    
        return $this->belongsTo(AulasModels::class, 'aula_id', 'aula_id');
    }
    public function categoria()
    {
    // Relación: Un activo pertenece a una categoría
    // El segundo parámetro es la llave foránea en tu tabla activos
    // El tercer parámetro es la llave primaria en tu tabla categorías
        return $this->belongsTo(CategoriasModels::class, 'cate_id', 'cate_id');
    }
}