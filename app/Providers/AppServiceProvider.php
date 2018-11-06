<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \App\Models\User::observe(\App\Observers\UserObserver::class);
        \App\Models\Category::observe(\App\Observers\CategoryObserver::class);
        \App\Models\Question::observe(\App\Observers\QuestionObserver::class);
        \App\Models\QuestionResult::observe(\App\Observers\QuestionResultObserver::class);
        \App\Models\ExamQuestion::observe(\App\Observers\ExamQuestionObserver::class);
        \App\Models\Exam::observe(\App\Observers\ExamObserver::class);
        // \DB::listen(function ($query) {
        //     \Log::info($query->sql);
        //     \Log::info($query->bindings);
        // });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // 默认 dingo 将 laravel 默认异常的状态码设置为 500，故手动捕获修改。
        \API::error(function (\Illuminate\Auth\AuthenticationException $exception) {
            abort(401, '需登录验证');
        });

        \API::error(function (\Illuminate\Auth\Access\AuthorizationException $exception) {
            abort(401,'需登录验证');
        });

        \API::error(function (\Spatie\Permission\Exceptions\UnauthorizedException $exception) {
            abort(403, '不具备权限');
        });

        \API::error(function (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
            abort(404, '数据不存在');
        });

        \API::error(function (\Illuminate\Validation\ValidationException $exception) {
            throw new \Dingo\Api\Exception\ResourceException('参数错误', $exception->errors(), null, [], 422);
        });
    }
}
