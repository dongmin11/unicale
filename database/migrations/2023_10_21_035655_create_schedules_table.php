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
            $table->string("memberID")->nullable();
            $table->string("memberID2")->nullable();
            $table->string("memberID3")->nullable();
            $table->string("memberID4")->nullable();
            $table->string("memberID5")->nullable();
            $table->integer("categoryID")->nullable();
            $table->integer("year");
            $table->integer("month");
            $table->integer("day");
            $table->string("schedule");
            $table->string("place");
            $table->string("detail")->nullable();
            $table->string("start_time");
            $table->string("end_time");
            $table->integer("deleteFlg")->default(0);
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
