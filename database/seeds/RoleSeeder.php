<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'super admin']);
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'client admin']);
        Role::create(['name' => 'owner']);
        Role::create(['name' => 'co-owner']);

        Permission::create(['name' => 'view role'])->assignRole('super admin');
        Permission::create(['name' => 'add role'])->assignRole('super admin');
        Permission::create(['name' => 'edit role'])->assignRole('super admin');
        Permission::create(['name' => 'delete role'])->assignRole('super admin');
        Permission::create(['name' => 'assign role to permission'])->assignRole('super admin');

        Permission::create(['name' => 'view permission'])->assignRole('super admin');
        Permission::create(['name' => 'add permission'])->assignRole('super admin');
        Permission::create(['name' => 'edit permission'])->assignRole('super admin');
        Permission::create(['name' => 'delete permission'])->assignRole('super admin');

        Permission::create(['name' => 'view user'])->assignRole('super admin');
        Permission::create(['name' => 'add user'])->assignRole('super admin');
        Permission::create(['name' => 'edit user'])->assignRole('super admin');
        Permission::create(['name' => 'delete user'])->assignRole('super admin');

        Permission::create(['name' => 'view client'])->assignRole('super admin');
        Permission::create(['name' => 'add client'])->assignRole('super admin');
        Permission::create(['name' => 'edit client'])->assignRole('super admin');
        Permission::create(['name' => 'delete client'])->assignRole('super admin');

        Permission::create(['name' => 'view clinic'])->assignRole('super admin');
        Permission::create(['name' => 'add clinic'])->assignRole('super admin');
        Permission::create(['name' => 'edit clinic'])->assignRole('super admin');
        Permission::create(['name' => 'delete clinic'])->assignRole('super admin');
    }
}
