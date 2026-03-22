<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin names
        $adminNames = [
            'George Washington',
            'George W. Bush',
            'Barack Obama',
            'Donald J. Trump',
            'Joe Biden',
            'Abraham Lincoln',
            'Theodore Roosevelt',
            'Franklin D. Roosevelt',
            'John F. Kennedy',
            'Ronald Reagan',
            'Jimmy Carter',
            'Richard Nixon',
            'Dwight D. Eisenhower',
            'Woodrow Wilson',
            'Warren G. Harding',
        ];

        // Admin user seeder
        User::updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => $adminNames[array_rand($adminNames)],
                'password' => '12345678',
                // 'password' => Hash::make('password'), // Use a secure password in production
                'role' => 'admin',
            ]
        );
    }
}
