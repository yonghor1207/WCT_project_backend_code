<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add 'pending_semester_2' to the payment_status enum
        DB::statement("ALTER TABLE users MODIFY COLUMN payment_status ENUM('paid_1_semester', 'paid_2_semester', 'pending', 'pending_semester_2', 'not_yet') DEFAULT 'not_yet'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'pending_semester_2' from the enum
        DB::statement("ALTER TABLE users MODIFY COLUMN payment_status ENUM('paid_1_semester', 'paid_2_semester', 'pending', 'not_yet') DEFAULT 'not_yet'");
    }
};
