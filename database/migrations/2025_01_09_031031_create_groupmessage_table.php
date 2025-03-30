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

        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('scholarship_id')->constrained()->onDelete('cascade');
            $table->text('content');
            $table->timestamps();
        });

        // Schema::create('pages', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('scholarship_id')->constrained()->onDelete('cascade');
        //     $table->timestamps();
        // });

        // Schema::create('postings', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('page_id')->constrained()->onDelete('cascade');
        //     $table->foreignId('user_id')->constrained()->onDelete('cascade');
        //     $table->text('content');
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
