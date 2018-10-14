<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('student_id')->nullable()->comment('学号');
            $table->string('phone')->nullable()->comment('手机');
            $table->string('avatar')->nullable()->comment('头像');
            $table->string('group_id')->nullable()->comment('群组');
            $table->rememberToken();
            $table->timestamps();

            $table->index('student_id');
            $table->index('group_id');
            $table->unique('phone');
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
