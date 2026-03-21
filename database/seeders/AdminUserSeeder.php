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
            'Brian O\'Connor',
            'Mia Toretto',
            'Dominic Toretto',
            'Letty Ortiz',
            'Roman Pearce',
            'Tej Parker',
            'Luke Hobbs',
            'Deckard Shaw',
            'Han Lue',
            'Jesse',
            'Gisele Yashar',
            'Vince',
            'Leon',
            'Suki',
            'Carter Verone',
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
