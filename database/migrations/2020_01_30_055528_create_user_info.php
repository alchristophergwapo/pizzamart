<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('user_info', function (Blueprint $table) {
            $table->increments('id');
            $table->string('firstname',255);
            $table->string('lastname',255);
            $table->string('contactNumber',255);
            $table->string('address',255);
            $table->float('points')->default(0);
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
        //
        Schema::dropIfExists('user_info');
    }
}
