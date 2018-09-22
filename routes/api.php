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

$api->version('v1', ['middleware' => ['serializer:array', 'bindings']], function ($api) {
    $api->group(['middleware' => 'api', 'namespace' => 'App\Http\Controllers'], function ($api) {
        // 后台
        $api->group(['prefix' => 'admin', 'namespace' => 'Admin'], function ($api) {
            // 登录相关
            $api->group(['prefix' => 'auth'], function ($api) {
                $api->post('login', 'AuthController@login')->name('admin.login');
                $api->delete('logout', 'AuthController@logout')->name('admin.logout');
                $api->patch('refresh', 'AuthController@refresh')->name('admin.refresh');
                $api->get('me', 'AuthController@me')->name('admin.me');
            });
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
            // 群组
            $api->resource('groups', 'GroupController');
            // 添加、移除群组
            $api->post('/users/{user}/groups/{group}', 'UserGroupController@store');
            $api->delete('/users/{user}/groups/{group}', 'UserGroupController@destroy');
            // 群组测试
            $api->post('group-tests', 'GroupTestController@store');
            // 为 Model 添加/移除分类
            $api->post('model/{type}/categories', 'ModelHasCategoryController@store');
            $api->delete('model/{type}/categories/{category}', 'ModelHasCategoryController@destroy');
        });

        // 小程序
        $api->group(['prefix' => 'miniapp', 'namespace' => 'MiniApp', 'as' => 'miniapp'], function ($api) {
            // 今日测验
            $api->get('today-tests', 'TestController@today');
            // 考试记录
            $api->get('records', 'RecordController@index');
            // 提交答卷
            $api->post('records', 'RecordController@store');
        });
    });
});