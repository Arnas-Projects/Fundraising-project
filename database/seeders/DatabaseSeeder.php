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

        $storyNumber = 70;

        for ($i = 0; $i < $storyNumber; $i++) {
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

        // Tags seeder
        DB::table('tags')->insert([
            ['name' => 'Gyvūnai', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Vaikai', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sveikata', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Švietimas', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Aplinka', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // StoryTag seeder
        for ($i = 1; $i <= $storyNumber; $i++) {
            $tagIds = DB::table('tags')->pluck('id')->toArray();
            $randomTagIds = array_rand($tagIds, rand(1, 3));
            foreach ((array) $randomTagIds as $tagId) {
                DB::table('story_tag')->insert([
                    'story_id' => $i,
                    'tag_id' => $tagIds[$tagId],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Main image seeder
        for ($i = 1; $i <= $storyNumber; $i++) {

            $randomImage = rand(1, 10);

            DB::table('stories')->where('id', $i)->update([
                'main_image' => 'stories/main-img/' . $randomImage . '.jpg',
            ]);
        }

        // Gallery images seeder
        for ($i = 1; $i <= $storyNumber; $i++) {
            for ($j = 1; $j <= 3; $j++) {

                $randomImage = rand(1, 7);

                DB::table('gallery_images')->insert([

                    'story_id' => $i,
                    'image_path' => 'stories/gallery/' . $randomImage . '.jpeg',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Donations seeder
        for ($i = 1; $i <= $storyNumber; $i++) {
            DB::table('donations')->insert([
                'story_id' => $i,
                'user_id' => rand(1, 10),
                'amount' => rand(5, 100),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
