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
        // 登录
        $api->post('admin/auth/login', 'Admin\AuthController@login')->name('admin.login');

        // 后台
        $api->group(['prefix' => 'admin', 'namespace' => 'Admin'], function ($api) {
            $api->group(['middleware' => 'auth'], function ($api) {
                // 控制台
                $api->get('dashboard', 'DashboardController@index');
                // 登出、刷新、个人信息
                $api->delete('auth/logout', 'AuthController@logout')->name('admin.logout');
                $api->patch('auth/refresh', 'AuthController@refresh')->name('admin.refresh');
                $api->get('auth/me', 'AuthController@me')->name('admin.me');
                // 类别 CRUD
                $api->resource('{type}/categories', 'CategoryController');
                // 题目 CRUD
                $api->resource('questions', 'QuestionController');
                $api->post('bulk-import-questions', 'QuestionController@bulk');
                // 共享题库
                $api->resource('share-questions', 'ShareQuestionController');
                // 测试 CRUD
                $api->resource('tests', 'TestController');
                // 测试题目 CRUD
                $api->resource('tests/{test}/questions', 'TestQuestionController');
                // 用户
                $api->resource('admin-users', 'AdminUserController');
                $api->resource('users', 'UserController');
                // 用户批量导入
                $api->post('bulk-import-users', 'UserController@bulk');
                // 群组
                $api->resource('groups', 'GroupController');
                // 课程
                $api->resource('courses', 'CourseController');
                // 测试分类添加移除
                $api->post('tests/{test}/categories', 'TestCategoryController@store');
                $api->post('tests/{test}/categories/{category}', 'TestCategoryController@destroy');
                // 群组测试
                $api->resource('groups.tests', 'GroupTestController');
                // 为 Model 添加/移除分类
                $api->post('model/{type}/categories', 'ModelHasCategoryController@store');
                $api->delete('model/{type}/categories/{category}', 'ModelHasCategoryController@destroy');
                // 考试记录
                $api->get('tests/{test}/groups/{group}/results', 'TestResultController@index');
                // 权限
                $api->resource('permissions', 'PermissionController');
                $api->resource('roles', 'RoleController');
                $api->resource('users.roles', 'UserRoleController');
                $api->resource('roles.permissions', 'RolePermissionController');
                // 云存储 七牛
                $api->post('cloud-storage', 'CloudStorageController@token');
                $api->delete('cloud-storage/{name}', 'CloudStorageController@destroy');
                // 考试记录
                $api->get('test-results', 'TestResultController@index');
                // 考试成绩分布、考试错题分布、个人成绩曲线
                $api->get('stat/grade-distribution', 'StatisticController@gradeDistribution');
                $api->get('stat/error-question', 'StatisticController@errorQuestion');
                $api->get('stat/user-grade-curve', 'StatisticController@userGradeCurve');
            });
        });

        // 小程序
        $api->group(['prefix' => 'miniapp', 'namespace' => 'MiniApp', 'as' => 'miniapp'], function ($api) {
            // 登录
            $api->post('auth/login', 'AuthController@login')->name('miniapp.login');
            $api->get('auth/me', 'AuthController@me')->name('miniapp.me');
            // 今日测验
            $api->get('today-tests', 'TestController@today');
            // 开始考试
            $api->get('tests/{test}/start', 'TestController@start');
            // 提交答案
            $api->post('tests/{test}/questions', 'QuestionResultController@store');
            // 答题记录
            $api->get('test-results', 'TestResultController@index');
            $api->get('test-results/{result}', 'TestResultController@show');
            // 同班同学
            $api->get('users', 'UserController@index');
        });
    });
});