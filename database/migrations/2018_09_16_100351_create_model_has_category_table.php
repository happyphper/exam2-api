<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModelHasCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('model_has_category', function (Blueprint $table) {
            $table->primary(['category_id', 'classified_id', 'classified_type'], 'primary_key');
            $table->integer('category_id');
            $table->morphs('classified');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('model_has_category');
    }
}
