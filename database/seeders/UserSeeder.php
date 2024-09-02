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
        User::create([
            'name'      => 'Administrator',
            'email'     => 'admin@example.com',
            'password'  => Hash::make('password'),
            'role'      => 'admin'     
        ]);  
        User::create([
            'name'      => 'Operator',
            'email'     => 'operator@example.com',
            'password'  => Hash::make('password'),
            'role'      => 'operator'     
        ]);  
        User::create([
            'name'      => 'Harun',
            'email'     => 'harun@example.com',
            'password'  => Hash::make('password'),
            'role'      => 'user'     
        ]);  
    }
}
