<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Materi>
 */
class MateriFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [

            'name' => $this->faker->name,
            'title' => $this->faker->sentence(),
            'description' => $this->faker->text(),
            'image' => $this->faker->imageUrl(),
            'kategori' => $this->faker->randomElement(['Numeric', 'Verbal', 'Logika']),
        ];
    }
}
