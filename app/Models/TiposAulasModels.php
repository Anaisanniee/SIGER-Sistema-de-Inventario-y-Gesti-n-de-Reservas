<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TiposAulasModels extends Model
{
    protected $table = 'tipos_aulas'; 
    protected $primaryKey = 'tip_aula_id';

    
    protected $fillable = ['tip_aula_nombre', 'tip_aula_descripcion']; 
    
    
}