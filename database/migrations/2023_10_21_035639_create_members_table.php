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
        Schema::create('members', function (Blueprint $table) {
            $table->increments("id");
            $table->integer("colorID");
            $table->string("fullName");
            $table->string("memName");
            $table->string("memName_2");
            $table->string("note")->nullable();
            $table->integer("appear");
            $table->integer("deleteFlg")->default(0);
            $table->date("created_at");
            $table->date("updated_at");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('members');
    }
};
