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
        Schema::create('sponsors', function (Blueprint $table) {
            $table->id();

            // Add the actual columns first
            $table->unsignedBigInteger('created_id')->nullable();
            $table->unsignedBigInteger('assign_id')->nullable();

            // Then define the foreign key constraints
            $table->foreign('created_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
            $table->foreign('assign_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->string('name');
            $table->string('abbreviation');
            $table->string('since');
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();
        });

        Schema::create('sponsor_moas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sponsor_id')->constrained()->onDelete('cascade');
            $table->string('moa');
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sponsor_moas');
        Schema::dropIfExists('sponsors');
    }
};
