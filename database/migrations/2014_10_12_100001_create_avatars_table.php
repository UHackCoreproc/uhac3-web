<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAvatarsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avatars', function (Blueprint $table) {
            $table->increments('id');
            $table->string('disk')->nullable(); // "Disk" used according to your config/filesystem.php
            $table->text('path')->nullable(); // Filepath
            $table->text('description')->nullable();
            $table->integer('size')->nullable(); // Size in bytes
            $table->string('content_type')->nullable();
            $table->morphs('avatarable');
            $table->nullableTimestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('avatars');
    }
}
