<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instructors', function (Blueprint $table) {
            $table->string('video')->nullable();
            $table->integer('intro_video_check')->nullable();
            $table->string('youtube_video_id')->nullable();
        });
        Schema::table('organizations', function (Blueprint $table) {
            $table->string('video')->nullable();
            $table->integer('intro_video_check')->nullable();
            $table->string('youtube_video_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instructors_and_organisations_tables', function (Blueprint $table) {
            //
        });
    }
};
