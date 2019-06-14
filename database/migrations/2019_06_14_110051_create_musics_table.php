<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMusicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('musics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->enum('status', array('0', '1'))->default('1');
            $table->string('cover_image')->nullable();
            $table->string('source')->nullable();
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
        Schema::dropIfExists('musics');
    }
}
