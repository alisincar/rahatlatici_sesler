<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToFavoritesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('favorites', function (Blueprint $table) {
            if (!Schema::hasColumn('favorites', 'user_id')) {
                $table->unsignedBigInteger('user_id')->unsigned()->nullable();
                $table->foreign('user_id', '288262_5ca8sdfsd32')->references('id')->on('users')->onDelete('cascade');
            }
            if (!Schema::hasColumn('favorites', 'music_id')) {
                $table->unsignedBigInteger('music_id')->unsigned()->nullable();
                $table->foreign('music_id', '288262_5ca8ef1a82b47')->references('id')->on('musics')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('favorites', function (Blueprint $table) {
            //
        });
    }
}
