<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Gate::define('admin', function ($user) {
            return ($user->role == "1" || $user->role == "2" || $user->role == "3");
        });

        Validator::extend('kana', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/[ァ-ヴー]+/u', $value);
        });
    }
}
