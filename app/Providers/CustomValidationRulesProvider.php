<?php

namespace App\Providers;

use App\Libs\Auth\Auth;
use App\Repositories\UsersRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class CustomValidationRulesProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('not_already_blocked', function($attribute, $value, $parameters, $validator){
                return (new UsersRepository())->findBlockedUser(['object_id'=>Auth::user()->id,'subject_id'=>$value]) == null;
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
