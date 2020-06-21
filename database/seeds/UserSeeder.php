<?php

use Illuminate\Database\Seeder;
use App\User;
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
        for ($i = 0; $i <= 15; $i++)
        {
            User::create([
                'name'  =>  'andriy' . $i,
                'email' =>  'andriy' . $i . '@email.com',
                'password'  =>  Hash::make('password' . $i),
                'permissions'   =>  $i % 2
            ]);
        }
    }
}
