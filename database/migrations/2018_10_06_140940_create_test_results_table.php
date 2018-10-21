<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_results', function (Blueprint $table) {
            $table->increments('id')->comment('考试记录表');
            $table->integer('group_id')->comment('群组 ID');
            $table->integer('course_id')->comment('课程 ID');
            $table->integer('user_id')->comment('用户 ID');
            $table->integer('test_id')->comment('考试 ID');
            $table->integer('wrong_count')->default(0)->index()->comment('答题错题数');
            $table->integer('right_count')->default(0)->index()->comment('答题正确数');
            $table->integer('questions_count')->default(0)->index()->comment('题目总数');
            $table->integer('score')->default(0)->comment('考试得分');
            $table->integer('total_score')->default(0)->comment('总分');
            $table->integer('finished_count')->default(0)->comment('已答题个数');
            $table->boolean('is_finished')->default(false)->comment('是否答完');
            $table->boolean('is_participated')->default(true)->comment('是否参加');
            $table->timestamps();

            $table->unique(['user_id', 'test_id']);
            $table->index('test_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('records');
    }
}
