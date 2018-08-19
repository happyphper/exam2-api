<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id')->comment('题目表');
            $table->string('title')->unique()->comment('题目');
            $table->json('options')->comment('选项');
            $table->integer('right_option')->comment('正确选项');
            $table->integer('category_id')->nullable()->index()->comment('类别');
            $table->string('parsing')->nullable()->comment('答案解析');
            $table->integer('error_count')->default(0)->comment('正确次数');
            $table->integer('correct_count')->default(0)->comment('错误次数');
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
        Schema::dropIfExists('questions');
    }
}
