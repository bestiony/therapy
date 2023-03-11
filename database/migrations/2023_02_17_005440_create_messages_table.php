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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sender_id')->unsigned();
            $table->bigInteger('conversation_id')->unsigned();
            $table->text('content');
            $table->string('file')->nullable();

            $table->timestamps();
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('no action');
            $table->foreign('conversation_id')->references('id')->on('conversations')->onDelete('no action');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
};
