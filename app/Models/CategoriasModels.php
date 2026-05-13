<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriasModels extends Model
{
    protected $table = 'categorias';

    // Asegúrate de que coincida con el nombre exacto en tu base de datos MariaDB
    protected $primaryKey = 'cate_id'; 
    
    // Si tu tabla no tiene columnas de fecha, añade esto:
    public $timestamps = true; 
}