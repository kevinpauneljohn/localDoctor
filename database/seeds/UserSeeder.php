<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->firstname = "john kevin";
        $user->middlename = "pama";
        $user->lastname = "paunel";
        $user->username = "kevinpauneljohn";
        $user->email = "johnkevinpaunel@gmail.com";
        $user->password = bcrypt('123');
        $user->mobileNo ='09166520817';
        $user->address ='blk 141 lot 2, Bulaon Resettlement, City Of San Fernando, Pampanga';
        $user->status = "offline";
        $user->category = "backend";
        $user->assignRole('super admin');

        $user->save();

        $admin = new User;
        $admin->firstname = "jamaica";
        $admin->middlename = "";
        $admin->lastname = "soto";
        $admin->username = "jamaica";
        $admin->email = "jhamie@gmail.com";
        $admin->password = bcrypt("123");
        $admin->mobileNo ='09166520817';
        $admin->address ='blk 141 lot 2, Bulaon Resettlement, City Of San Fernando, Pampanga';
        $admin->status = "offline";
        $admin->category = "backend";
        $admin->assignRole('admin');

        $admin->save();
    }
}
