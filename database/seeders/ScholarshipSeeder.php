<?php

namespace Database\Seeders;

use App\Models\Scholarship;
use App\Models\Sponsor;
use App\Models\SponsorMoa;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScholarshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'sponsor3',
            'email' => 'samsungsponsor@gmail.com',
            'first_name' => 'Jose',
            'last_name' => 'Manalo',
            'middle_name' => 'De Guzman',
            'password' => bcrypt('password'),
            'usertype' => 'sponsor',
            'campus_id' => '1',
            'age' => '25',
            'address' => 'Antipolo, Rizal',
            'contact' => '09999999999',
            'status' => 'Active',
            'picture' => 'ched_pic.jpg',
        ]);

        Sponsor::factory()->create([
            'name' => 'Samsung Scholraship',
            'created_id' => 2,
            'assign_id' => 7,
            'abbreviation' => 'SSS',
            'since' => '2001',
            'description' => 'A flagship CSR initiative of the Development Bank of the Philippines (DBP), provides financial assistance to underprivileged high school graduates, aiming to improve their lives and contribute to their development as productive members of society.',
            'logo' => 'dbp.webp',
        ]);

        SponsorMoa::factory()->create([
            'sponsor_id' => '3',
            'moa' => 'moa1.pdf',
            'validity' => '2026-11-03',
            'status' => 'Active',
        ]);

        //scholarship
        Scholarship::factory()->create([
            'name' => 'Samsung Scholarship Program',
            'sponsor_id' => 1,
            'user_id' => 2,
            'scholarshipType' => 'Grant-Based',
            'status' => 'Pending',
        ]);

        // Scholarship::factory()->create([
        //     'name' => 'DBP-RISE',
        //     'sponsor_id' => 2,
        //     'user_id' => 2,
        //     'scholarshipType' => 'Grant-Based',
        //     'status' => 'Pending',
        // ]);
    }
}
