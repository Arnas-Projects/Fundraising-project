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
                'goal_amount' => rand(100, 1500),
                'main_image' => null,
                'status' => rand(0, 1) ? 'active' : 'pending', // 50% tikimybė, kad bus active arba pending
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
        // for ($i = 1; $i <= $storyNumber; $i++) {
        //     DB::table('donations')->insert([
        //         'story_id' => $i,
        //         'user_id' => rand(1, 10),
        //         'amount' => rand(5, 100),
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }

        // Multiple donations seeder
        for ($i = 1; $i <= $storyNumber; $i++) {

            $donationCount = rand(1, 5); // Kiekvienai istorijai nuo 1 iki 5 aukojimų

            for ($j = 0; $j < $donationCount; $j++) {

                DB::table('donations')->insert([
                    'story_id' => $i,
                    'user_id' => rand(1, 10),
                    'amount' => rand(5, 100),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Likes seeder
        // Pasiimame visų vartotojų ID
        $userIds = DB::table('users')->pluck('id')->toArray();

        for ($i = 1; $i <= $storyNumber; $i++) {

            // Kiekvienai istorijai parenkame atsitiktinį KIEKĮ unikalių vartotojų
            // Pvz., nuo 0 iki 8 atsitiktinių vartotojų iš visų esamų
            $randomUserIds = fake()->randomElements($userIds, rand(0, 8));

            foreach ($randomUserIds as $userId) {
                DB::table('likes')->insert([
                    'story_id' => $i,
                    'user_id' => $userId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }


        // Multiple comments per story seeder

        // Komentarų masyvas su įvairiais tekstais, kad būtų įvairesni komentarai
        $messages = [
            'Wow, labai įkvepianti istorija!',
            'Sėkmės tau, tikiuosi viskas pavyks!',
            'Tikrai verta dėmesio, pasidalinsiu su draugais.',
            'Saugokitės, man tai panašu į apgaulę...',
            'Kokia graži iniciatyva!',
            'Stiprybės jums šiuo sunkiu laikotarpiu.',
            'Ar yra kokių nors naujienų apie progresą?',
            'Palaikau visomis prasmėmis!',
            'Nelabai supratau esmės, bet sėkmės.',
            'Gera matyti, kad žmonės dar padeda vieni kitiems.',
            'Tikiuosi, kad pasieksite savo tikslą!',
            'Labai svarbu, kad būtų daugiau tokių kampanijų.',
            'Ar yra galimybė prisidėti savanoriškai?',
            'Taigi čia apgaulė, žmonės...',
            'Palaikau!',
            'Spaudžiu dešinę!',
            'Kiek dar laiko liko kampanijai?',
        ];

        for ($i = 1; $i <= $storyNumber; $i++) {

            $commentCount = rand(1, 5); // Kiekvienai istorijai nuo 1 iki 5 komentarų

            for ($j = 0; $j < $commentCount; $j++) {

                DB::table('comments')->insert([
                    'story_id' => $i,
                    'user_id' => rand(1, 10),
                    'content' => $messages[array_rand($messages)],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Call for AdminUserSeeder
        $this->call(AdminUserSeeder::class);
    }
}
