<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Users extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->delete();
        $superadmin = User::factory()->create([
            'name' => 'vcrox',
            'email' => 'vcrox@hotmail.com',
            'password' => bcrypt('petertony'),
        ]);

        $superadmin->assignRole(["SuperAdministrador"]);
        $admin = User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@demo.com',
            'password' => bcrypt('Admin123'),
        ]);

        $admin->assignRole(["Administrador"]);
    }
}
