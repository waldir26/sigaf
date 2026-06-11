<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        Usuario::updateOrCreate(
            ['correo' => 'admin@fuslamo.com'],
            [
                'nombre' => 'Admin',
                'apellido' => 'Sistema',
                'usuario' => 'admin',
                'contrasena' => Hash::make('admin123'),
                'rol' => 'admin',
                'estado' => 'activo'
            ]
        );
    }
}
