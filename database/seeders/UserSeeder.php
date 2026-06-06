<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserPoint;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Rina Amelia',
                'email' => 'rina@example.com',
                'phone' => '081234567890',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@example.com',
                'phone' => '081234567891',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti@example.com',
                'phone' => '081234567892',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Doni Prasetyo',
                'email' => 'doni@example.com',
                'phone' => '081234567893',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Maya Indah',
                'email' => 'maya@example.com',
                'phone' => '081234567894',
                'password' => Hash::make('password'),
            ],
        ];

        foreach ($users as $userData) {
            $user = User::create($userData);
            UserPoint::create([
                'user_id' => $user->id,
                'balance' => fake()->numberBetween(0, 5000),
            ]);
        }
    }
}
