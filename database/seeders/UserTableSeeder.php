<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\DB;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            //Coordinator
            [
                'name' => 'coordinator1',
                'email' => 'coor1@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'coordinator'
            ],

            //Student
            [
                'name' => 'student1',
                'email' => 'student1@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'student'
            ]
        ]
    );
    }
}
