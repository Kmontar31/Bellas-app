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
                'id' => 1, 
                'nombre' => 'Pestañas',
                'nombre_categoria' => 'pestanas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'nombre' => 'Cejas',
                'nombre_categoria' => 'cejas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'nombre' => 'Estética Facial',
                'nombre_categoria' => 'estetica_facial',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'nombre' => 'Uñas',
                'nombre_categoria' => 'unas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'nombre' => 'Depilación',
                'nombre_categoria' => 'depilacion',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
