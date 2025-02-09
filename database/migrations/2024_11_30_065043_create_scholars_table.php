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
        Schema::create('scholars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholarship_id')->constrained()->onDelete('cascade');
            $table->string('hei_name');
            $table->string('campus');
            $table->string('grant');
            $table->foreignId('batch_id')->constrained()->onDelete('cascade');
            $table->string('app_no');
            $table->string('award_no');
            $table->string('last_name');
            $table->string('first_name');
            $table->string('extname')->nullable();
            $table->string('middle_name');
            $table->string('sex');
            $table->date('birthdate');
            $table->string('course');
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scholars');
    }
};
