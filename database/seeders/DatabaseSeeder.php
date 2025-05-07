<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\PersonalInformation;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(
            [
                PersonalInformationSeeder::class,
                UserSeeder::class,
                UserImageSeeder::class,
                PersonalInformationDetailSeeder::class,


                // UjianSeeder::class,
                // UjianSoalListSeeder::class,



                QuizQuestionSeeder::class,
                //QuizSeeder::class,
                //QuizChoiceSeeder::class,
                //QuizEssaySeeder::class,
                //QuizDetailSeeder::class,
                //QuizResultSeeder::class,

            ]
        );

    }
}
