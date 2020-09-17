<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddABunchOfStuffToUsersAndTasksAndTests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->string('author')->default('');
            $table->string('grader_status')->default('');
        });
        Schema::table('tests', function (Blueprint $table) {
            $table->string('input_status')->default('');
            $table->string('output_status')->default('');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->string('real_name')->default('');
        });
        Schema::table('runs', function (Blueprint $table) {
            $table->string('grader_feedback')->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('author');
            $table->dropColumn('grader_status');
        });
        Schema::table('tests', function (Blueprint $table) {
            $table->dropColumn('input_status');
            $table->dropColumn('output_status');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('real_name');
        });
        Schema::table('runs', function (Blueprint $table) {
            $table->dropColumn('grader_feedback');
        });
    }
}
