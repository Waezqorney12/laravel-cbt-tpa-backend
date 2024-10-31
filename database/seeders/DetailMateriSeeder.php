<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetailMateriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('detail_materi')->insert([
            [
                'user_id' => 1,
                'materi_id' => 1,
                'status' => 0,
                'created_at' => now(),
            ],
            [
                'user_id' => 1,
                'materi_id' => 2,
                'status' => 0,
                'created_at' => now(),

            ],
            [
                'user_id' => 1,
                'materi_id' => 3,
                'status' => 0,
                'created_at' => now(),

            ],
            [
                'user_id' => 1,
                'materi_id' => 4,
                'status' => 0,
                'created_at' => now(),

            ],
            [
                'user_id' => 2,
                'materi_id' => 1,
                'status' => 0,
                'created_at' => now(),

            ],
            [
                'user_id' => 2,
                'materi_id' => 3,
                'status' => 0,
                'created_at' => now(),

            ],
        ]);
    }
}
