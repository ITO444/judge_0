<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStuffToContests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contests', function (Blueprint $table) {
            $table->integer('view_level')->default(10);
            $table->integer('reg_level')->default(10);
            $table->integer('add_level')->default(10);
            $table->integer('edit_level')->default(10);
            $table->integer('duration')->default(0);
            $table->dateTime('results')->nullable();
            $table->text('description');
            $table->text('editorial');
            $table->boolean('published')->default(0);
        });
        Schema::table('participations', function (Blueprint $table) {
            $table->dateTime('end');
            $table->json('information');
        });
        Schema::table('submissions', function (Blueprint $table) {
            $table->integer('participation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contests', function (Blueprint $table) {
            $table->dropColumn('view_level');
            $table->dropColumn('reg_level');
            $table->dropColumn('add_level');
            $table->dropColumn('edit_level');
            $table->dropColumn('duration');
            $table->dropColumn('results');
            $table->dropColumn('description');
            $table->dropColumn('editorial');
            $table->dropColumn('published');
        });
        Schema::table('participations', function (Blueprint $table) {
            $table->dropColumn('end');
            $table->dropColumn('information');
        });
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropColumn('participation');
        });
    }
}
