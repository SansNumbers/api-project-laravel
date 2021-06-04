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

            $table->string('login')->unique();
            $table->string('password');
            $table->string('email')->unique();

            $table->string('name')->default('User');
            $table->text('avatar')->nullable();
            $table->enum('role', ['user', 'admin'])->default('user');
            $table->integer('rating')->default(0);

            $table->text('rememberToken')->nullable();

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
