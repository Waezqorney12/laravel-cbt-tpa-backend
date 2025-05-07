<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuizDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('quiz_detail')->insert([
            [
                'quiz_id' => 1,
                'user_id' => 2,
                'quiz_question_id' => 1,
                'answer' => null,
                'is_correct' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'quiz_id' => 2,
                'user_id' => 2,
                'quiz_question_id' => 2,
                'answer' => null,
                'is_correct' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'quiz_id' => 4,
                'user_id' => 2,
                'quiz_question_id' => 4,
                'answer' => null,
                'is_correct' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'quiz_id' => 5,
                'user_id' => 2,
                'quiz_question_id' => 5,
                'answer' => null,
                'is_correct' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
