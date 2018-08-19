<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('records', function (Blueprint $table) {
            $table->increments('id')->comment('答题记录表');
            $table->integer('user_id')->comment('用户 ID');
            $table->integer('test_id')->comment('测试 ID');
            $table->json('answer')->comment('答案');
            $table->integer('error_count')->index()->comment('答题错题数');
            $table->integer('correct_count')->index()->comment('答题正确数');
            $table->integer('grade')->comment('考试得分');
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
