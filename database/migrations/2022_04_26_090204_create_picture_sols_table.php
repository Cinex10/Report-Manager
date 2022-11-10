<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePictureSolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('picture_sols', function (Blueprint $table) {
            $table->id();
            $table->UnsignedBigInteger('idSolution');
            $table->foreign('idSolution')->references('id')->on('solutions')->onDelete('cascade')->onUpdate('cascade');
            $table->string('picture');
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
        Schema::dropIfExists('picture_decs');
    }
}
