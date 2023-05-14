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
        Schema::create('certified_parents', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->bigInteger('country_id')->nullable();
            $table->bigInteger('province_id')->nullable();
            $table->bigInteger('state_id')->nullable();
            $table->bigInteger('city_id')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('professional_title')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('postal_code', 100)->nullable();
            $table->string('address')->nullable();
            $table->tinyInteger('consultancy_area')->default(3);
            $table->mediumText('about_me')->nullable();
            $table->string('gender', 50)->nullable();
            $table->mediumText('social_link')->nullable();
            $table->string('slug')->nullable();
            $table->string('is_private', 10)->default('no')->comment('yes, no');
            $table->string('remove_from_web_search', 10)->default('no')->comment('yes, no');
            $table->tinyInteger('status')->default(0)->comment('0=pending, 1=approved, 2=blocked');
            $table->tinyInteger('is_offline')->default(0)->comment('offline status');
            $table->string('offline_message')->nullable()->comment('offline message');
            $table->string('video')->nullable();
            $table->integer('intro_video_check')->nullable();
            $table->string('youtube_video_id')->nullable();
            $table->tinyInteger('consultation_available')->default(0)->comment('1=yes, 0=no')->nullable();
            $table->integer('rank')->default(100);
            $table->softDeletes();

            $table->timestamps();
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('no action');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('certified_parents');
    }
};
