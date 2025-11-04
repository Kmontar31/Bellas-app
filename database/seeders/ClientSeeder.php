<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clientes = [
            [
                'nombre' => 'Ana García',
                'email' => 'ana@email.com',
                'telefono' => '555-1001',
                'direccion' => 'Calle Principal 123',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Laura Pérez',
                'email' => 'laura@email.com',
                'telefono' => '555-1002',
                'direccion' => 'Av. Central 456',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Carmen Rodríguez',
                'email' => 'carmen@email.com',
                'telefono' => '555-1003',
                'direccion' => 'Plaza Mayor 789',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('clientes')->insert($clientes);
    }
}