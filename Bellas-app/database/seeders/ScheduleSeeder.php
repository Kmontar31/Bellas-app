<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $horarios = [];
        
        // IDs de los profesionales (asumiendo que son 1, 2, 3 después del seeder de profesionales)
        $profesionales = [1, 2, 3];
        
        // Horarios de lunes a sábado (0 = domingo, 6 = sábado)
        for ($profesional_id = 1; $profesional_id <= 3; $profesional_id++) {
            for ($dia = 1; $dia <= 6; $dia++) { // Lunes a Sábado
                $horarios[] = [
                    'profesional_id' => $profesional_id,
                    'dia_semana' => $dia,
                    'hora_inicio' => '09:00:00',
                    'hora_fin' => '18:00:00',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('horarios')->insert($horarios);
    }
}