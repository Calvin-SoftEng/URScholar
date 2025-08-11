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
        Schema::create('student_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholar_id')->constrained()->onDelete('cascade');
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('suffix_name')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('placebirth')->nullable();
            $table->integer('age')->nullable();
            $table->string('gender')->nullable();
            $table->string('civil')->nullable();
            $table->string('religion')->nullable();
            $table->string('guardian')->nullable();
            $table->string('relationship')->nullable();
            $table->boolean('has_other_scholarship')->default(false);
            $table->string('other_scholarship_name')->nullable();
            $table->decimal('other_scholarship_amount', 10, 2)->nullable();
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

        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholar_id')->constrained()->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained()->onDelete('cascade');
            $table->decimal('grade')->nullable();
            $table->string('cog')->nullable();
            $table->string('path');
            $table->string('school_year_id')->nullable();
            $table->string('semester')->nullable();
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
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

        Schema::create('sibling_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('family_record_id')->constrained()->onDelete('cascade');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('age')->nullable();
            $table->string('occupation')->nullable();
            $table->timestamps();
        });

        Schema::create('org_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_record_id')->constrained()->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('year')->nullable();
            $table->string('position')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('org_records');
        Schema::dropIfExists('sibling_records');
        Schema::dropIfExists('family_records');
        Schema::dropIfExists('grades');
        Schema::dropIfExists('education_records');
        Schema::dropIfExists('student_records');
    }
};
