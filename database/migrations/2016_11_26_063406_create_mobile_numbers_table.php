<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMobileNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mobile_numbers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mobile_number')->nullable();
            $table->string('verification_code')->nullable();
            $table->integer('user_id', false, true)->nullable();
            $table->nullableTimestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mobile_numbers');
    }
}
