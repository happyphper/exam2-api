<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    $api->group(['middleware' => 'api', 'namespace' => 'App\Http\Controllers'], function ($api) {
        // 登录相关
        $api->group(['prefix' => 'auth'], function ($api) {
            $api->post('login', 'AuthController@login')->name('admin.login');
            $api->delete('logout', 'AuthController@logout')->name('admin.logout');
            $api->patch('refresh', 'AuthController@refresh')->name('admin.refresh');
            $api->get('me', 'AuthController@me')->name('admin.me');
        });

        // 后台相关
        $api->group(['prefix' => 'admin', 'namespace' => 'Admin'], function ($api) {
            $api->group(['as' => 'admin'], function ($api) {
                // 用户 CRUD
                $api->resource('users', 'UserController');
                // 题目 CRUD
                $api->resource('questions', 'QuestionController');
                // 测试 CRUD
                $api->resource('records', 'RecordController');
                // 类别 CRUD
                $api->resource('categories', 'CategoryController');
            });
        });

        // 小程序相关
        $api->group(['prefix' => 'mini-app', 'namespace' => 'MiniApp'], function ($api) {
            // 登录相关
            $api->group(['prefix' => 'auth'], function ($api) {
                $api->post('login', 'AuthController@login')->name('mini-app.login');
                $api->delete('logout', 'AuthController@logout')->name('mini-app.logout');
                $api->patch('refresh', 'AuthController@refresh')->name('mini-app.refresh');
                $api->get('me', 'AuthController@me')->name('mini-app.me');
            });
            // 获取每日一测题目
            $api->get('daily-test', 'TestController@daily')->name('mini-app.test.daily');
            // 获取随机题目
            $api->get('random-test', 'TestController@random')->name('mini-app.test.random');
            // 提交测试
            $api->post('records', 'RecordController@store')->name('mini-app.record.store');
            // 查看答案
            $api->get('records{record}', 'RecordController@show')->name('mini-app.record.show');
            // 查看以往答题记录
            $api->get('records', 'RecordController@index')->name('mini-app.record.index');
            // 获取分类
            $api->get('categories', 'CategoryController@index')->name('mini-app.category.index');
        });
    });
});