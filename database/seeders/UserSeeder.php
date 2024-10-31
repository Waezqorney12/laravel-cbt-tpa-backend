<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert(
            [
                [
                    'email' => 'waezqorney12@gmail.com',
                    'password' => bcrypt('12345678'),
                    'first_name' => 'Waezqorney',
                    'last_name' => 'Huanfareyzo',
                    'username' => 'waezqorney',
                    'phone_number' => '081234567890',
                    'email_verified_at' => now(),
                    'created_at' => now(),

                ],
                [
                    'email' => 'asep@gmail.com',
                    'password' => bcrypt('12345678'),
                    'first_name' => 'Muhammad',
                    'last_name' => 'Asep',
                    'username' => 'asep',
                    'phone_number' => '081231231231',
                    'email_verified_at' => now(),
                    'created_at' => now(),
                ],
                [
                    'email' => 'jamal@gmail.com',
                    'password' => bcrypt('12345678'),
                    'first_name' => 'Muhammad',
                    'last_name' => 'Jamal',
                    'username' => 'Jamaludin',
                    'phone_number' => '081231235121',
                    'email_verified_at' => now(),
                    'created_at' => now(),

                ]
            ]
        );
    }
}
