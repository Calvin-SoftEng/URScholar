<?php

namespace Database\Seeders;

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
        // User::factory(2)->create();
        //MIS
        User::factory()->create([
            'name' => 'mis1',
            'email' => 'mis1@gmail.com',
            'first_name' => 'John Paul',
            'last_name' => 'Manalo',
            'middle_name' => 'De Guzman',
            'password' => bcrypt('password'),
            'usertype' => 'system_admin'
        ]);

        //Super Admin
        User::factory()->create([
            'name' => 'superadmin1',
            'email' => 'sadmin1@gmail.com',
            'first_name' => 'John Paul',
            'last_name' => 'Manalo',
            'middle_name' => 'De Guzman',
            'password' => bcrypt('password'),
            'usertype' => 'super_admin'
        ]);


        //coordinator
        User::factory()->create([
            'name' => 'coordinator1',
            'email' => 'coor1@gmail.com',
            'first_name' => 'John Paul',
            'last_name' => 'Manalo',
            'middle_name' => 'De Guzman',
            'password' => bcrypt('password'),
            'usertype' => 'coordinator'
        ]);

        User::factory()->create([
            'name' => 'coordinator12',
            'email' => 'coor2@gmail.com',
            'first_name' => 'Rublyn',
            'last_name' => 'IDK',
            'middle_name' => 'Maribujoc',
            'password' => bcrypt('password'),
            'usertype' => 'coordinator'
        ]);

        //cashier
        User::factory()->create([
            'name' => 'cashier1',
            'email' => 'cashier1@gmail.com',
            'first_name' => 'John Paul',
            'last_name' => 'Manalo',
            'middle_name' => 'De Guzman',
            'password' => bcrypt('password'),
            'usertype' => 'cashier'
        ]);

        //student
        User::factory()->create([
            'name' => 'student1',
            'email' => 'student1@gmail.com',
            'first_name' => 'Carl Vincent',
            'last_name' => 'Catahimican',
            'middle_name' => 'Soriano',
            'password' => bcrypt('password'),
            'usertype' => 'student'
        ]);


        $logopath = 'storage/sponsor/logo/images.png';
        //sponsor
        Sponsor::factory()->create([
            'name' => 'sponsor1',
            'abbreviation' => 'SP1',
            'since' => '2021-01-01',
            'moa_file' => 'moa1.pdf',
            'description' => 'sponsor1',
            'logo' => 'images.png',
        ]);

        //sponsor 
        // $this->call(UserTableSeeder::class);
    }
}
