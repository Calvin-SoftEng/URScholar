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
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholarship_id')->constrained()->onDelete('cascade');
            $table->integer('batch_no');
            $table->foreignId('school_year_id')->constrained()->onDelete('cascade');
            $table->string('semester');
            $table->foreignId('campus_id')->constrained()->onDelete('cascade');
            $table->string('total_scholars')->nullable();
            $table->string('sub_total')->nullable();
            $table->enum('status', ['Active', 'Inactive', 'Pending'])->default('Pending');
            $table->boolean('validated')->default(false);
            $table->boolean('read')->default(false);
            $table->timestamps();
        });

        Schema::create('scholars', function (Blueprint $table) {
            $table->id();
            $table->string('student_number')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('hei_name');
            $table->foreignId('campus_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('grant')->nullable();
            $table->string('urscholar_id')->unique();
            $table->string('qr_code')->nullable();
            $table->string('app_no')->nullable();
            ;
            $table->string('award_no')->nullable();
            ;
            $table->string('last_name');
            $table->string('first_name');
            $table->string('extname')->nullable();
            $table->string('middle_name');
            $table->string('sex');
            $table->date('birthdate');
            $table->integer('year_level');
            $table->string('total_units')->nullable();
            $table->string('street');
            $table->string('municipality');
            $table->string('province');
            $table->string('pwd_classification')->nullable();
            $table->string('email')->nullable();
            $table->enum('status', ['Verified', 'Unverified'])->default('Unverified');
            $table->enum('student_status', ['Graduated', 'Enrolled', 'Unenrolled', 'Dropped'])->default('Unenrolled');
            $table->timestamps();
        });

        Schema::create('notifiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('message');
            $table->string('type');
            $table->boolean('read')->default(false);
            $table->timestamps();
        });

        //Scholars Grant-Based
        Schema::create('grantees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholarship_id')->constrained()->onDelete('cascade');
            $table->foreignId('batch_id')->constrained()->onDelete('cascade');
            $table->foreignId('scholar_id')->constrained()->onDelete('cascade');
            $table->foreignId('school_year_id')->constrained()->onDelete('cascade');
            $table->string('semester');
            $table->enum('student_status', ['Graduated', 'Enrolled', 'Unenrolled', 'Dropped'])->default('Unenrolled');
            $table->enum('status', ['Active', 'Inactive', 'Pending', 'Accomplished'])->default('Pending');
            $table->timestamps();
        });

        //Scholars One-Time
        Schema::create('applicant_tracks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholarship_id')->constrained()->onDelete('cascade');
            $table->foreignId('campus_id')->constrained()->onDelete('cascade');
            $table->foreignId('school_year_id')->constrained()->onDelete('cascade');
            $table->string('semester');
            $table->enum('status', ['Active', 'Inactive', 'Pending'])->default('Pending');
            $table->timestamps();
        });

        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholarship_id')->constrained()->onDelete('cascade');
            $table->foreignId('applicant_track_id')->constrained()->onDelete('cascade');
            $table->foreignId('scholar_id')->constrained()->onDelete('cascade');
            $table->foreignId('school_year_id')->constrained()->onDelete('cascade');
            $table->string('essay');
            $table->string('semester');
            $table->datetime('validated_date')->nullable();
            $table->enum('status', ['Approved', 'Rejected', 'Pending'])->default('Pending');
            $table->timestamps();
        });

        Schema::create('submitted_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholar_id')->constrained()->onDelete('cascade');
            $table->foreignId('requirement_id')->constrained()->onDelete('cascade');
            $table->string('submitted_requirements');
            $table->string('path');
            $table->string('message')->nullable();
            $table->datetime('validated_date')->nullable();
            $table->enum('status', ['Approved', 'Pending', 'Returned'])->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submitted_requirements');
        Schema::dropIfExists('applicants');
        Schema::dropIfExists('applicant_tracks');
        Schema::dropIfExists('grantees');
        Schema::dropIfExists('notifiers');
        Schema::dropIfExists('scholars');
        Schema::dropIfExists('batches');
    }
};
