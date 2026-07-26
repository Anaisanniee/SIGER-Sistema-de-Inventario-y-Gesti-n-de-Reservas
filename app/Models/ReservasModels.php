<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservasModels extends Model
{
    protected $table = 'reservas';

    protected $primaryKey = 'res_id';

    // Como sí tiene created_at y updated_at, dejamos esto en true
    public $timestamps = true;

    protected $fillable = [
        'usu_id',
        'recurso_id',
        'tipo_recurso',
        'res_fecha_inicio',
        'res_fecha_fin',
        'res_hora_inicio',
        'res_hora_fin',
        'res_motivo',
        'aula_uso',
        'res_estado_reserva',
        'res_fecha_creacion',
    ];

    // Relación con el usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usu_id', 'usu_id');
    }

    // Relación con los detalles de reserva
    public function detalles()
    {
        return $this->hasMany(DetallesReservasModels::class, 'res_id', 'res_id');
    }
}