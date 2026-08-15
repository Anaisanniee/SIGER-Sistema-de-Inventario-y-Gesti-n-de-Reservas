<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Insertar los 3 Roles con su campo 'slug'
        $rolSecretaria = Role::firstOrCreate(
            ['name' => 'Secretaria'],
            ['slug' => 'secretaria']
        );

        $rolRectora = Role::firstOrCreate(
            ['name' => 'Rectora'],
            ['slug' => 'rectora']
        );

        $rolDocente = Role::firstOrCreate(
            ['name' => 'Docente'],
            ['slug' => 'docente']
        );

        // 2. Crear Usuario: Secretaría
        User::firstOrCreate(
            ['USU_CORREO' => 'secretaria@siger.com'],
            [
                'USU_CEDULA'           => '1000000001',
                'USU_PRIMER_NOMBRE'    => 'Ana',
                'USU_PRIMER_APELLIDO'  => 'Secretaria',
                'USU_CONTRASEÑA'       => Hash::make('123456'),
                'ROL_ID'               => $rolSecretaria->id,
                'USU_ESTADO'           => 'Activo',
            ]
        );

        // 3. Crear Usuario: Rectora
        User::firstOrCreate(
            ['USU_CORREO' => 'rectora@siger.com'],
            [
                'USU_CEDULA'           => '1000000002',
                'USU_PRIMER_NOMBRE'    => 'Maria',
                'USU_PRIMER_APELLIDO'  => 'Rectora',
                'USU_CONTRASEÑA'       => Hash::make('123456'),
                'ROL_ID'               => $rolRectora->id,
                'USU_ESTADO'           => 'Activo',
            ]
        );

        // 4. Crear Usuario: Docente
        User::firstOrCreate(
            ['USU_CORREO' => 'docente@siger.com'],
            [
                'USU_CEDULA'           => '1000000003',
                'USU_PRIMER_NOMBRE'    => 'Carlos',
                'USU_PRIMER_APELLIDO'  => 'Docente',
                'USU_CONTRASEÑA'       => Hash::make('123456'),
                'ROL_ID'               => $rolDocente->id,
                'USU_ESTADO'           => 'Activo',
            ]
        );
    }
}