<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
            'terakhir_aktif' => now(),
        ]);

        // Create Demo Users for testing
        User::create([
            'name' => 'User Demo',
            'email' => 'user@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'email_verified_at' => now(),
            'terakhir_aktif' => now(),
        ]);

        // Create additional demo users for clustering testing
        $demoUsers = [
            ['name' => 'Andi Pembaca', 'email' => 'andi@gmail.com'],
            ['name' => 'Sari Buku', 'email' => 'sari@gmail.com'],
            ['name' => 'Doni Novel', 'email' => 'doni@gmail.com'],
            ['name' => 'Maya Literasi', 'email' => 'maya@gmail.com'],
            ['name' => 'Reza Edukasi', 'email' => 'reza@gmail.com'],
        ];

        foreach ($demoUsers as $user) {
            User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make('password'),
                'role' => 'user',
                'email_verified_at' => now(),
                'terakhir_aktif' => now()->subDays(rand(1, 30)),
            ]);
        }
    }
}
