<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userData = [
            [
                'name' => 'Jishan Shaikh',
                'dob'  => date('Y-m-d',strtotime('25-08-2002')),
                'username'  => 'jishan',
                'password'  => Hash::make('password')
            ]
        ];

        foreach ($userData as $user){
            User::create($user);
        }
    }
}
