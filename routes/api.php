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
                // 类别 CRUD
                $api->resource('{type}/categories', 'CategoryController');
                // 题目 CRUD
                $api->resource('questions', 'QuestionController');
                // 测试 CRUD
                $api->resource('tests', 'TestController');
                // 测试题目 CRUD
                $api->resource('tests/{test}/questions', 'TestQuestionController');
                // 用户
                $api->resource('users', 'UserController');
            });
        });
    });
});