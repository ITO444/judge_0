<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeWithMutatorB extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('runner_status')->default(-4);
        });
        Schema::table('tasks', function (Blueprint $table) {
            $table->integer('grader_status')->default(-4);
        });
        Schema::table('submissions', function (Blueprint $table) {
            $table->integer('result')->default(-4);
        });
        Schema::table('runs', function (Blueprint $table) {
            $table->integer('result')->default(-4);
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
}
