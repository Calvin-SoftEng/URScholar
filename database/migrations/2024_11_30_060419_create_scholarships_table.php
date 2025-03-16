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
        Schema::create('scholarships', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('sponsor_id')->constrained()->onDelete('cascade');
            $table->string('scholarshipType');
            $table->date('date_start');
            $table->date('date_end');
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();
        });

        Schema::create('requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholarship_id')->constrained()->onDelete('cascade');
            $table->string('requirements');
            $table->date('date_start');
            $table->date('date_end');
            $table->integer('subtotal_scholars')->nullable(); //1
            $table->integer('total_scholars')->nullable(); //4
            $table->timestamps();
        });

        Schema::create('campus_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholarship_id')->constrained()->onDelete('cascade');
            $table->foreignId('campus_id')->constrained();
            $table->json('selected_campus');
            $table->integer('slots');
            $table->integer('remaining_slots');
            $table->timestamps();
        });

        Schema::create('criteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholarship_id')->constrained()->onDelete('cascade');
            $table->string('forms');
            $table->timestamps();
        });

        Schema::create('scholarship_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('scholarship_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scholarship_groups');
        Schema::dropIfExists('criteria');
        Schema::dropIfExists('campus_recipients');
        Schema::dropIfExists('requirements');
        Schema::dropIfExists('scholarships');
    }
};
