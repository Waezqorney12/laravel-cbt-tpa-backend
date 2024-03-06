<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::factory(10)->create();
        // \App\Models\User::factory()->create([
        //     'name' => 'Waezqorney',
        //     'email' => 'waezqorney12@gmail.com',
        //     'phone' => '081230662475',
        //     'roles' => 'mahasiswa',
        //     'password' => '123456',
        // ]);
        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]); -> ini buat manual
    }
}
