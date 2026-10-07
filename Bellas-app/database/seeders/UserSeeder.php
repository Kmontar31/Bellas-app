<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $usuarios = [
            [
                'nombre'=>'Admin',
                'email'=>'admin@gmail.com',
                'password'=>bcrypt('admin123'),
            ],
        ];

        DB::table('users')->insert($usuarios);   }
}
