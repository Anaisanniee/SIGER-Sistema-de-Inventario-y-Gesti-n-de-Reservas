<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoAulaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipos_aulas')->insert([
            [
                'tip_aula_nombre' => 'Administrativas y de Dirección',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tip_aula_nombre' => 'Apoyo Docente y Logística',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tip_aula_nombre' => 'Espacios Académicos Especializados',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tip_aula_nombre' => 'Áreas Comunes y de Bienestar',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tip_aula_nombre' => 'Bloque Preescolar',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tip_aula_nombre' => 'Bloque Primaria',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tip_aula_nombre' => 'Bloque Secundaria',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}