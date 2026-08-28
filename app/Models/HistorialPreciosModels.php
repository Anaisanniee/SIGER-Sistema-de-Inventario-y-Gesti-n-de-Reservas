<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialPreciosModels extends Model
{
    protected $table = 'historial_precios';
    protected $primaryKey = 'his_pre_id';
    
    protected $fillable = [
        'his_pre_fecha_cambio',
        'his_pre_valor',
        'his_pre_motivo',
        'act_id'
    ];

    // Relación: Un historial de precio pertenece a un Activo
    public function activo()
    {
        return $this->belongsTo(ActivosModels::class, 'act_id', 'act_id');
    }
}