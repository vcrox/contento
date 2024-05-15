<?php

namespace Database\Seeders;

use App\Models\Estado;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['codigo' => 'AGU', 'codigo_pais' => 'MEX', 'descripcion' => 'Aguascalientes'],
            ['codigo' => 'BCN', 'codigo_pais' => 'MEX', 'descripcion' => 'Baja California'],
            ['codigo' => 'BCS', 'codigo_pais' => 'MEX', 'descripcion' => 'Baja California Sur'],
            ['codigo' => 'CAM', 'codigo_pais' => 'MEX', 'descripcion' => 'Campeche'],
            ['codigo' => 'CHP', 'codigo_pais' => 'MEX', 'descripcion' => 'Chiapas'],
            ['codigo' => 'CHH', 'codigo_pais' => 'MEX', 'descripcion' => 'Chihuahua'],
            ['codigo' => 'COA', 'codigo_pais' => 'MEX', 'descripcion' => 'Coahuila'],
            ['codigo' => 'COL', 'codigo_pais' => 'MEX', 'descripcion' => 'Colima'],
            ['codigo' => 'CMX', 'codigo_pais' => 'MEX', 'descripcion' => 'Ciudad de México'],
            ['codigo' => 'DUR', 'codigo_pais' => 'MEX', 'descripcion' => 'Durango'],
            ['codigo' => 'GUA', 'codigo_pais' => 'MEX', 'descripcion' => 'Guanajuato'],
            ['codigo' => 'GRO', 'codigo_pais' => 'MEX', 'descripcion' => 'Guerrero'],
            ['codigo' => 'HID', 'codigo_pais' => 'MEX', 'descripcion' => 'Hidalgo'],
            ['codigo' => 'JAL', 'codigo_pais' => 'MEX', 'descripcion' => 'Jalisco'],
            ['codigo' => 'MEX', 'codigo_pais' => 'MEX', 'descripcion' => 'Estado de México'],
            ['codigo' => 'MIC', 'codigo_pais' => 'MEX', 'descripcion' => 'Michoacán'],
            ['codigo' => 'MOR', 'codigo_pais' => 'MEX', 'descripcion' => 'Morelos'],
            ['codigo' => 'NAY', 'codigo_pais' => 'MEX', 'descripcion' => 'Nayarit'],
            ['codigo' => 'NLE', 'codigo_pais' => 'MEX', 'descripcion' => 'Nuevo León'],
            ['codigo' => 'OAX', 'codigo_pais' => 'MEX', 'descripcion' => 'Oaxaca'],
            ['codigo' => 'PUE', 'codigo_pais' => 'MEX', 'descripcion' => 'Puebla'],
            ['codigo' => 'QUE', 'codigo_pais' => 'MEX', 'descripcion' => 'Querétaro'],
            ['codigo' => 'ROO', 'codigo_pais' => 'MEX', 'descripcion' => 'Quintana Roo'],
            ['codigo' => 'SLP', 'codigo_pais' => 'MEX', 'descripcion' => 'San Luis Potosí'],
            ['codigo' => 'SIN', 'codigo_pais' => 'MEX', 'descripcion' => 'Sinaloa'],
            ['codigo' => 'SON', 'codigo_pais' => 'MEX', 'descripcion' => 'Sonora'],
            ['codigo' => 'TAB', 'codigo_pais' => 'MEX', 'descripcion' => 'Tabasco'],
            ['codigo' => 'TAM', 'codigo_pais' => 'MEX', 'descripcion' => 'Tamaulipas'],
            ['codigo' => 'TLA', 'codigo_pais' => 'MEX', 'descripcion' => 'Tlaxcala'],
            ['codigo' => 'VER', 'codigo_pais' => 'MEX', 'descripcion' => 'Veracruz'],
            ['codigo' => 'YUC', 'codigo_pais' => 'MEX', 'descripcion' => 'Yucatán'],
            ['codigo' => 'ZAC', 'codigo_pais' => 'MEX', 'descripcion' => 'Zacatecas']
        ];
        Estado::insert($data);
    }
}
