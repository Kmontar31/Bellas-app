<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfessionalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $profesionales = [
            [
                'nombre' => 'María',
                'email' => 'maria@bellasapp.com',
                'telefono' => '555-0001',
                'especialidad' => 'Pestañas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Andrea',
                'email' => 'andrea@bellasapp.com',
                'telefono' => '555-0002',
                'especialidad' => 'Uñas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Paola',
                'email' => 'paola@bellasapp.com',
                'telefono' => '555-0003',
                'especialidad' => 'Depilación',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('profesionales')->insert($profesionales);
    }
}