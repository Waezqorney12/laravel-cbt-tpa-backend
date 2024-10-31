<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MateriImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('materi_images')->insert([
            [
                'materi_id' => 1,
                'materi_image_path' => 'https://picsum.photos/500/300',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'materi_id' => 2,
                'materi_image_path' => 'https://picsum.photos/500/300',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'materi_id' => 3,
                'materi_image_path' => 'https://picsum.photos/500/300',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'materi_id' => 4,
                'materi_image_path' => 'https://picsum.photos/500/300',
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}
