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
        Schema::table('zoom_settings', function (Blueprint $table) {
            $table->string('api_key')->nullable()->change();
            $table->string('api_secret')->nullable()->change();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('account_id')->nullable();
            $table->string('access_token')->nullable();
            $table->dateTime('token_expires_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('zoom_settings', function (Blueprint $table) {
            //
        });
    }
};
