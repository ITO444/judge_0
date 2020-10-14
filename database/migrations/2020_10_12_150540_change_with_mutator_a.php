<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeWithMutatorA extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('runner_status');
        });
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('grader_status');
        });
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropColumn('result');
        });
        Schema::table('runs', function (Blueprint $table) {
            $table->dropColumn('result');
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
            $table->string('runner_status')->default('');
        });
        Schema::table('tasks', function (Blueprint $table) {
            $table->string('grader_status')->default('');
        });
        Schema::table('submissions', function (Blueprint $table) {
            $table->string('result')->default('');
        });
        Schema::table('runs', function (Blueprint $table) {
            $table->string('result')->default('');
        });
    }
}
