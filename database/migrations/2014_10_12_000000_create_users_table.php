<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('tel')->nullable();
            $table->string('photo')->nullable();
            $table->string('adress')->nullable();
            $table->boolean('isActive');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('notification_token_mobile')->nullable();
            $table->string('notification_token_web')->nullable();
            $table->boolean('can_delete')->default(true);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
