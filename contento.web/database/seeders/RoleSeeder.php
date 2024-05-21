<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::create(["name" => "Administrador"]);
        $superadmin = Role::create(["name" => "SuperAdministrador"]);
        $ejecutivo = Role::create(["name" => "Ejecutivo"]);

        Permission::create(['name' => 'CREAR USUARIOS']);
        Permission::create(['name' => 'EDITAR USUARIOS']);
        Permission::create(['name' => 'ELIMINAR USUARIOS']);
        Permission::create(['name' => 'VISUALIZAR USUARIOS']);
        $superadmin->givePermissionTo(Permission::all());
    }
}
