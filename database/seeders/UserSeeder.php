<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user              =  new User();
        $user->name        = "mahatab";
        $user->email       = "shuvomahi12@gmail.com";
        $user->password    = Hash::make('123456');
        $user->save();
    }
}
