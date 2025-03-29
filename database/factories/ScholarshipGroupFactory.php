<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ScholarshipGroup>
 */
class ScholarshipGroupFactory extends Factory
{
    protected $model = \App\Models\ScholarshipGroup::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'scholarship_id' => \App\Models\Scholarship::factory(),
        ];
    }
}
