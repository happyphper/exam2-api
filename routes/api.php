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
                // 共享管理
                $api->resource('shares', 'ShareController');
                // 考试 CRUD
                $api->patch('exams/{exam}/end', 'ExamController@end');
                $api->patch('exams/{exam}/start', 'ExamController@start');
                $api->resource('exams', 'ExamController');
                // 考试题目 CRUD
                $api->resource('exams/{exam}/questions', 'ExamQuestionController');
                // 用户
                $api->resource('teachers', 'TeacherController');
                $api->resource('users', 'UserController');
                // 用户批量导入
                $api->post('bulk-import-users', 'UserController@bulk');
                // 群组
                $api->resource('classrooms', 'ClassroomController');
                // 课程
                $api->resource('courses', 'CourseController');
                // 测试分类添加移除
                $api->post('exams/{exam}/categories', 'ExamCategoryController@store');
                $api->post('exams/{exam}/categories/{category}', 'ExamCategoryController@destroy');
                // 群组测试
                $api->resource('classrooms.exams', 'ClassroomExamController');
                // 为 Model 添加/移除分类
                $api->post('model/{type}/categories', 'ModelHasCategoryController@store');
                $api->delete('model/{type}/categories/{category}', 'ModelHasCategoryController@destroy');
                // 考试记录
                $api->get('exams/{exam}/classrooms/{classroom}/results', 'ExamResultController@index');
                // 权限
                $api->resource('permissions', 'PermissionController');
                $api->resource('roles', 'RoleController');
                $api->resource('users.roles', 'UserRoleController');
                $api->resource('roles.permissions', 'RolePermissionController');
                // 云存储 七牛
                $api->post('cloud-storage', 'CloudStorageController@token');
                $api->delete('cloud-storage', 'CloudStorageController@destroy');
                // 考试记录
                $api->get('exam-results', 'ExamResultController@index');
                // 考试成绩分布、考试错题分布、个人成绩曲线
                $api->get('stat/grade-distribution', 'StatisticController@gradeDistribution');
                $api->get('stat/error-question', 'StatisticController@errorQuestion');
                $api->get('stat/user-grade-curve', 'StatisticController@userGradeCurve');
                $api->get('stat/user-grade-data', 'StatisticController@userGradeData');
                // 修改密码
                $api->patch('password/reset', 'PasswordController@reset');
            });
        });

        // 小程序
        $api->group(['prefix' => 'miniapp', 'namespace' => 'MiniApp', 'as' => 'miniapp'], function ($api) {
            // 微信授权登录
            $api->post('wechat/login', 'WechatAuthController@login')->name('miniapp.wechat.login');
            $api->post('wechat/register', 'WechatAuthController@register')->name('miniapp.wechat.register');
            // 登录
            $api->post('auth/login', 'AuthController@login')->name('miniapp.login');
            $api->group(['middleware' => 'auth'], function ($api) {
                $api->get('auth/me', 'AuthController@me')->name('miniapp.me');
                // 今日测验
                $api->get('today-exams', 'ExamController@today');
                // 开始考试
                $api->get('exams/{exam}/start', 'ExamController@start');
                // 提交答案
                $api->post('exams/{exam}/questions', 'QuestionResultController@store');
                // 答题记录
                $api->get('exam-results', 'ExamResultController@index');
                $api->get('exam-results/{result}', 'ExamResultController@show');
                // 同班同学
                $api->get('classmates', 'ClassmateController@index');
                // 修改密码
                $api->put('password', 'PasswordController@update');
                // 绑定手机
                $api->put('phone', 'PhoneController@update');
                // 获取题目及考试统计信息
                $api->get('stats/dashboard', 'StatController@dashboard');
            });
        });
    });
});