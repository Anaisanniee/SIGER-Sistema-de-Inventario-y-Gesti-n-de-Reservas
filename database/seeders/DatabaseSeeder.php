<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear los 3 Roles obligatorios con sus slugs
        $rolSecretaria = Role::firstOrCreate(
            ['slug' => 'secretaria'],
            ['name' => 'Secretaria']
        );

        $rolRectora = Role::firstOrCreate(
            ['slug' => 'rectora'],
            ['name' => 'Rectora']
        );

        $rolDocente = Role::firstOrCreate(
            ['slug' => 'docente'],
            ['name' => 'Docente']
        );

        // 2. Crear Usuario: Secretaría
        User::firstOrCreate(
            ['USU_CEDULA' => '1000000001'],
            [
                'USU_CORREO'          => 'secretaria@siger.com',
                'USU_PRIMER_NOMBRE'   => 'Ana',
                'USU_PRIMER_APELLIDO' => 'Secretaria',
                'USU_CONTRASEÑA'      => Hash::make('123456'),
                'ROL_ID'              => $rolSecretaria->id,
                'USU_ESTADO'          => 'Activo',
            ]
        );

        // 3. Crear Usuario: Rectora
        User::firstOrCreate(
            ['USU_CEDULA' => '1000000002'],
            [
                'USU_CORREO'          => 'rectora@siger.com',
                'USU_PRIMER_NOMBRE'   => 'Maria',
                'USU_PRIMER_APELLIDO' => 'Rectora',
                'USU_CONTRASEÑA'      => Hash::make('123456'),
                'ROL_ID'              => $rolRectora->id,
                'USU_ESTADO'          => 'Activo',
            ]
        );

        // 4. Crear Usuario: Docente
        User::firstOrCreate(
            ['USU_CEDULA' => '1000000003'],
            [
                'USU_CORREO'          => 'docente@siger.com',
                'USU_PRIMER_NOMBRE'   => 'Carlos',
                'USU_PRIMER_APELLIDO' => 'Docente',
                'USU_CONTRASEÑA'      => Hash::make('123456'),
                'ROL_ID'              => $rolDocente->id,
                'USU_ESTADO'          => 'Activo',
            ]
        );
    }
}