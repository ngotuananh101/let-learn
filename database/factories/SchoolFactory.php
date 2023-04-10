<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\School>
 */
class SchoolFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'address' => $this->faker->address,
            'website' => $this->faker->domainName,
            'city' => $this->faker->city,
            'region' => $this->faker->state,
            'logo' => $this->faker->imageUrl(512, 512, 'cats', true, 'Faker'),
            'slug' => $this->faker->slug,
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}
