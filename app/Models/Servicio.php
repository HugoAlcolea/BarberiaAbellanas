<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $fillable = [
        'nombre', 'descripcion', 'precio', 'duracion', // Asegúrate de incluir todas las columnas de la tabla 'servicios'
    ];
}
