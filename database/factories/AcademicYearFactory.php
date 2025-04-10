<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class AcademicYearFactory extends Factory
{
    protected $model = \App\Models\AcademicYear::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'school_year_id' => fake()->word(),
            'semester' => $this->faker->word(),
            'status' => fake()->word(),
        ];
    }
}
