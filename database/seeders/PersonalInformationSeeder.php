<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PersonalInformationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('personal_information')->insert(
            [
                [
                    'matrix_id' => 'E41210184',
                    'first_name' => 'Waezqorney',
                    'last_name' => 'Huanfareyzo',
                    'full_name' => 'Waezqorney Huanfareyzo',
                    'birth_date' => '2003-06-09',
                    'gender' => 'man',
                    'address' => '123 Main St',
                    'phone_number' => '081234567890',
                    'departement' => 'Engineering',
                    'study_program' => 'Computer Science',
                    'entry_year' => 2021,
                    'updated_at' => now(),
                    'created_at' => now(),

                ],
                [
                    'matrix_id' => 'E41210185',
                    'first_name' => 'Asep',
                    'last_name' => 'Sutisna',
                    'full_name' => 'Asep Sutisna',
                    'birth_date' => '2002-08-15',
                    'gender' => 'man',
                    'address' => '456 Elm St',
                    'phone_number' => '081234567891',
                    'departement' => 'Business',
                    'study_program' => 'Marketing',
                    'entry_year' => 2020,
                    'updated_at' => now(),
                    'created_at' => now(),
                ],
                [
                    'matrix_id' => 'E41210186',
                    'first_name' => 'Siti',
                    'last_name' => 'Nurhaliza',
                    'full_name' => 'Siti Nurhaliza',
                    'birth_date' => '2001-12-25',
                    'gender' => 'woman',
                    'address' => '789 Oak St',
                    'phone_number' => '081234567892',
                    'departement' => 'Arts',
                    'study_program' => 'Music',
                    'entry_year' => 2019,
                    'updated_at' => now(),
                    'created_at' => now(),
                ],
                [
                    'matrix_id' => 'E41210187',
                    'first_name' => 'Budi',
                    'last_name' => 'Santoso',
                    'full_name' => 'Budi Santoso',
                    'birth_date' => '1999-03-10',
                    'gender' => 'man',
                    'address' => '123 Pine St',
                    'phone_number' => '081234567893',
                    'departement' => 'Engineering',
                    'study_program' => 'Mechanical Engineering',
                    'entry_year' => 2018,
                    'updated_at' => now(),
                    'created_at' => now(),
                ],
            ]
        );
    }
}
