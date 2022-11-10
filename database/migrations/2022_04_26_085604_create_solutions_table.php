<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solutions', function (Blueprint $table) {
            $table->id()->unique();
            $table->unsignedBigInteger('idDeclaration');
            $table->foreign('idDeclaration')->references('id')->on('declarations')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('idChefService');
            $table->foreign('idChefService')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('titre', 255);
            $table->text('description');
            $table->timestamp('dateResolution')->nullable();
            $table->string('state', 255)->default('in review');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('declarations');
    }
}
