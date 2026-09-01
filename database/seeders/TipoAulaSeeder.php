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
                'tip_aula_nombre' => 'Sala de Sistemas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tip_aula_nombre' => 'Laboratorio de Química',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tip_aula_nombre' => 'Aula Magistral',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}