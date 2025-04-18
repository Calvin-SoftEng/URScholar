<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Scholarship>
 */
class ScholarshipFactory extends Factory
{
    protected $model = \App\Models\Scholarship::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'sponsor_id' => \App\Models\Sponsor::factory(),
            'user_id' => \App\Models\User::factory(),
            'scholarshipType' => $this->faker->word(),
            'status' => $this->faker->word(),
        ];
    }
}
