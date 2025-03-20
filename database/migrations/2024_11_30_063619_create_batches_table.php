<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholarship_id')->constrained()->onDelete('cascade');
            $table->integer('batch_no');
            $table->string('school_year');
            $table->string('semester');
            $table->timestamps();
        });

        Schema::create('scholars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholarship_id')->constrained()->onDelete('cascade');
            $table->string('hei_name');
            $table->foreignId('campus_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('grant');
            $table->foreignId('batch_id')->constrained()->onDelete('cascade');
            $table->string('urscholar_id')->unique();
            $table->string('qr_code')->nullable();
            $table->string('app_no');
            $table->string('award_no');
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
            $table->timestamps();
        });

        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholar_id')->constrained()->onDelete('cascade');
            $table->decimal('grade')->nullable();
            $table->string('cog')->nullable();
            $table->string('path');
            $table->string('school_year');
            $table->string('semester');
            $table->timestamps();
        });

        Schema::create('grantees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholarship_id')->constrained()->onDelete('cascade');
            $table->foreignId('batch_id')->constrained()->onDelete('cascade');
            $table->foreignId('scholar_id')->constrained()->onDelete('cascade');
            $table->string('school_year');
            $table->string('semester');
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();
        });

        Schema::create('submitted_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholar_id')->constrained()->onDelete('cascade');
            $table->foreignId('requirement_id')->constrained()->onDelete('cascade');
            $table->string('submitted_requirements');
            $table->string('path');
            $table->string('message')->nullable();
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
        Schema::dropIfExists('grantees');
        Schema::dropIfExists('grades');
        Schema::dropIfExists('scholars');
        Schema::dropIfExists('batches');
    }
};
