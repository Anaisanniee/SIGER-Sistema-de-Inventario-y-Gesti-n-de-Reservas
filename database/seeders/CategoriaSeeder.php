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
                'cate_nombre' => 'Tecnología y Cómputo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cate_nombre' => 'Tablets',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cate_nombre' => 'Equipos Audiovisuales',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
