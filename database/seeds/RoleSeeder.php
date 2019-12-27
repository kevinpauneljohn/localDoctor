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
//        Role::create(['name' => 'admin']);
//        Role::create(['name' => 'owner']);
//        Role::create(['name' => 'co-owner']);
//        Role::create(['name' => 'medical doctor']);
//        Role::create(['name' => 'HR']);

        Role::create(['name' => 'medical staff']);
        Role::create(['name' => 'employee']);


//        Permission::create(['name' => 'view user'])->assignRole('owner');
//        Permission::create(['name' => 'add user'])->assignRole('owner');
//        Permission::create(['name' => 'edit user'])->assignRole('owner');
//        Permission::create(['name' => 'delete user'])->assignRole('owner');
//
//        Permission::create(['name' => 'view clinic'])->assignRole('owner');
//        Permission::create(['name' => 'add clinic'])->assignRole('owner');
//        Permission::create(['name' => 'edit clinic'])->assignRole('owner');
//        Permission::create(['name' => 'delete clinic'])->assignRole('owner');

//        Permission::create(['name' => 'view medical staff'])->assignRole('owner');
//        Permission::create(['name' => 'add medical staff'])->assignRole('owner');
//        Permission::create(['name' => 'edit medical staff'])->assignRole('owner');
//        Permission::create(['name' => 'delete medical staff'])->assignRole('owner');
//
//        Permission::create(['name' => 'view employee'])->assignRole('owner');
//        Permission::create(['name' => 'add employee'])->assignRole('owner');
//        Permission::create(['name' => 'edit employee'])->assignRole('owner');
//        Permission::create(['name' => 'delete employee'])->assignRole('owner');
    }
}
