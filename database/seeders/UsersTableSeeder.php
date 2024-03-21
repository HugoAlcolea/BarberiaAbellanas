<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\CustomUser;
use App\Models\StatsUser;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // Crear un usuario normal
        $user = CustomUser::create([
            'id' => 26, 
            'name' => 'hugo',
            'surname' => 'alcolea',
            'username' => 'hugoalcolea',
            'phone' => '696045259',
            'date_of_birth' => '2004-10-06',
            'gender' => 'hombre',
            'email' => 'hugo@gmail.com',
            'password' => Hash::make('hugoalcolea'),
            'confirm_password' => Hash::make('hugoalcolea'),
            'profile_image' => 'hugoalcolea.jpg', // Solo el nombre del archivo
            'is_admin' => false,
        ]);

        StatsUser::create([
            'user_id' => $user->id,
            'haircuts' => 26,
            'points' =>696969,
        ]);


        $pueva = CustomUser::create([
            'id' => 2, 
            'name' => 'pueva',
            'surname' => 'definiva',
            'username' => 'puevadefiniva',
            'phone' => '123456789',
            'date_of_birth' => '2000-01-01',
            'gender' => 'hombre',
            'email' => 'puevadefiniva@gmail.com',
            'password' => Hash::make('puevadefiniva'),
            'confirm_password' => Hash::make('puevadefiniva'),
            'profile_image' => 'default.jpg', // Solo el nombre del archivo
            'is_admin' => false,
        ]);

        StatsUser::create([
            'user_id' => $pueva->id,
            'haircuts' => 1,
            'points' =>1,
        ]);


        $admin = CustomUser::create([
            'id' => 1,
            'name' => 'admin',
            'surname' => 'admin',
            'username' => 'admin',
            'phone' => '000000000',
            'date_of_birth' => '1000-01-01',
            'gender' => 'hombre',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'),
            'confirm_password' => Hash::make('admin'),
            'profile_image' => 'noelnovo.jpeg', // Solo el nombre del archivo
            'is_admin' => true,
        ]);

        StatsUser::create([
            'user_id' => $admin->id,
            'haircuts' => 1000,
            'points' => 0,
        ]);

        // Crear usuarios adicionales con datos inventados
        $this->createUser('Dumas', 'Dumas', 'dumasdumas', '555555555', '1980-05-15', 'hombre', 'dumas@dumas.com', 'dumasdumas.jpg', false, 5, 10);
        $this->createUser('John', 'Doe', 'john.doe', '123456789', '1990-12-25', 'hombre', 'john@doe.com', 'admin.jpeg', false, 10, 20);
        $this->createUser('Jane', 'Doe', 'jane.doe', '987654321', '1985-08-10', 'mujer', 'jane@doe.com', 'default.jpg', false, 15, 30);
        $this->createUser('Bob', 'Smith', 'bob.smith', '555123456', '1975-03-20', 'hombre', 'bob@smith.com', 'hugoalcolea.jpg', false, 20, 40);
    }

    private function createUser($name, $surname, $username, $phone, $date_of_birth, $gender, $email, $profile_image, $is_admin, $haircuts, $points)
    {
        $user = CustomUser::create([
            'name' => $name,
            'surname' => $surname,
            'username' => $username,
            'phone' => $phone,
            'date_of_birth' => $date_of_birth,
            'gender' => $gender,
            'email' => $email,
            'password' => Hash::make($username), // Usando el nombre de usuario como contraseña temporal
            'confirm_password' => Hash::make($username),
            'profile_image' => $profile_image,
            'is_admin' => $is_admin,
        ]);

        StatsUser::create([
            'user_id' => $user->id,
            'haircuts' => $haircuts,
            'points' => $points,
        ]);
    }
}
