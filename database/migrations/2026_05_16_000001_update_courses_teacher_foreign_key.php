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
        Schema::table('courses', function (Blueprint $table) {
            // Make teacher_id nullable
            $table->unsignedBigInteger('teacher_id')->nullable()->change();
            
            // Add the foreign key with ON DELETE SET NULL
            $table->foreign('teacher_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            // Drop the modified foreign key
            $table->dropForeign(['teacher_id']);
            
            // Restore the original foreign key
            $table->foreign('teacher_id')
                  ->references('id')
                  ->on('users');
        });
    }
};
