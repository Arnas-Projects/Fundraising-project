<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        for ($i = 0; $i < 10; $i++) {
            DB::table('stories')->insert([
                'user_id' => rand(1, 10),
                'title' => 'Pavyzdinė kampanija #' . ($i + 1),
                'short_description' => 'Trumpas aprašymas kampanijai #' . ($i + 1),
                'full_story' => 'Pilnas pasakojimas apie kampaniją #' . ($i + 1) . '. Tai yra pavyzdinė kampanija, skirta testavimui.',
                'goal_amount' => rand(10, 500),
                'main_image' => null,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
