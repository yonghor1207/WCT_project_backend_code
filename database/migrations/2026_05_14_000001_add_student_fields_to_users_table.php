<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStudentFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('department')->nullable()->after('phone');
            $table->string('class')->nullable()->after('department');
            $table->string('year')->nullable()->after('class');
            $table->enum('payment_status', ['paid', 'pending', 'not_yet'])->default('not_yet')->after('year');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['department', 'class', 'year', 'payment_status']);
        });
    }
}
