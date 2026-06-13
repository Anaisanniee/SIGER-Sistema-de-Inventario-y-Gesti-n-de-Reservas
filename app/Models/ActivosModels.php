<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes; // Importante para el SOFTDELETES de tu tabla

class ActivosModels extends Model
{
    use HasFactory, SoftDeletes; // Habilitamos las fábricas y el borrado lógico

    // Nombre de la tabla en tu base de datos
    protected $table = 'activos';

    // Llave primaria de la tabla
    protected $primaryKey = 'act_id';

    // Campos habilitados para asignación masiva (Mass Assignment)
    protected $fillable = [
        'act_foto',
        'act_nombre',
        'act_serial',
        'act_marca',          // Nuevo campo añadido
        'act_estado_fisico',
        'act_reservable',
        'act_fecha_ingreso',
        'act_motivo_baja',    // Nuevo campo añadido
        'aula_id',
        'cate_id'
    ];

    /**
     * Propiedad para indicarle a Eloquent que trate 'deleted_at' como una instancia de Carbon/Date.
     * Laravel por defecto busca 'deleted_at', que se mapea con tu columna 'SOFTDELETES' de la migración.
     */
    protected $dates = ['deleted_at'];

    /**
     * Relación: Un activo pertenece a un Aula (Muchos a Uno)
     * El segundo parámetro es la llave foránea en 'activos' y el tercero es la llave primaria en 'aulas'
     */
    public function aula()
    {
        return $this->belongsTo(AulasModels::class, 'aula_id', 'aula_id');
    }

    /**
     * Relación: Un activo pertenece a una Categoría (Muchos a Uno)
     * El segundo parámetro es la llave foránea en 'activos' y el tercero es la llave primaria en 'categorias'
     */
    public function categoria()
    {
        return $this->belongsTo(CategoriasModels::class, 'cate_id', 'cate_id');
    }
}