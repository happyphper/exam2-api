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
        \App\Models\Category::observe(\App\Observers\CategoryObserver::class);
        \App\Models\UserGroup::observe(\App\Observers\UserGroupObserver::class);
        \DB::listen(function ($query) {
            \Log::info($query->sql);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
