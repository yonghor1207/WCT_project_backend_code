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
        Schema::table('attendances', function (Blueprint $table) {
            // Drop the existing foreign key for student_id
            $table->dropForeign(['student_id']);
            
            // Add the foreign key with cascade delete
            $table->foreign('student_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            // Drop the cascade foreign key
            $table->dropForeign(['student_id']);
            
            // Restore the original foreign key without cascade
            $table->foreign('student_id')
                  ->references('id')
                  ->on('users');
        });
    }
};
