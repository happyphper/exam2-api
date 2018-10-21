<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->default('每日一答')->index()->comment('今日主题');
            $table->timestamp('started_at')->nullable()->index()->comment('开考时间');
            $table->timestamp('ended_at')->nullable()->index()->comment('结束时间');
            $table->integer('course_id')->index()->comment('课程');
            $table->integer('questions_count')->default(0)->index()->comment('题目个数');
            $table->string('status', 16)->default(\App\Enums\TestStatus::Unstart)->index()->comment('题目个数');
            $table->integer('total_score')->default(0)->index()->comment('总分');
            $table->integer('user_id')->index()->comment('创建人');
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
        Schema::dropIfExists('tests');
    }
}
