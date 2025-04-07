<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('portal_brandings', function (Blueprint $table) {
            $table->id();
            $table->string('logo_light')->nullable();
            $table->string('light_path');
            $table->string('logo_dark')->nullable();
            $table->string('dark_path');
            $table->string('branding_name')->nullable();
            $table->string('favicon')->nullable();
            $table->string('favicon_path');
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();
        });
        
        Schema::create('campuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location');
            $table->unsignedBigInteger('coordinator_id')->nullable();
            $table->unsignedBigInteger('cashier_id')->nullable();

            $table->foreign('coordinator_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->foreign('cashier_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campus_id')->constrained();
            $table->string('name');
            $table->string('abbreviation');
            $table->timestamps();
        });

        Schema::create('school_years', function (Blueprint $table) {
            $table->id();
            $table->string('year');
            $table->timestamps();
        });

        Schema::create('academic_years', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_year_id')->constrained()->onDelete('cascade');
            $table->enum('semester', ['1st', '2nd'])->default('1st');
            $table->timestamps();
        });

        Schema::create('usertypes', function (Blueprint $table) {
            $table->id();
            $table->string('roles');
            $table->timestamps();
        });

        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_number');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->foreignId('campus_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('year_level');
            $table->string('semester');
            $table->string('age');
            $table->string('religion');
            $table->string('birthplace');
            $table->string('birthdate')->nullable();
            $table->string('civil_status')->nullable();
            $table->string('permanent_address')->nullable();
            $table->string('facebook_account')->nullable();
            $table->string('contact_no')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
        Schema::dropIfExists('usertypes');
        Schema::dropIfExists('academic_years');
        Schema::dropIfExists('school_years');
        Schema::dropIfExists('courses');
        Schema::dropIfExists('campuses');
        Schema::dropIfExists('portal_brandings');
    }
};
