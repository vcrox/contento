<?php

namespace Database\Seeders;

use App\Models\Pais;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['codigo'=>'MEX', 'descripcion'=>'México'],
            ['codigo'=>'CAN', 'descripcion'=>'Canadá'],
            ['codigo'=>'USA', 'descripcion'=>'Estados Unidos'],
        ];
        Pais::insert($data);
    }
}
