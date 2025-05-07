<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('quiz')->insert([
            [
                'id' => 1,
                'image_thumbnail_path' => 'https://via.placeholder.com/150',
                'title' => 'Quiz 1',
                'description' => 'Quiz 1 description',
                'type' => 'multiple_choice',
                'category' => 'Logika',
            ],
            [
                'id' => 2,
                'image_thumbnail_path' => 'https://via.placeholder.com/150',
                'title' => 'Quiz 2',
                'description' => 'Quiz 2 description',
                'type' => 'multiple_choice',
                'category' => 'Verbal',
            ],
            [
                'id' => 3,
                'image_thumbnail_path' => 'https://via.placeholder.com/150',
                'title' => 'Quiz 3',
                'description' => 'Quiz 3 description',
                'type' => 'essay',
                'category' => 'Numeric',
            ],
            [
                'id' => 4,
                'image_thumbnail_path' => 'https://via.placeholder.com/150',
                'title' => 'Quiz 4',
                'description' => 'Quiz 4 description',
                'type' => 'multiple_choice',
                'category' => 'Logika',
            ],
            [
                'id' => 5,
                'image_thumbnail_path' => 'https://via.placeholder.com/150',
                'title' => 'Quiz 5',
                'description' => 'Quiz 5 description',
                'type' => 'multiple_choice',
                'category' => 'Verbal',
            ],
            [
                'id' => 6,
                'image_thumbnail_path' => 'https://via.placeholder.com/150',
                'title' => 'Quiz 6',
                'description' => 'Quiz 6 description',
                'type' => 'essay',
                'category' => 'Numeric',
            ],
        ]);
    }
}
