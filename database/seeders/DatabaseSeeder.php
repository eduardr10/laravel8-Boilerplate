<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // USUARIO ADMINISTRADOR
        $adminUser = User::factory()->create([
            'name' => 'admin',
            'surname' => 'admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('12345678'),
        ]);
        
        // ROLES Y PERMISOS BASE PARA EL SISTEMA
        $adminRole = Role::create(['name' => 'admin']);
        $permission = Permission::create(['name' => 'create.user']);
        $adminRole->givePermissionTo($permission);
        Role::create(['name' => 'guest']);
        
        // ASIGNACIÓN DE ROL ADMINISTRADOR
        $adminUser->assignRole($adminRole);
    }
}
