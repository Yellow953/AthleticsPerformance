<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::connection('mysql')->table('users')->insert([
            'name' => 'test',
            'role' => 'admin',
            'email' => 'test@test.com',
            'password' => Hash::make('qwe123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::connection('mysql')->table('users')->insert([
            'name' => 'gaby el khoury',
            'role' => 'admin',
            'email' => 'gabyek@gmail.com',
            'password' => Hash::make('qwe123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}