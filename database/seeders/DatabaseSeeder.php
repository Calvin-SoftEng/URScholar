<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Campus;
use App\Models\Condition;
use App\Models\Course;
use App\Models\Eligibility;
use App\Models\PortalBranding;
use App\Models\Scholarship;
use App\Models\ScholarshipForm;
use App\Models\ScholarshipFormData;
use App\Models\ScholarshipGroup;
use App\Models\SchoolYear;
use App\Models\Sponsor;
use App\Models\SponsorMoa;
use App\Models\StaffGroup;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use StaffGroupUser;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        PortalBranding::factory()->create([
            'logo_light' => 'main_logo_white.webp',
            'light_path' => 'storage/branding/logos/main_logo_white.webp',
            'logo_dark' => 'main_logo.webp',
            'dark_path' => 'storage/branding/logos/main_logo.webp',
            'branding_name' => 'URScholar',
            'favicon' => 'web_logo.webp',
            'favicon_path' => 'storage/branding/favicons/web_logo.webp',
            'status' => 'Active',
        ]);

        // User::factory(2)->create();
        //MIS
        User::factory()->create([
            'name' => 'mis1',
            'email' => 'mis1@gmail.com',
            'first_name' => 'Marvin',
            'last_name' => 'Baquiran',
            'middle_name' => 'N/A',
            'password' => bcrypt('password'),
            'usertype' => 'system_admin',
            'campus_id' => '1',
            'age' => '25',
            'address' => 'Morong, Rizal',
            'contact' => '09999999999',
            'status' => 'Active',
            'picture' => 'men.png',
        ]);

        //Super Admin
        User::factory()->create([
            'name' => 'superadmin1',
            'email' => 'headadmin@gmail.com',
            'first_name' => 'Roslyn',
            'last_name' => 'Magat',
            'middle_name' => 'B',
            'password' => bcrypt('password'),
            'usertype' => 'super_admin',
            'campus_id' => '1',
            'age' => '25',
            'address' => 'Morong, Rizal',
            'contact' => '09999999999',
            'status' => 'Active',
            'picture' => 'head_admin_pic.jpg',
        ]);


        //coordinator
        User::factory()->create([
            'name' => 'coordinator1',
            'email' => 'bcoor@gmail.com',
            'first_name' => 'Baby Eunice',
            'last_name' => 'Cabaltera',
            'middle_name' => 'N/A',
            'password' => bcrypt('password'),
            'usertype' => 'coordinator',
            'campus_id' => '2',
            'age' => '25',
            'address' => 'Morong, Rizal',
            'contact' => '09999999999',
            'status' => 'Active',
            'picture' => 'bcoor_pic.jpg',
        ]);

        User::factory()->create([
            'name' => 'coordinator12',
            'email' => 'pcoor@gmail.com',
            'first_name' => 'Keith Ashley',
            'last_name' => 'Cruz',
            'middle_name' => 'Dolorian',
            'password' => bcrypt('password'),
            'usertype' => 'coordinator',
            'campus_id' => '4',
            'age' => '25',
            'address' => 'Morong, Rizal',
            'contact' => '09999999999',
            'status' => 'Active',
            'picture' => 'pcoor_pic.png',
        ]);

        //sponsor
        User::factory()->create([
            'name' => 'sponsor1',
            'email' => 'chedsponsor@gmail.com',
            'first_name' => 'Calvin',
            'last_name' => 'Manalo',
            'middle_name' => 'De Guzman',
            'password' => bcrypt('password'),
            'usertype' => 'sponsor',
            'campus_id' => '1',
            'age' => '25',
            'address' => 'Morong, Rizal',
            'contact' => '09999999999',
            'status' => 'Active',
            'picture' => 'ched_pic.jpg',
        ]);

        User::factory()->create([
            'name' => 'sponsor2',
            'email' => 'dbpsponsor@gmail.com',
            'first_name' => 'Jose',
            'last_name' => 'Manalo',
            'middle_name' => 'De Guzman',
            'password' => bcrypt('password'),
            'usertype' => 'sponsor',
            'campus_id' => '1',
            'age' => '25',
            'address' => 'Morong, Rizal',
            'contact' => '09999999999',
            'status' => 'Active',
            'picture' => 'ched_pic.jpg',
        ]);

        //cashier
        User::factory()->create([
            'name' => 'head_cashier1',
            'email' => 'headcashier1@gmail.com',
            'first_name' => 'Eva',
            'last_name' => 'Natividad',
            'middle_name' => 'N/A',
            'password' => bcrypt('password'),
            'usertype' => 'head_cashier',
            'campus_id' => '1',
            'age' => '25',
            'address' => 'Morong, Rizal',
            'contact' => '09999999999',
            'status' => 'Active',
            'picture' => 'univ_cashier_pic.jpg',
        ]);

        User::factory()->create([
            'name' => 'cashier1',
            'email' => 'bcashier@gmail.com',
            'first_name' => 'Denise Ann',
            'last_name' => 'Lopez',
            'middle_name' => 'N/A',
            'password' => bcrypt('password'),
            'usertype' => 'cashier',
            'campus_id' => '2',
            'age' => '25',
            'address' => 'Morong, Rizal',
            'contact' => '09999999999',
            'status' => 'Active',
            'picture' => 'bcashier_pic.jpg',
        ]);

        User::factory()->create([
            'name' => 'cashier2',
            'email' => 'mcashier@gmail.com',
            'first_name' => 'Oliver John',
            'last_name' => 'Cayas',
            'middle_name' => 'N/A',
            'password' => bcrypt('password'),
            'usertype' => 'cashier',
            'campus_id' => '1',
            'age' => '25',
            'address' => 'Morong, Rizal',
            'contact' => '09999999999',
            'status' => 'Active',
            'picture' => 'men.png',
        ]);

        //student
        // User::factory()->create([
        //     'name' => 'student1',
        //     'email' => 'student1@gmail.com',
        //     'first_name' => 'Carl Vincent',
        //     'last_name' => 'Catahimican',
        //     'middle_name' => 'Soriano',
        //     'password' => bcrypt('password'),
        //     'usertype' => 'student',
        //     'campus_id' => '1',
        //     'status' => 'Active',
        // ]);

        //Campus
        Campus::create([
            'name' => 'Morong',
            'location' => 'Sumulong St, Morong, Rizal',
            'coordinator_id' => '2',
            'cashier_id' => '8',
        ]);

        Campus::create([
            'name' => 'Binangonan',
            'location' => '601: Manila East Rd, Binangonan, Philippines',
            'coordinator_id' => '3',
            'cashier_id' => '7',
        ]);

        Campus::create([
            'name' => 'Taytay',
            'location' => ' A. Luna Street, Highway 2000 Brgy. San Juan, Taytay, Rizal',
            // 'coordinator_id' => '3',
            // 'cashier_id' => '6',
        ]);

        Campus::create([
            'name' => 'Pililla',
            'location' => 'Dampol Street, Manaila East Road, Bagumbayan, Pililia, Rizal',
            // 'coordinator_id' => '4',
            // 'cashier_id' => '7',
        ]);


        $logopath = 'storage/sponsor/logo/images.png';
        //sponsor
        Sponsor::factory()->create([
            'name' => 'Commissioner on Higher Education',
            'created_id' => 2,
            'assign_id' => 5,
            'abbreviation' => 'CHED',
            'since' => '2021',
            'description' => 'CHED Scholarships provide financial assistance to deserving Filipino students in higher education. These include merit-based and need-based grants covering tuition, allowances, and other school-related expenses to support academic excellence and accessibility to quality education.',
            'logo' => 'images.png',
        ]);

        SponsorMoa::factory()->create([
            'sponsor_id' => '1',
            'moa' => 'moa1.pdf',
            'validity' => '2025-09-03',
            'status' => 'Active',
        ]);

        Sponsor::factory()->create([
            'name' => 'Development Bank of the Philippines',
            'created_id' => 2,
            'assign_id' => 6,
            'abbreviation' => 'DBP',
            'since' => '2001',
            'description' => 'A flagship CSR initiative of the Development Bank of the Philippines (DBP), provides financial assistance to underprivileged high school graduates, aiming to improve their lives and contribute to their development as productive members of society.',
            'logo' => 'dbp.webp',
        ]);

        SponsorMoa::factory()->create([
            'sponsor_id' => '2',
            'moa' => 'moa1.pdf',
            'validity' => '2025-09-03',
            'status' => 'Active',
        ]);

        //scholarship
        Scholarship::factory()->create([
            'name' => 'Tulong Dunong Program',
            'sponsor_id' => 1,
            'user_id' => 2,
            'scholarshipType' => 'Grant-Based',
            'status' => 'Pending',
        ]);

        Scholarship::factory()->create([
            'name' => 'DBP-RISE',
            'sponsor_id' => 2,
            'user_id' => 2,
            'scholarshipType' => 'Grant-Based',
            'status' => 'Pending',
        ]);

        //school year
        // SchoolYear::factory()->create([
        //     'year' => '2021-2022',
        // ]);

        SchoolYear::factory()->create([
            'year' => '2023-2024',
        ]);

        SchoolYear::factory()->create([
            'year' => '2024-2025',
        ]);

        SchoolYear::factory()->create([
            'year' => '2025-2026',
        ]);

        //Course
        // morong
        // binangonan
        // taytay
        // pililla
        Course::create([
            'campus_id' => 2,
            'name' => 'Bachelor of Science in Information Technology',
            'abbreviation' => 'BSIT',
        ]);

        Course::create([
            'campus_id' => 2,
            'name' => 'Bachelor of Science in Business Administration',
            'abbreviation' => 'BSBA',
        ]);

        Course::create([
            'campus_id' => 2,
            'name' => 'Bachelor of Science in Accountancy',
            'abbreviation' => 'BSA',
        ]);

        Course::create([
            'campus_id' => 1,
            'name' => 'Bachelor of Science in Civil Engineering',
            'abbreviation' => 'BCE',
        ]);

        Course::create([
            'campus_id' => 1,
            'name' => 'Bachelor of Science in Mechanical Engineering',
            'abbreviation' => 'BME',
        ]);

        Course::create([
            'campus_id' => 1,
            'name' => 'Bachelor of Science in Electrical Engineering',
            'abbreviation' => 'BEE',
        ]);

        Course::create([
            'campus_id' => 1,
            'name' => 'Bachelor of Arts in English',
            'abbreviation' => 'BAE',
        ]);

        Course::create([
            'campus_id' => 4,
            'name' => 'Bachelor of Science in Secondary Education',
            'abbreviation' => 'BSEd',
        ]);

        Course::create([
            'campus_id' => 3,
            'name' => 'Bachelor of Science in Nursing',
            'abbreviation' => 'BSN',
        ]);

        Course::create([
            'campus_id' => 4,
            'name' => 'Bachelor of Science in Business Administrator',
            'abbreviation' => 'BSBA',
        ]);

        //Eligibilities

        Eligibility::create([
            'name' => 'Financial Need-Based Criteria',
        ]);

        Eligibility::create([
            'name' => 'Residency & Citizenship Criteria',
        ]);

        Eligibility::create([
            'name' => 'Community Involvement & Leadership Criteria',
        ]);

        Eligibility::create([
            'name' => 'Special Group Scholarships',
        ]);

        Condition::create([
            'eligibility_id' => 1,
            'name' => 'Must belong to a low-income household (based on government records)',
        ]);

        Condition::create([
            'eligibility_id' => 2,
            'name' => 'Must be a Filipino Citizen',
        ]);

        Condition::create([
            'eligibility_id' => 2,
            'name' => 'Must have lived in the community for at least 3 years',
        ]);

        Condition::create([
            'eligibility_id' => 3,
            'name' => 'Must hold an officer position in a student organization',
        ]);

        Condition::create([
            'eligibility_id' => 4,
            'name' => 'Open only to students with disabilities',
        ]);

        Condition::create([
            'eligibility_id' => 4,
            'name' => 'For single parents or children of single parents',
        ]);
        //Group Chat
        // ScholarshipGroup::create([
        //     'user_id' => 2,
        //     'scholarship_id' => 1,
        // ]);

        // ScholarshipGroup::create([
        //     'user_id' => 3,
        //     'scholarship_id' => 1,
        // ]);

        //academic year
        // AcademicYear::factory()->create([
        //     'school_year_id' => '1',
        //     'semester' => '1st',
        //     'status' => 'Inactive'
        // ]);

        // AcademicYear::factory()->create([
        //     'school_year_id' => '1',
        //     'semester' => '2nd',
        //     'status' => 'Inactive'
        // ]);

        AcademicYear::factory()->create([
            'school_year_id' => '1',
            'semester' => '1st',
            'status' => 'Inactive'
        ]);

        AcademicYear::factory()->create([
            'school_year_id' => '1',
            'semester' => '2nd',
            'status' => 'Inactive'
        ]);

        AcademicYear::factory()->create([
            'school_year_id' => '2',
            'semester' => '1st',
            'status' => 'Active'
        ]);

        StaffGroup::factory()->create([
            'name' => 'Scholarship Coordinators',
            'user_id' => '2',
        ]);

        StaffGroup::factory()->create([
            'name' => 'Scholarship Cashiers',
            'user_id' => '6',
        ]);


        \App\Models\StaffGroupUser::factory()->create([
            'user_id' => '2',
            'staff_group_id' => '1',
        ]);

        \App\Models\StaffGroupUser::factory()->create([
            'user_id' => '3',
            'staff_group_id' => '1',
        ]);

        \App\Models\StaffGroupUser::factory()->create([
            'user_id' => '4',
            'staff_group_id' => '1',
        ]);
        
        \App\Models\StaffGroupUser::factory()->create([
            'user_id' => '6',
            'staff_group_id' => '2',
        ]);

        \App\Models\StaffGroupUser::factory()->create([
            'user_id' => '7',
            'staff_group_id' => '2',
        ]);

        \App\Models\StaffGroupUser::factory()->create([
            'user_id' => '8',
            'staff_group_id' => '2',
        ]);


        //sponsor 
        $this->call(FormTableSeeder::class);
        $this->call(StudentSeeder::class);
    }
}
