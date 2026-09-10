<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetallesReservasModels extends Model
{
    protected $table = 'detalles_reservas';

    protected $primaryKey = 'det_re_id';

    public $timestamps = true;

    protected $fillable = [
        'det_re_fecha_ini',
        'det_re_fecha_fin',
        'res_id',
        'act_id',
        'det_re_aula_destino_act',
        'aula_id',
    ];

    // Relación con la reserva principal (Cabecera)
    public function reserva()
    {
        return $this->belongsTo(ReservasModels::class, 'res_id', 'res_id');
    }

    // Relación con el activo (Añadimos withTrashed para que traiga los eliminados de la papelera)
    public function activo()
    {
        return $this->belongsTo(ActivosModels::class, 'act_id', 'act_id')->withTrashed();
    }

    // Relación con el aula (Para cuando se reserva un aula de forma independiente)
    public function aula()
    {
        return $this->belongsTo(AulasModels::class, 'aula_id', 'aula_id');
    }

    // Relación para el aula de destino cuando se le asigna un activo a un aula
    public function aulaDestino()
    {
        return $this->belongsTo(AulasModels::class, 'det_re_aula_destino_act', 'aula_id');
    }
}