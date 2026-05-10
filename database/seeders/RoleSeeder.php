<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    \DB::table('roles')->insert([
        ['name' => 'Rectora', 'slug' => 'rectora'],
        ['name' => 'Secretaria', 'slug' => 'secretaria'],
        ['name' => 'Docente', 'slug' => 'docente'],
    ]);
}
}
