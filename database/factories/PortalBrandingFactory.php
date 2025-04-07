<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PortalBranding>
 */
class PortalBrandingFactory extends Factory
{

    protected $model = \App\Models\PortalBranding::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'logo_light' => $this->faker->words(),
            'light_path' => $this->faker->words(),
            'logo_dark' => $this->faker->words(),
            'dark_path' => $this->faker->words(),
            'branding_name' => $this->faker->words(),
            'favicon' => $this->faker->words(),
            'favicon_path' => $this->faker->words(),
            'status' => $this->faker->words(),
        ];
    }
}
