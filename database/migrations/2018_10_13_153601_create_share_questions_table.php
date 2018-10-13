<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShareQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('share_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->comment('收益者');
            $table->integer('share_user_id')->comment('被共享者');
            $table->timestamps();

            $table->unique(['user_id', 'share_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('share_questions');
    }
}
