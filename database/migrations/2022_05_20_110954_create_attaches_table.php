<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttachesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attaches', function (Blueprint $table) {
            $table->unsignedBigInteger('idDeclaration2')->nullable();
            $table->foreign('idDeclaration2')->references('id')->on('declarations')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('idDeclaration1')->nullable();
            $table->foreign('idDeclaration1')->references('id')->on('declarations')->onDelete('cascade')->onUpdate('cascade');
            // $table->primary(['idDeclaration1', 'idDeclaration2']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attaches');
    }
}
