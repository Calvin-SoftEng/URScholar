<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SponsorMoa>
 */
class SponsorMoaFactory extends Factory
{
    protected $model = \App\Models\SponsorMoa::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sponsor_id' => \App\Models\Sponsor::factory(),
            'moa' => $this->faker->word(),
            'status' => $this->faker->word(),
        ];
    }
}
