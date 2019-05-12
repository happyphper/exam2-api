<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWechatFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('openid')->nullable()->comment('微信 openid')->after('remember_token');
            $table->string('nickname')->nullable()->comment('微信昵称')->after('remember_token');
            $table->string('gender')->default(0)->comment('0 未知、1 男、2 女')->after('remember_token');

            $table->index('openid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['openid', 'nickname', 'gender']);
        });
    }
}
