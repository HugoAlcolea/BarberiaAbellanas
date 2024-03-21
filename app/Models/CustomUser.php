<?php

// En el modelo CustomUser.php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomUser extends Model implements Authenticatable
{
    use HasFactory, AuthenticatableTrait; 

    protected $table = 'custom_users';

    protected $fillable = [
        'name', 'surname', 'username', 'phone', 'date_of_birth', 'email', 'password', 'gender', 'profile_image',
    ];

    public function stats()
    {
        return $this->hasOne(StatsUser::class, 'user_id');
    }

    /**
     * Verifica si el usuario es administrador.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->is_admin;
    }
}
