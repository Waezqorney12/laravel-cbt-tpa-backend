<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuizQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('quiz_question')->insert([
            [
                'quiz_image_path' => 'https://via.placeholder.com/150',
                'question' => 'What is the capital of Indonesia?',
            ],
            [
                'quiz_image_path' => 'https://via.placeholder.com/150',
                'question' => 'What is the capital of Malaysia?',
            ],
            [
                'quiz_image_path' => 'https://via.placeholder.com/150',
                'question' => 'What is the capital of Singapore?',
            ],
            [
                'quiz_image_path' => 'https://via.placeholder.com/150',
                'question' => 'What is the capital of Japan?',
            ],
            [
                'quiz_image_path' => 'https://via.placeholder.com/150',
                'question' => 'What is the capital of South Korea?',
            ],
            [
                'quiz_image_path' => 'https://via.placeholder.com/150',
                'question' => 'What is the capital of China?',
            ],
        ]);
    }
}
