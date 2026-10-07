<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Categoria;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get categories
        $pestanasCategory = Categoria::where('nombre', 'Pestañas')->first();
        $unasCategory = Categoria::where('nombre', 'Uñas')->first();
        $depilacionCategory = Categoria::where('nombre', 'Depilación')->first();

        $servicios = [
            [
                'nombre' => 'Pestañas Básicas',
                'descripcion' => 'Extensiones y tratamientos de pestañas',
                'duracion_minutos' => 60,
                'precio' => 350.00,
                'categoria_id' => $pestanasCategory?->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Manicure',
                'descripcion' => 'Manicure y tratamientos de uñas',
                'duracion_minutos' => 45,
                'precio' => 250.00,
                'categoria_id' => $unasCategory?->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Depilación Facial',
                'descripcion' => 'Servicios de depilación',
                'duracion_minutos' => 30,
                'precio' => 200.00,
                'categoria_id' => $depilacionCategory?->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('servicios')->insert($servicios);
    }
}