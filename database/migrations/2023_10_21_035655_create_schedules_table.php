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
        Schema::create('schedules', function (Blueprint $table) {
            $table->increments("id");
            $table->string("memberID");
            $table->string("memberID2");
            $table->string("memberID3");
            $table->string("memberID4");
            $table->string("memberID5");
            $table->integer("categoryID");
            $table->integer("year");
            $table->integer("month");
            $table->integer("day");
            $table->string("schedule");
            $table->string("place");
            $table->string("detail");
            $table->string("start_time");
            $table->string("end_time");
            $table->integer("deleteFlg");
            $table->date("updated_at");
            $table->date("created_at");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedules');
    }
};
