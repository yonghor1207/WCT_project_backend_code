<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdatePaymentStatusEnum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Step 1: Add new enum values while keeping 'paid'
        DB::statement("ALTER TABLE users MODIFY COLUMN payment_status ENUM('paid', 'paid_1_semester', 'paid_2_semester', 'pending', 'not_yet') DEFAULT 'not_yet'");
        
        // Step 2: Update existing 'paid' values to 'paid_1_semester'
        DB::statement("UPDATE users SET payment_status = 'paid_1_semester' WHERE payment_status = 'paid'");
        
        // Step 3: Remove 'paid' from enum
        DB::statement("ALTER TABLE users MODIFY COLUMN payment_status ENUM('paid_1_semester', 'paid_2_semester', 'pending', 'not_yet') DEFAULT 'not_yet'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revert back to original enum
        DB::statement("ALTER TABLE users MODIFY COLUMN payment_status ENUM('paid', 'pending', 'not_yet') DEFAULT 'not_yet'");
        
        // Update 'paid_1_semester' and 'paid_2_semester' back to 'paid'
        DB::statement("UPDATE users SET payment_status = 'paid' WHERE payment_status IN ('paid_1_semester', 'paid_2_semester')");
    }
}
