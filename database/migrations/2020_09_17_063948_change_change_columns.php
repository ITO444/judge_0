<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeChangeColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->text('statement')->change();
            $table->mediumText('checker')->change();
            $table->renameColumn('checker', 'grader');
            $table->text('solution')->change();
        });
        Schema::table('submissions', function (Blueprint $table) {
            $table->text('compiler_warning')->change();
            $table->mediumText('source_code')->change();
            $table->integer('score');
        });
        Schema::table('runs', function (Blueprint $table) {
            $table->text('grader_feedback')->change();
            $table->string('result')->change();
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
            $table->renameColumn('grader', 'checker');
        });
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropColumn('score');
        });
        Schema::table('runs', function (Blueprint $table) {
            $table->integer('result')->change();
        });
    }
}
