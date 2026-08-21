<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role; // Importamos el modelo Role para poder buscar por slug
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

        // Buscamos los roles directamente por su slug para obtener sus IDs reales
        $rolRectora = Role::where('slug', 'rectora')->first();
        $rolSecretaria = Role::where('slug', 'secretaria')->first();

        // 2. Creamos el usuario Administrador base
        \App\Models\User::create([
            'USU_CEDULA' => '123456789',
            'USU_PRIMER_NOMBRE' => 'Admin',
            'USU_SEGUNDO_NOMBRE' => null,
            'USU_PRIMER_APELLIDO' => 'SIGER',
            'USU_SEGUNDO_APELLIDO' => null,
            'USU_CORREO' => 'admin@siger.edu.co',
            'USU_CONTRASEÑA' => 'admin123',
            'USU_ESTADO' => 'Activo',
            'ROL_ID' => $rolRectora->id, // Asigna dinámicamente el ID de Rectora
        ]);

        // 3. Creamos el usuario Secretaria (El Génesis)
        \App\Models\User::create([
            'USU_CEDULA' => '987654321',
            'USU_PRIMER_NOMBRE' => 'Ana',
            'USU_SEGUNDO_NOMBRE' => null,
            'USU_PRIMER_APELLIDO' => 'Secretaria',
            'USU_SEGUNDO_APELLIDO' => 'Pruebas',
            'USU_CORREO' => 'secretaria@siger.edu.co',
            'USU_CONTRASEÑA' => 'secretaria123',
            'USU_ESTADO' => 'Activo',
            'ROL_ID' => $rolSecretaria->id, // Asigna dinámicamente el ID de Secretaria
        ]);
    }
}