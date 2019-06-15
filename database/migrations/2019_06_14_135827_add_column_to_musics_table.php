<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToMusicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('musics', function (Blueprint $table) {
            /*
             * Bu tabloyu sonradan yaratmamızın sebebi ilişkili sutunların migration işlemi yapılırken varolmayabilmesidir
             * onDelete('cascade') ile ilişkilerden biri silinirse diğeri de etkilenecek
             * */
            if (!Schema::hasColumn('musics', 'category_id')) {
                $table->unsignedBigInteger('category_id')->unsigned()->nullable();
                $table->foreign('category_id', '288262_564325676543')->references('id')->on('categories')->onDelete('cascade');
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
        Schema::table('musics', function (Blueprint $table) {
            //
        });
    }
}
