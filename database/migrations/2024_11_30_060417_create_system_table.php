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

        Schema::create('usertypes', function (Blueprint $table) {
            $table->id();
            $table->string('roles');
            $table->timestamps();
        });

        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('course');
            $table->string('campus');
            $table->string('year_level');
            $table->string('semester');
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
        Schema::dropIfExists('school_years');
        Schema::dropIfExists('courses');
        Schema::dropIfExists('campuses');
    }
};
