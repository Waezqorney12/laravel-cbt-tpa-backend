<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuizEssaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('quiz_essay')->insert([
            [
                'quiz_id' => 3,
                'correct_answer' => 'Kuala Lumpur',
            ],
            [
                'quiz_id' => 6,
                'correct_answer' => 'Jakarta',
            ],
        ]);
    }
}
