<?php

namespace Database\Seeders;

use App\Models\CampusRecipients;
use App\Models\Scholarship;
use App\Models\Sponsor;
use App\Models\SponsorMoa;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Carbon\Carbon;

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
            'name' => 'Samsung Scholarship',
            'created_id' => 2,
            'assign_id' => 11,
            'abbreviation' => 'SNU',
            'since' => '2001',
            'description' => 'A flagship CSR initiative of the Development Bank of the Philippines (DBP), provides financial assistance to underprivileged high school graduates, aiming to improve their lives and contribute to their development as productive members of society.',
            'logo' => 'samsung.webp',
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
            'sponsor_id' => 3,
            'user_id' => 2,
            'scholarshipType' => 'Grant-Based',
            'status' => 'Pending',
        ]);

        Scholarship::factory()->create([
            'name' => 'Kim Jin Foundation',
            'sponsor_id' => 3,
            'user_id' => 2,
            'scholarshipType' => 'One-time Payment',
            'status' => 'Active',
        ]);

        $applicant_tracksM = DB::table('applicant_tracks')->insertGetId([
            'scholarship_id' => 4,
            'campus_id' => 1,
            'school_year_id' => 3,
            'semester' => '1st',
            'status' => 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $applicant_tracksP = DB::table('applicant_tracks')->insertGetId([
            'scholarship_id' => 4,
            'campus_id' => 4,
            'school_year_id' => 3,
            'semester' => '1st',
            'status' => 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        $campusReceipientsM = DB::table('campus_recipients')->insertGetId([
            'scholarship_id' => 4,
            'campus_id' => 1,
            'slots' => 25,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $campusReceipientsP = DB::table('campus_recipients')->insertGetId([
            'scholarship_id' => 4,
            'campus_id' => 4,
            'slots' => 25,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $criterias = DB::table('criterias')->insertGetId([
            'scholarship_id' => 4,
            'scholarship_form_data_id' => 5,
            'grade' => 1.9,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $requirements = DB::table('requirements')->insertGetId([
            'scholarship_id' => 4,
            'requirements' => 'COR',
            'date_start' => '2025-11-06',
            'date_end' => '2025-11-29',
            'total_scholars' => 50,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $faker = Faker::create('en_PH');

        // Arrays for data generation
        $religions = [
            'Roman Catholic',
            'Iglesia ni Cristo',
            'Born Again Christian',
            'Islam',
            'Buddhist',
            'Hindu',
            'Atheist',
            'Agnostic',
            'Jehovah\'s Witness'
        ];

        $townsInRizal = [
            'Pililla, Rizal',
            'Morong, Rizal',
            'Tanay, Rizal',
            'Cardona, Rizal',
            'Binangonan, Rizal',
            'Cainta, Rizal',
            'Baras, Rizal',
            'Teresa, Rizal'
        ];

        $civilStatus = ['Single', 'Single', 'Single', 'Married'];
        $genders = ['Male', 'Female'];
        $grants = ['Full', 'Partial', 'Merit-based'];
        $studentStatuses = ['Enrolled', 'Enrolled', 'Enrolled', 'Graduated', 'Unenrolled'];

        // ========================================
        // PART 1: CREATE USERS AND SCHOLARS TOGETHER
        // ========================================
        $this->command->info('Creating users and scholars...');

        $password = Hash::make('password');

        foreach (range(1, 50) as $index) {
            // Generate student number
            $year = $faker->numberBetween(2020, 2023);
            $randomNum = $faker->numberBetween(100, 9999);
            $studentNo = "P{$year}-{$randomNum}";

            // Generate birthdate and calculate age
            $birthDateObj = $faker->dateTimeBetween('-25 years', '-18 years');
            $birthDateString = $birthDateObj->format('Y-m-d');
            $age = $birthDateObj->diff(new \DateTime())->y;

            $firstName = $faker->firstName;
            $lastName = $faker->lastName;
            $middleName = $faker->lastName;
            $extName = $faker->optional(0.1)->randomElement(['Jr.', 'Sr.', 'III', 'IV']);
            $sex = $faker->randomElement($genders);

            // Extract municipality from town
            $selectedTown = $faker->randomElement($townsInRizal);
            $townParts = explode(', ', $selectedTown);
            $municipality = $townParts[0];
            $province = $townParts[1] ?? 'Rizal';

            $street = $faker->numberBetween(1, 999) . ' ' . $faker->streetName;
            $email = strtolower($firstName . substr($lastName, 0, 1) . $faker->numberBetween(1, 99) . '@student.edu.ph');
            $campusId = 4;
            $fullAddress = $street . ', ' . $municipality . ', ' . $province;

            // A. Create User Account FIRST
            $userId = DB::table('users')->insertGetId([
                'name' => $firstName . ' ' . $lastName,
                'email' => $email,
                'usertype' => 'student',
                'password' => $password,
                'email_verified_at' => now(),
                'first_name' => $firstName,
                'last_name' => $lastName,
                'middle_name' => $middleName,
                'suffix_name' => $extName,
                'campus_id' => $campusId,
                'address' => $fullAddress,
                'contact' => '09' . $faker->numberBetween(100000000, 999999999),
                'status' => 'Active',
                'picture' => 'men.png',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // B. Create Scholar with user_id
            DB::table('scholars')->insert([
                'student_number' => $studentNo,
                'user_id' => $userId, // NOW WE HAVE THE USER ID
                'hei_name' => 'University of Rizal System',
                'campus_id' => $campusId,
                'course_id' => 8,
                'grant' => null,
                'urscholar_id' => 'URS' . $year . str_pad($index, 5, '0', STR_PAD_LEFT),
                'qr_code' => null,
                'app_no' => null,
                'award_no' => null,
                'last_name' => $lastName,
                'first_name' => $firstName,
                'extname' => $extName,
                'middle_name' => $middleName,
                'sex' => $sex,
                'birthdate' => $birthDateString,
                'year_level' => $faker->numberBetween(1, 4),
                'total_units' => null,
                'street' => $street,
                'municipality' => $municipality,
                'province' => $province,
                'pwd_classification' => $faker->optional(0.05)->randomElement(['Visual Impairment', 'Hearing Impairment', 'Physical Disability']),
                'email' => $email,
                'status' => 'Verified',
                'student_status' => $faker->randomElement($studentStatuses),
                'apply_scholarship' => 'null',
                'endorsed_scholarship' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ========================================
        // PART 2: CREATE STUDENTS TABLE RECORDS (CONNECTED TO SCHOLARS)
        // ========================================
        $this->command->info('Creating students table records linked to scholars...');

        // Get all scholars we just created
        $createdScholars = DB::table('scholars')->get();

        foreach ($createdScholars as $scholar) {
            // Convert birthdate format for students table (Y-m-d to n/j/Y)
            $birthDateObj = Carbon::parse($scholar->birthdate);
            $birthDateString = $birthDateObj->format('n/j/Y'); // Example: 7/18/2007
            $age = $birthDateObj->age;

            // Map year_level integer to string format
            $yearLevelMap = [
                1 => '1st',
                2 => '2nd',
                3 => '3rd',
                4 => '4th'
            ];

            DB::table('students')->insert([
                'student_number' => $scholar->student_number,
                'first_name' => $scholar->first_name,
                'last_name' => $scholar->last_name,
                'email' => $scholar->email,
                'campus_id' => $scholar->campus_id, // Same campus as scholar
                'course_id' => $scholar->course_id, // Same course as scholar
                'academic_year_id' => $faker->numberBetween(1, 5), // Random academic year
                'year_level' => $yearLevelMap[$scholar->year_level] ?? '1st',
                'semester' => '1st',
                'age' => (string)$age,
                'religion' => $faker->randomElement($religions),
                'birthplace' => $scholar->municipality . ', ' . $scholar->province,
                'birthdate' => $birthDateString,
                'civil_status' => $faker->randomElement($civilStatus),
                'permanent_address' => $scholar->street . ', ' . $scholar->municipality . ', ' . $scholar->province,
                'facebook_account' => $scholar->first_name . ' ' . $scholar->last_name,
                'contact_no' => '+63 9' . $faker->numberBetween(10, 99) . ' ' . $faker->numberBetween(100, 999) . ' ' . $faker->numberBetween(1000, 9999),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ========================================
        // PART 3: CREATE STUDENT RECORDS AND RELATED DATA
        // ========================================
        $this->command->info('Creating student records and related data for scholars...');

        $scholars = DB::table('scholars')->get();
        $academicYearIds = DB::table('academic_years')->pluck('id')->toArray();

        if ($scholars->isEmpty()) {
            $this->command->error("No scholars found!");
            return;
        }

        foreach ($scholars as $scholar) {
            // Calculate age
            $age = $scholar->birthdate ? Carbon::parse($scholar->birthdate)->age : 18;

            $applicant = DB::table('applicants')->insertGetId([
                'scholarship_id' => 4,
                'applicant_track_id' => 2,
                'scholar_id' => $scholar->id,
                'school_year_id' => 3,
                'essay' => "Thank you po!",
                'semester' => "1st",
                'validated_date' => now(),
                'status' => "Approved",
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $submitted_requirements = DB::table('submitted_requirements')->insertGetId([
                'scholar_id' => $scholar->id,
                'requirement_id' => 1,
                'submitted_requirements' => "Catahimican, Carl Vincent Unlocked.docx",
                'path' => "requirements/51/6zxGJi851wk7fZCQ48Gs1SYiIaJtHYZHaK6pSlpg.docx",
                'validated_date' => now(),
                'status' => 'Approved',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create Student Record
            $recordId = DB::table('student_records')->insertGetId([
                'scholar_id' => $scholar->id,
                'first_name' => $scholar->first_name,
                'last_name' => $scholar->last_name,
                'middle_name' => $scholar->middle_name,
                'suffix_name' => $scholar->extname,
                'birthdate' => $scholar->birthdate,
                'placebirth' => $faker->city . ', Rizal',
                'age' => $age,
                'gender' => $scholar->sex,
                'civil' => $faker->randomElement(['Single', 'Married']),
                'religion' => $faker->randomElement($religions),
                'guardian' => $faker->name,
                'relationship' => 'Parent',
                'has_other_scholarship' => 'No',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Family Record
            DB::table('family_records')->insert([
                'student_record_id' => $recordId,
                'mother' => json_encode(['name' => $faker->name('female'), 'occupation' => 'Housewife']),
                'father' => json_encode(['name' => $faker->name('male'), 'occupation' => 'Farmer']),
                'marital_status' => 'Married',
                'monthly_income' => 'Below 10,000',
                'family_housing' => 'Owned',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Education Record
            DB::table('education_records')->insert([
                'student_record_id' => $recordId,
                'elementary' => json_encode(['school' => $scholar->municipality . ' Elem', 'year' => '2012']),
                'junior' => json_encode(['school' => $scholar->municipality . ' High', 'year' => '2016']),
                'senior' => json_encode(['school' => $scholar->municipality . ' Senior', 'year' => '2018']),
                'college' => json_encode(['school' => 'URS', 'course' => 'Course ' . $scholar->course_id]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Grades
            if (!empty($academicYearIds)) {
                DB::table('grades')->insert([
                    'scholar_id' => $scholar->id,
                    'academic_year_id' => 5,
                    'grade' => $faker->randomFloat(2, 1.0, 3.0),
                    'cog' => 'Passed',
                    'path' => 'grades/sample.pdf',
                    'school_year_id' => 2,
                    'semester' => '2nd',
                    'status' => 'Active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('Seeding completed successfully!');
    }
}
