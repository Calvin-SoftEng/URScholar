<?php

namespace Database\Seeders;

use App\Models\Campus;
use App\Models\Course;
use App\Models\Scholarship;
use App\Models\ScholarshipForm;
use App\Models\ScholarshipFormData;
use App\Models\ScholarshipGroup;
use App\Models\SchoolYear;
use App\Models\Sponsor;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //Campus
        Campus::create([
            'name' => 'Morong',
            'location' => 'Morong, Rizal',
        ]);

        Campus::create([
            'name' => 'Binangonan',
            'location' => 'Binangonan, Rizal',
        ]);

        // User::factory(2)->create();
        //MIS
        User::factory()->create([
            'name' => 'mis1',
            'email' => 'mis1@gmail.com',
            'first_name' => 'John Paul',
            'last_name' => 'Manalo',
            'middle_name' => 'De Guzman',
            'password' => bcrypt('password'),
            'usertype' => 'system_admin',
            'campus_id' => '1'
        ]);

        //Super Admin
        User::factory()->create([
            'name' => 'superadmin1',
            'email' => 'sadmin1@gmail.com',
            'first_name' => 'John Paul',
            'last_name' => 'Manalo',
            'middle_name' => 'De Guzman',
            'password' => bcrypt('password'),
            'usertype' => 'super_admin',
            'campus_id' => '1'
        ]);


        //coordinator
        User::factory()->create([
            'name' => 'coordinator1',
            'email' => 'coor1@gmail.com',
            'first_name' => 'John Paul',
            'last_name' => 'Manalo',
            'middle_name' => 'De Guzman',
            'password' => bcrypt('password'),
            'usertype' => 'coordinator',
            'campus_id' => '2'
        ]);

        User::factory()->create([
            'name' => 'coordinator12',
            'email' => 'coor2@gmail.com',
            'first_name' => 'Rublyn',
            'last_name' => 'IDK',
            'middle_name' => 'Maribujoc',
            'password' => bcrypt('password'),
            'usertype' => 'coordinator',
            'campus_id' => '1'
        ]);

        //sponsor
        User::factory()->create([
            'name' => 'sponsor1',
            'email' => 'sponsor1@gmail.com',
            'first_name' => 'Calvin',
            'last_name' => 'Manalo',
            'middle_name' => 'De Guzman',
            'password' => bcrypt('password'),
            'usertype' => 'sponsor',
            'campus_id' => '1'
        ]);

        //cashier
        User::factory()->create([
            'name' => 'cashier1',
            'email' => 'cashier1@gmail.com',
            'first_name' => 'John Paul',
            'last_name' => 'Manalo',
            'middle_name' => 'De Guzman',
            'password' => bcrypt('password'),
            'usertype' => 'cashier',
            'campus_id' => '1'
        ]);

        //student
        User::factory()->create([
            'name' => 'student1',
            'email' => 'student1@gmail.com',
            'first_name' => 'Carl Vincent',
            'last_name' => 'Catahimican',
            'middle_name' => 'Soriano',
            'password' => bcrypt('password'),
            'usertype' => 'student',
            'campus_id' => '1'
        ]);


        $logopath = 'storage/sponsor/logo/images.png';
        //sponsor
        Sponsor::factory()->create([
            'name' => 'Commissioner of Higher Education',
            'abbreviation' => 'CHED',
            'since' => '2021',
            'moa_file' => 'moa1.pdf',
            'description' => 'sponsor1',
            'logo' => 'images.png',
        ]);

        //scholarship
        Scholarship::factory()->create([
            'name' => 'Tulong Dunong Program',
            'sponsor_id' => 1,
            'scholarshipType' => 'Need-Based',
            'status' => 'Active',
            'date_start' => '2021-01-01',
            'date_end' => '2021-12-31',
        ]);

        Scholarship::factory()->create([
            'name' => 'DBP-Rise',
            'sponsor_id' => 1,
            'scholarshipType' => 'One-time Payment',
            'status' => 'Active',
            'date_start' => '2021-01-01',
            'date_end' => '2021-12-31',
        ]);

        //school year
        SchoolYear::factory()->create([
            'year' => '2021-2022',
        ]);

        SchoolYear::factory()->create([
            'year' => '2022-2023',
        ]);

        SchoolYear::factory()->create([
            'year' => '2024-2025',
        ]);

        SchoolYear::factory()->create([
            'year' => '2025-2026',
        ]);

        //Course
        Course::create([
            'campus_id' => 1,
            'name' => 'Bachelor of Science in Information Technology',
            'abbreviation' => 'BSIT',
        ]);

        Course::create([
            'campus_id' => 2,
            'name' => 'Bachelor of Science in Business Administrator',
            'abbreviation' => 'BSBA',
        ]);

        //Group Chat
        ScholarshipGroup::create([
            'user_id' => 2,
            'scholarship_id' => 1,
        ]);

        ScholarshipGroup::create([
            'user_id' => 3,
            'scholarship_id' => 1,
        ]);


        //sponsor 
        $this->call(FormTableSeeder::class);
    }
}
