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
                    'created_at' => now(),
                ],
                [
                    'user_id' => 2,
                    'created_at' => now(),
                ],
                [
                    'user_id' => 3,
                    'created_at' => now(),
                ]
            ]
        );
    }
}
