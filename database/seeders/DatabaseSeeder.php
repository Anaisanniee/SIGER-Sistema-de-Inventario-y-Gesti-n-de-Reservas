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
        // 1. Crear los 3 Roles obligatorios para que la secretaría pueda asignarlos después
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

        User::firstOrCreate(
            ['USU_CEDULA' => '1000000001'], 
            [
                'USU_CORREO'          => 'secretaria@siger.edu.co', 
                'USU_PRIMER_NOMBRE'   => 'Secretaria',                    
                'USU_PRIMER_APELLIDO' => 'SIGER',               
                'USU_CONTRASEÑA'      => Hash::make('1000000001'), 
                'ROL_ID'              => $rolSecretaria->id,
                'USU_ESTADO'          => 'Activo',
            ]
        );

        // 3. Llamar a los seeders de catálogos y configuraciones iniciales
        $this->call([
            CategoriaSeeder::class,
            TipoAulaSeeder::class,
        ]);
    }
}