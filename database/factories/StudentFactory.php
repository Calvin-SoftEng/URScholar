<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $rizalCities = [
            'Antipolo City', 'Binangonan', 'Cainta', 'Taytay', 'Angono', 'Morong',
            'Rodriguez', 'San Mateo', 'Baras', 'Cardona', 'Jalajala', 'Pililla',
            'Tanay', 'Teresa'
        ];

        $philippineStreetNames = [
            'Mabini St.', 'Rizal St.', 'Bonifacio St.', 'Del Pilar St.', 'Luna St.',
            'Quezon Ave.', 'Aguinaldo Hwy', 'Roxas Blvd.', 'Katipunan Ave.', 'Burgos St.',
            'San Juan St.', 'P. Gomez St.', 'Gen. Luna St.', 'E. Rodriguez Ave.'
        ];

        $city = fake()->randomElement($rizalCities);
        $street = fake()->buildingNumber() . ' ' . fake()->randomElement($philippineStreetNames);

        return [
            'student_number'     => 'M' . fake()->year() . '-' . fake()->numberBetween(1000, 9999),
            'first_name'         => fake()->firstName(),
            'last_name'          => fake()->lastName(),
            'email'              => fake()->unique()->safeEmail(),
            'campus_id'          => 1, // Fixed
            'course_id'          => 4, // Fixed
            'year_level'         => '4', // Fixed
            'semester'           => fake()->randomElement(['1st', '2nd']),
            'age'                => fake()->numberBetween(18, 25),
            'religion'           => fake()->randomElement(['Catholic', 'Christian', 'Muslim', 'Iglesia ni Cristo']),
            'birthplace'         => $city . ', Rizal, Philippines',
            'birthdate'          => fake()->date('Y-m-d', '2007-12-31'),
            'civil_status'       => fake()->randomElement(['Single', 'Married', 'Divorced']),
            'permanent_address'  => $street . ', ' . $city . ', Rizal, Philippines',
            'facebook_account'   => strtolower(fake()->firstName()) . fake()->numberBetween(10, 99),
            'contact_no'         => '+63 ' . fake()->numberBetween(900, 999) . ' ' . fake()->numberBetween(100, 999) . ' ' . fake()->numberBetween(1000, 9999),
            'academic_year_id'   => 3, // Fixed
            'created_at'         => now(),
            'updated_at'         => now(),
        ];
    }
}
