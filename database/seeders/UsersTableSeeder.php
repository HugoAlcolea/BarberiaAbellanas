<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // Crear usuarios normales
        $this->createUser('hugo', 'alcolea', 'hugoalcolea', '696045259', '2004-10-06', 'hombre', 'hugo@gmail.com', 'hugoalcolea.jpg', false, 26, 696969);
        $this->createUser('pueva', 'definiva', 'puevadefiniva', '123456789', '2000-01-01', 'hombre', 'puevadefiniva@gmail.com', 'default.jpg', false, 1, 1);

        // Crear usuario administrador
        $this->createUser('admin', 'admin', 'admin', '000000000', '1000-01-01', 'hombre', 'admin@gmail.com', 'noelnovo.jpeg', true, 1000, 0);

        // Crear usuarios adicionales con datos inventados
        $this->createUser('Dumas', 'Dumas', 'dumasdumas', '555555555', '1980-05-15', 'hombre', 'dumas@dumas.com', 'dumasdumas.jpg', false, 5, 10);
        $this->createUser('John', 'Doe', 'john.doe', '123456789', '1990-12-25', 'hombre', 'john@doe.com', 'admin.jpeg', false, 10, 20);
        $this->createUser('Jane', 'Doe', 'jane.doe', '987654321', '1985-08-10', 'mujer', 'jane@doe.com', 'default.jpg', false, 15, 30);
        $this->createUser('Bob', 'Smith', 'bob.smith', '555123456', '1975-03-20', 'hombre', 'bob@smith.com', 'hugoalcolea.jpg', false, 20, 40);

        // Crear barberos
        $this->createBarbero('Barbero 1', 'Descripción del barbero 1', 'barbero1.jpg', 'Horario del barbero 1');
        // Agrega más barberos si lo deseas

        // Crear servicios
        $this->createServicio('Corte de pelo', 'Descripción del servicio de corte de pelo', 10.00, 30);
        // Agrega más servicios si lo deseas
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

    private function createBarbero($nombre, $descripcion, $imagen, $horario)
    {
        Barbero::create([
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'imagen' => $imagen,
            'horario' => $horario,
        ]);
    }

    private function createServicio($nombre, $descripcion, $precio, $duracion)
    {
        Servicio::create([
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'precio' => $precio,
            'duracion' => $duracion,
        ]);
    }
}
