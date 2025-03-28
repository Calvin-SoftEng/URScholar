<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Campus>
 */
class CampusFactory extends Factory
{

    protected $model = \App\Models\Campus::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(), // Using company name instead of a person's name
            'location' => $this->faker->city(), // More realistic location
            'coordinator_id' => User::inRandomOrder()->value('id'), // Select random user ID
            'cashier_id' => User::inRandomOrder()->value('id'), // Select random user ID
        ];
    }
}
