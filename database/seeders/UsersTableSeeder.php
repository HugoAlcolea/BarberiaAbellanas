<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\CustomUser;
use App\Models\StatsUser;
use App\Models\Barbero;
use App\Models\Servicio;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear usuarios
        $hugo = CustomUser::create([
            'name' => 'hugo',
            'surname' => 'alcolea',
            'username' => 'hugoalcolea',
            'phone' => '696045259',
            'date_of_birth' => '2004-10-06',
            'gender' => 'hombre',
            'email' => 'hugo@gmail.com',
            'password' => Hash::make('hugoalcolea'),
            'confirm_password' => Hash::make('hugoalcolea'),
            'profile_image' => 'hugoalcolea.jpg',
            'is_admin' => false,
            'google_id' => null,
        ]);

        StatsUser::create([
            'user_id' => $hugo->id,
            'haircuts' => 26,
            'points' => 696969,
        ]);

        $pueva = CustomUser::create([
            'name' => 'pueva',
            'surname' => 'definiva',
            'username' => 'puevadefiniva',
            'phone' => '123456789',
            'date_of_birth' => '2000-01-01',
            'gender' => 'hombre',
            'email' => 'puevadefiniva@gmail.com',
            'password' => Hash::make('puevadefiniva'),
            'confirm_password' => Hash::make('puevadefiniva'),
            'profile_image' => 'default.jpg',
            'is_admin' => false,
            'google_id' => null,
        ]);

        StatsUser::create([
            'user_id' => $pueva->id,
            'haircuts' => 1,
            'points' => 1,
        ]);

        $admin = CustomUser::create([
            'name' => 'admin',
            'surname' => 'admin',
            'username' => 'admin',
            'phone' => '000000000',
            'date_of_birth' => '1000-01-01',
            'gender' => 'hombre',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'),
            'confirm_password' => Hash::make('admin'),
            'profile_image' => 'noelnovo.jpeg',
            'is_admin' => true,
            'google_id' => null,
        ]);

        StatsUser::create([
            'user_id' => $admin->id,
            'haircuts' => 1000,
            'points' => 0,
        ]);

        $dumas = CustomUser::create([
            'name' => 'Dumas',
            'surname' => 'Dumas',
            'username' => 'dumasdumas',
            'phone' => '555555555',
            'date_of_birth' => '1980-05-15',
            'gender' => 'hombre',
            'email' => 'dumas@dumas.com',
            'password' => Hash::make('dumasdumas'),
            'confirm_password' => Hash::make('dumasdumas'),
            'profile_image' => 'dumasdumas.jpg',
            'is_admin' => false,
            'google_id' => null,
        ]);

        StatsUser::create([
            'user_id' => $dumas->id,
            'haircuts' => 5,
            'points' => 10,
        ]);

        // Crear barberos
        $barbero1 = Barbero::create([
            'nombre' => 'Barbero 1',
            'descripcion' => 'Descripción del barbero 1',
            'imagen' => 'barbero1.jpg',
            'horario' => 'Horario del barbero 1',
        ]);

        $barbero2 = Barbero::create([
            'nombre' => 'Barbero 2',
            'descripcion' => 'Descripción del barbero 2',
            'imagen' => 'barbero2.jpg',
            'horario' => 'Horario del barbero 2',
        ]);

        // Crear servicios
        $cortePelo = Servicio::create([
            'nombre' => 'Corte de pelo',
            'descripcion' => 'Descripción del servicio de corte de pelo',
            'precio' => 10.00,
            'duracion' => 30,
        ]);

        $corteBarba = Servicio::create([
            'nombre' => 'Corte de barba',
            'descripcion' => 'Descripción del servicio de corte de barba',
            'precio' => 15.00,
            'duracion' => 45,
        ]);

        $cortePeloBarba = Servicio::create([
            'nombre' => 'Corte de pelo y barba',
            'descripcion' => 'Descripción del servicio de corte de pelo y barba',
            'precio' => 20.00,
            'duracion' => 60,
        ]);
    }
}
