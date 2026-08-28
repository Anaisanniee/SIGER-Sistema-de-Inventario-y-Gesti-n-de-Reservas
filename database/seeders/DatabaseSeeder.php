<?php

namespace Database\Seeders;

<<<<<<< HEAD
=======
use App\Models\User;
use App\Models\Role; // Importamos el modelo Role para poder buscar por slug
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
>>>>>>> origin/backend-Elias
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
<<<<<<< HEAD
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
=======
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
>>>>>>> origin/backend-Elias
    }
}