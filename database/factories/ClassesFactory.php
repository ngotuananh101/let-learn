<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Classes>
 */
class ClassesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'school_id' => 1,
            'name' => $this->faker->name,
            'status' => 'active',
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
        ];
    }
}
