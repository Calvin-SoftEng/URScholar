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
        Schema::create('education_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_record_id')->constrained()->onDelete('cascade');
            $table->json('elem')->nullable();
            $table->json('junior')->nullable();
            $table->json('senior')->nullable();
            $table->json('college')->nullable();
            $table->json('vocal')->nullable();
            $table->json('postg')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education_records');
    }
};

