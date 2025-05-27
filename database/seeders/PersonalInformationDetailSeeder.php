<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PersonalInformationDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('detail_personal_information')->insert([
            [
                'personal_id' => 1,
                'user_id' => 1,
                'status' => 'undergraduate',
            ],
            [
                'personal_id' => 2,
                'user_id' => 1,
                'status' => 'undergraduate',
            ],
            [
                'personal_id' => 3,
                'user_id' => 2,
                'status' => 'undergraduate',
            ],
            [
                'personal_id' => 4,
                'user_id' => 3,
                'status' => 'graduate',
            ]
        ]);
    }
}
