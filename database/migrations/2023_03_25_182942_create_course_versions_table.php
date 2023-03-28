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
        Schema::create('course_versions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('instructor_id');
            $table->integer('version');
<<<<<<< HEAD
            $table->integer('status')->default(1)->comment('1 = pending, 2 = refused, 3 = apporved ');
=======
            $table->integer('status')->default(0)->comment('0 = incompleteed 1 = pending, 2 = refused, 3 = apporved ');
>>>>>>> refs/remotes/origin/temporary
            $table->json('details');
            $table->timestamps();
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('instructor_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_versions');
    }
};
