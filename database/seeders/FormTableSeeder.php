<?php

namespace Database\Seeders;

use App\Models\ScholarshipForm;
use App\Models\ScholarshipFormData;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\DB;

class FormTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Scholarship Forms -- Marital Status of Parents
        ScholarshipForm::create([
            'name' => 'Marital Status of Parents',
        ]);

        ScholarshipFormData::create([
            'name' => 'Married',
            'scholarship_form_id' => 1
        ]);

        ScholarshipFormData::create([
            'name' => 'Not Married',
            'scholarship_form_id' => 1
        ]);

        ScholarshipFormData::create([
            'name' => 'Living Together',
            'scholarship_form_id' => 1
        ]);

        ScholarshipFormData::create([
            'name' => 'Separated',
            'scholarship_form_id' => 1
        ]);

        //Scholarship Forms -- Monthly Family Income
        ScholarshipForm::create([
            'name' => 'Monthly Family Income',
        ]);

        ScholarshipFormData::create([
            'name' => '10,000 and below',
            'scholarship_form_id' => 2
        ]);

        ScholarshipFormData::create([
            'name' => '10,001 - 20,000',
            'scholarship_form_id' => 2
        ]);

        ScholarshipFormData::create([
            'name' => '20,001 - 30,000',
            'scholarship_form_id' => 2
        ]);

        ScholarshipFormData::create([
            'name' => '30,001 and above',
            'scholarship_form_id' => 2
        ]);

        //Scholarship Forms -- Family Type of Housing
        ScholarshipForm::create([
            'name' => 'Family Type of Housing',
        ]);

        ScholarshipFormData::create([
            'name' => 'Owned',
            'scholarship_form_id' => 3
        ]);

        ScholarshipFormData::create([
            'name' => 'Settler',
            'scholarship_form_id' => 3
        ]);

        ScholarshipFormData::create([
            'name' => 'Rental',
            'scholarship_form_id' => 3
        ]);

        ScholarshipFormData::create([
            'name' => 'Others, please specify:',
            'scholarship_form_id' => 3
        ]);
    }
}
