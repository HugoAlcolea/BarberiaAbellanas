<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CustomUser;


class Cita extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'fecha',
        'hora',
        'codigo',
        'dinero_cobrado',
    ];

    public function usuario()
    {
        return $this->belongsTo(CustomUser::class, 'user_id');
    }
}
