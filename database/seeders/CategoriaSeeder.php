<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categorias')->insert([
            [
                'cate_nombre' => 'Muebles y enseres',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cate_nombre' => 'Equipos y maquina de oficina',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cate_nombre' => 'Equipo de computacion',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cate_nombre' => 'Equipo de comunicacion',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cate_nombre' => 'Equipo de musica',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cate_nombre' => 'Equipo de laboratorio',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cate_nombre' => 'Equipo de enseñanza',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cate_nombre' => 'Equipo de recreacion y deporte',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cate_nombre' => 'Equipo de cocina',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}