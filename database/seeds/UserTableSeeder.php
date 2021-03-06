<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = new \App\User();
        $user->name ="Toni Soprano";
        $user->email = "tony@gmail.com";
        $user->password = \Illuminate\Support\Facades\Hash::make('123456789');
        $user->save();
        $user->roles()->attach(1);
    }
}
