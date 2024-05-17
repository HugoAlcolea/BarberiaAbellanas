<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barbero extends Model
{
    protected $fillable = [
        'nombre', 'descripcion', 'imagen', 'horario'
    ];
}
