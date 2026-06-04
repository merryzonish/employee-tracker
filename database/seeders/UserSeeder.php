<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Ali Hassan',   'email' => 'ali@company.com'],
            ['name' => 'Sara Khan',    'email' => 'sara@company.com'],
            ['name' => 'Usman Ahmed',  'email' => 'usman@company.com'],
            ['name' => 'Fatima Noor',  'email' => 'fatima@company.com'],
            ['name' => 'Bilal Raza',   'email' => 'bilal@company.com'],
            ['name' => 'Ayesha Malik', 'email' => 'ayesha@company.com'],
            ['name' => 'Hamza Sheikh', 'email' => 'hamza@company.com'],
            ['name' => 'Zara Hussain', 'email' => 'zara@company.com'],
            ['name' => 'Omar Farooq',  'email' => 'omar@company.com'],
            ['name' => 'Hina Baig',    'email' => 'hina@company.com'],
        ];

        foreach ($users as $user) {
            DB::table('users')->insert([
                'name'       => $user['name'],
                'email'      => $user['email'],
                'password'   => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}