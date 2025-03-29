<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Eligibility>
 */
class EligibilityFactory extends Factory
{
    protected $model = \App\Models\Eligibility::class;
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'scholarship_id' => \App\Models\Scholarship::factory(),
            'name' => $this->faker->name(),
        ];
    }
}
