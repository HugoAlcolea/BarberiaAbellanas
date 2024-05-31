<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class CustomUser extends Model implements Authenticatable
{
    use HasApiTokens, HasFactory, AuthenticatableTrait; 

    protected $table = 'custom_users';

    protected $fillable = [
        'name', 'surname', 'username', 'phone', 'date_of_birth', 'email', 'password', 'google_id', 'gender', 'profile_image', 'is_admin',
    ];

    public function stats()
    {
        return $this->hasOne(StatsUser::class, 'user_id');
    }

    public function isAdmin()
    {
        return $this->is_admin;
    }

    public function deleteUser($id)
    {
        try {
            $user = CustomUser::findOrFail($id);
            $user->delete();

            return response()->json(['message' => 'Usuario eliminado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al eliminar el usuario', 'error' => $e->getMessage()], 500);
        }
    }

    public function citas()
    {
        return $this->hasMany(Cita::class, 'user_id');
    }

    public function images()
    {
        return $this->hasMany(HaircutGallery::class, 'user_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($user) {
            $user->images()->each(function ($image) {
                \Storage::delete('public/haircut_gallery/' . $image->photo_name);
                $image->delete();
            });
        });
    }
}

