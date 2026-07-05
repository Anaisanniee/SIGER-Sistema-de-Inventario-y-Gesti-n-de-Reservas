<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AulasModels extends Model
{
    use SoftDeletes;

    protected $table = 'aulas';
    protected $primaryKey = 'aula_id';

    protected $fillable = [
        'aula_foto',
        'aula_nombre',
        'aula_capacidad',
        'aula_estado',
        'aula_reservable',
        'tip_aula_id',
        'aula_motivo_baja'
    ];

    /**
     * Relación con TiposAulasModels.
     * Aunque el modelo sea plural, el método se llama en singular 
     * porque un aula solo pertenece a UN tipo de aula.
     */
    public function tipoAula()
    {
        return $this->belongsTo(TiposAulasModels::class, 'tip_aula_id', 'tip_aula_id');
    }
}
