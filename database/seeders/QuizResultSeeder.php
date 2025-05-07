<?php
// database/seeders/QuizResultSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuizResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('quiz_result')->insert([
            [
                'quiz_id' => 1,
                'user_id' => 1,
                'score' => null,
                'status' => 'not started',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'quiz_id' => 1,
                'user_id' => 2,
                'score' => null,
                'status' => 'not started',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'quiz_id' => 2,
                'user_id' => 1,
                'score' => 100,
                'status' => 'pass',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'quiz_id' => 2,
                'user_id' => 2,
                'score' => 80,
                'status' => 'pass',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'quiz_id' => 2,
                'user_id' => 3,
                'score' => 80,
                'status' => 'pass',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'quiz_id' => 3,
                'user_id' => 1,
                'score' => 100,
                'status' => 'pass',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'quiz_id' => 4,
                'user_id' => 2,
                'score' => null,
                'status' => 'not started',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'quiz_id' => 5,
                'user_id' => 2,
                'score' => null,
                'status' => 'not started',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'quiz_id' => 3,
                'user_id' => 3,
                'score' => 80,
                'status' => 'pass',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
