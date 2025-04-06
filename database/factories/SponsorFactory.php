<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sponsor>
 */
class SponsorFactory extends Factory
{
    protected $model = \App\Models\Sponsor::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'created_id' => \App\Models\User::factory(),
            'assign_id' => \App\Models\User::factory(),
            'abbreviation' => $this->faker->word(),
            'since' => $this->faker->date(),
            'moa_file' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'logo' => $this->faker->word(),
        ];
    }
}
