<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use \App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'username' => 'admin',
            'password' => Hash::make('123456'),
            'status' => 3,
            'level' => 1,
            'first_name' => 'admin',
            'last_name' => 'admin',
            'phone' => '09121234567'
        ]);
    }
}
