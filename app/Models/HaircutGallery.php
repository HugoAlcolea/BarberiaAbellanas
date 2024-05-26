<?php

namespace App\Models;

use App\Models\Barbero;
use App\Models\EstilosDeCortes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HaircutGallery extends Model
{
    protected $table = 'haircut_gallery';
    
    protected $fillable = [
        'photo_name',
        'user_id',
        'barber_id',
        'hairstyle_id',
    ];

    public function barbero()
    {
        return $this->belongsTo(Barbero::class, 'barber_id');
    }

    public function estilo()
    {
        return $this->belongsTo(EstilosDeCortes::class, 'hairstyle_id');
    }
}
