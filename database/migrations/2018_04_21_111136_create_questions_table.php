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
            $table->string('type')->comment('题目:single、multiple');
            $table->json('options')->comment('选项');
            $table->json('answer')->comment('正确选项');
            $table->integer('chapter')->nullable()->comment('章');
            $table->integer('section')->nullable()->comment('节');
            $table->string('explain')->nullable()->comment('答案解析');
            $table->integer('wrong_count')->default(0)->comment('错误次数');
            $table->integer('right_count')->default(0)->comment('正确次数');
            $table->integer('answered_count')->default(0)->comment('答题次数');
            $table->integer('accuracy')->default(0)->comment('正确率');
            $table->integer('course_id')->comment('课程');
            $table->integer('user_id')->comment('创建人');
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
