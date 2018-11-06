<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassroomExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classroom_exams', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('exam_id')->comment('测试与群组的关联表');
            $table->integer('classroom_id');
            $table->timestamps();
            $table->index(['exam_id', 'classroom_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('classroom_exams');
    }
}
