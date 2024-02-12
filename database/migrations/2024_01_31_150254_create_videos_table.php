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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->bigInteger('user_id');
            $table->string('title');
            $table->string('slug');
            $table->mediumText('details');
            $table->mediumText('description');
            $table->string('image');
            $table->string('video');
            $table->text('keywords');
            $table->tinyInteger('status')->default(0)->comment('1=published, 0=unpublished');
            $table->unsignedBigInteger('video_category_id')->nullable();
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
        Schema::dropIfExists('videos');
    }
};
