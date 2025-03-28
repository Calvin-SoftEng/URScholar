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
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholarship_id')->constrained()->onDelete('cascade');
            $table->string('total_scholars')->nullable();
            $table->string('sub_total')->nullable();
            $table->date('date_start');
            $table->date('date_end');
            $table->enum('status', ['Active', 'Inactive', 'Pending'])->default('Pending');
            $table->timestamps();
        });

        Schema::create('disbursements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payout_id')->constrained()->onDelete('cascade');
            $table->foreignId('batch_id')->constrained()->onDelete('cascade');
            $table->foreignId('scholar_id')->constrained()->onDelete('cascade');
            $table->string('reasons_of_not_claimed')->nullable();
            $table->timestamp('claimed_at')->nullable();
            $table->foreignId('claimed_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->enum('status', ['Claimed', 'Pending'])->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payouts');
        Schema::dropIfExists('disbursements');
    }
};
