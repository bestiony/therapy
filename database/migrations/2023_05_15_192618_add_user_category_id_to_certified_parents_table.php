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
        Schema::table('certified_parents', function (Blueprint $table) {
            $table->unsignedBigInteger('user_category_id')->nullable();
            $table->foreign('user_category_id')->references('id')->on('categories')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('certified_parents', function (Blueprint $table) {
            //
        });
    }
};
