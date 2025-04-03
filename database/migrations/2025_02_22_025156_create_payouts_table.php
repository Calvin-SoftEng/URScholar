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
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholarship_id')->constrained()->onDelete('cascade');
            $table->integer('total_scholars')->nullable();
            $table->integer('sub_total')->nullable();
            $table->date('date_start');
            $table->date('date_end');
            $table->enum('status', ['Active', 'Inactive', 'Pending'])->default('Pending');
            $table->timestamps();
        });

        Schema::create('payout_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payout_id')->constrained()->onDelete('cascade');
            $table->date('scheduled_date'); // Stores the scheduled payout date
            $table->time('scheduled_time'); // Stores the scheduled payout time
            $table->string('reminders')->nullable();
            $table->timestamps();
        });

        Schema::create('disbursements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payout_id')->constrained()->onDelete('cascade');
            $table->foreignId('batch_id')->constrained()->onDelete('cascade');
            $table->foreignId('scholar_id')->constrained()->onDelete('cascade');
            $table->string('reasons_of_not_claimed')->nullable();
            $table->string('file_name')->nullable();
            $table->string('path')->nullable();
            $table->timestamp('claimed_at')->nullable();
            $table->foreignId('claimed_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->enum('status', ['Claimed', 'Pending', 'Not Claimed'])->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disbursements');
        Schema::dropIfExists('payout_schedules');
        Schema::dropIfExists('payouts');
    }
};
