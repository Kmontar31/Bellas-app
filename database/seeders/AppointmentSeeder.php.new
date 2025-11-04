<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear algunas citas de ejemplo
        $citas = [
            [
                'cliente_id' => 1,
                'profesional_id' => 1,
                'servicio_id' => 1,
                'fecha' => Carbon::today(),
                'hora_inicio' => '10:00:00',
                'hora_fin' => '11:00:00',
                'estado' => 'confirmado',
                'notas' => 'Primera cita - Pestañas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cliente_id' => 2,
                'profesional_id' => 2,
                'servicio_id' => 2,
                'fecha' => Carbon::today(),
                'hora_inicio' => '11:00:00',
                'hora_fin' => '11:45:00',
                'estado' => 'confirmado',
                'notas' => 'Manicure completo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cliente_id' => 3,
                'profesional_id' => 3,
                'servicio_id' => 3,
                'fecha' => Carbon::tomorrow(),
                'hora_inicio' => '09:00:00',
                'hora_fin' => '09:30:00',
                'estado' => 'pendiente',
                'notas' => 'Depilación programada',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('agenda')->insert($citas);
    }
}