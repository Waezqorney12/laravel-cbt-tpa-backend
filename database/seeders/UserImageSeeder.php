<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user_images')->insert(
            [
                [
                    'user_id' => 1,
                    'user_image_path' => 'https://picsum.photos/500/300',
                    'created_at' => now(),
                ],
                [
                    'user_id' => 2,
                    'user_image_path' => 'https://picsum.photos/500/300',
                    'created_at' => now(),
                ],
                [
                    'user_id' => 3,
                    'user_image_path' => 'https://picsum.photos/500/300',
                    'created_at' => now(),
                ]
            ]
        );
    }
}
