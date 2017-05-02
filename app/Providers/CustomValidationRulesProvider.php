<?php

namespace App\Providers;

use App\Libs\Auth\Auth;
use Illuminate\Support\Facades\Hash;
use phpDocumentor\Reflection\Types\Boolean;
use Repositories\UsersRepository;
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
        Validator::extend('match_password', function($attribute, $value, $parameters, $validator){
            return Hash::check($value, (new UsersRepository())->findById($parameters[0])->getPassword());
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
