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
                    'personal_id' => 1, 
                    'email' => 'waezqorney12@gmail.com',
                    'password' => bcrypt('12345678'),
                    'username' => 'waezqorney',
                    'roles' => 'STAFF',
                    'email_verified_at' => now(),
                    'created_at' => now(),
                ],
                [
                    'personal_id' => null,
                    'email' => 'admin@gmail.com',
                    'password' => bcrypt('admin'),
                    'roles' => 'ADMIN',
                    'username' => 'admin',
                    'email_verified_at' => now(),
                    'created_at' => now(),
                ],
                [
                    'personal_id' => 2,
                    'email' => 'asep@gmail.com',
                    'password' => bcrypt('12345678'),
                    'roles' => 'STAFF',
                    'username' => 'asep',
                    'email_verified_at' => now(),
                    'created_at' => now(),
                ],
                [
                    'personal_id' => 3,
                    'email' => 'siti@gmail.com',
                    'password' => bcrypt('12345678'),
                    'roles' => 'USER',
                    'username' => 'siti',
                    'email_verified_at' => now(),
                    'created_at' => now(),
                ],
                [
                    'personal_id' => 4,
                    'email' => 'budi@gmail.com',
                    'password' => bcrypt('12345678'),
                    'roles' => 'USER',
                    'username' => 'budis',
                    'email_verified_at' => now(),
                    'created_at' => now(),
                ],

            ]
        );
    }
}
