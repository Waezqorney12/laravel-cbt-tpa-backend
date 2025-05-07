<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuizChoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('quiz_choice')->insert([
            [
                'quiz_id' => 1,
                'choice_a' => 'Jakarta',
                'choice_b' => 'Bandung',
                'choice_c' => 'Surabaya',
                'choice_d' => 'Bali',
                'correct_answer' => 'a',
            ],
            [
                'quiz_id' => 2,
                'choice_a' => 'Kuala Lumpur',
                'choice_b' => 'Bangkok',
                'choice_c' => 'Manila',
                'choice_d' => 'Hanoi',
                'correct_answer' => 'a',
            ],
            [
                'quiz_id' => 4,
                'choice_a' => 'Tokyo',
                'choice_b' => 'Osaka',
                'choice_c' => 'Kyoto',
                'choice_d' => 'Hiroshima',
                'correct_answer' => 'a',
            ],
            [
                'quiz_id' => 5,
                'choice_a' => 'Seoul',
                'choice_b' => 'Busan',
                'choice_c' => 'Incheon',
                'choice_d' => 'Jeju',
                'correct_answer' => 'a',
            ]
        ]);
    }
}
