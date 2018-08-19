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
            $table->string('type')->default('daily')->comment('daily, random 每日一测、随机考验');
            $table->timestamp('started_at')->nullable()->index()->comment('开考时间');
            $table->timestamp('ended_at')->nullable()->index()->comment('结束时间');
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
