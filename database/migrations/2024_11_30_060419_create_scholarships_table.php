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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('scholarshipType', ['Grant-Based', 'One-time Payment'])->default('Grant-Based');
            $table->date('date_start');
            $table->date('date_end');
            $table->enum('status', ['Active', 'Inactive', 'Pending'])->default('Pending');
            $table->boolean('read')->default(false);
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

        Schema::create('scholarship_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholarship_id')->constrained()->onDelete('cascade');
            $table->foreignId('requirement_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('filename');
            $table->string('path');
            $table->string('mime_type');
            $table->timestamps();
        });

        Schema::create('scholarship_forms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('scholarship_form_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholarship_form_id')->constrained()->onDelete('cascade');
            $table->string('name');
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

        Schema::create('criterias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholarship_id')->constrained()->onDelete('cascade');
            $table->foreignId('scholarship_form_data_id')->nullable()
                ->constrained('scholarship_form_data')
                ->onDelete('cascade');
            $table->decimal('grade')->nullable();
            $table->timestamps();
        });

        Schema::create('eligibilities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('conditions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eligibility_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->timestamps();   
        });

        Schema::create('eligibles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholarship_id')->constrained()->onDelete('cascade');
            $table->foreignId('condition_id')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('eligibles');
        Schema::dropIfExists('conditions');
        Schema::dropIfExists('eligibilities');
        Schema::dropIfExists('criterias');
        Schema::dropIfExists('campus_recipients');
        Schema::dropIfExists('scholarship_form_data');
        Schema::dropIfExists('scholarship_forms');
        Schema::dropIfExists('scholarship_templates');
        Schema::dropIfExists('requirements');
        Schema::dropIfExists('scholarships');
    }
};
