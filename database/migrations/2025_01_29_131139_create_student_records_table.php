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
        Schema::create('student_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('suffix_name')->nullable();
            $table->string('bday')->nullable();
            $table->string('placebirth')->nullable();
            $table->integer('age')->nullable();
            $table->string('sex')->nullable();
            $table->string('civil')->nullable();
            $table->string('religion')->nullable();
            $table->string('guardian')->nullable();
            $table->string('relationship')->nullable();
            $table->timestamps();
        });

        Schema::create('education_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_record_id')->constrained()->onDelete('cascade');
            $table->json('elementary')->nullable();
            $table->json('junior')->nullable();
            $table->json('senior')->nullable();
            $table->json('college')->nullable();
            $table->json('vocational')->nullable();
            $table->json('postgrad')->nullable();
            $table->timestamps();
        });

        Schema::create('family_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_record_id')->constrained()->onDelete('cascade');
            $table->json('mother')->nullable();
            $table->json('father')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('monthly_income')->nullable();
            $table->string('other_income')->nullable();
            $table->string('family_housing')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_records');
        Schema::dropIfExists('education_records');
        Schema::dropIfExists('student_records');
    }
};
