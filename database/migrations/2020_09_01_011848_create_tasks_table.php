<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('task_id')->unique();
            $table->string('title');
            $table->integer('source_size');
            $table->integer('compile_time');
            $table->integer('runtime_limit');
            $table->integer('memory_limit');
            $table->integer('output_limit');
            $table->integer('view_level');
            $table->integer('submit_level');
            $table->integer('edit_level');
            $table->integer('task_type');
            $table->date('date_created');
            $table->string('origin');
            $table->string('statement');
            $table->string('checker');
            $table->string('solution');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
