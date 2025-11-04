<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $servicios = [
            [
                'nombre' => 'Pestañas',
                'descripcion' => 'Extensiones y tratamientos de pestañas',
                'duracion_minutos' => 60,
                'precio' => 350.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Uñas',
                'descripcion' => 'Manicure y tratamientos de uñas',
                'duracion_minutos' => 45,
                'precio' => 250.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Depilación',
                'descripcion' => 'Servicios de depilación',
                'duracion_minutos' => 30,
                'precio' => 200.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('servicios')->insert($servicios);
    }
}