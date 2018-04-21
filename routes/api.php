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
    $api->group(['middleware' => 'api', 'prefix' => 'admin', 'namespace' => 'App\Http\Controllers'], function ($api) {
        // 登录相关
        $api->group(['namespace' => 'Admin', 'prefix' => 'auth'], function ($api) {
            $api->post('login', 'AuthController@login')->name('admin.login');
            $api->delete('logout', 'AuthController@logout')->name('admin.logout');
            $api->patch('refresh', 'AuthController@refresh')->name('admin.refresh');
            $api->get('me', 'AuthController@me')->name('admin.me');
        });
    });
});