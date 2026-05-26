<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
public function run(): void
{
    // 1. Primero sembramos los roles obligatoriamente
    $this->call(RoleSeeder::class);

    // 2. Creamos el usuario Administrador base con el formato de la documentación
    \App\Models\User::create([
        'USU_CEDULA' => '123456789',
        'USU_PRIMER_NOMBRE' => 'Admin',
        'USU_SEGUNDO_NOMBRE' => null,
        'USU_PRIMER_APELLIDO' => 'SIGER',
        'USU_SEGUNDO_APELLIDO' => null,
        'USU_CORREO' => 'admin@siger.edu.co',
        'USU_CONTRASEÑA' => 'admin123', // El modelo se encarga de encriptarla automáticamente
        'USU_ESTADO' => 'Activo',
        'ROL_ID' => 1, // ID 1 = Rectora / Admin
    ]);
}
}
