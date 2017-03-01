<?php
/**
 * Created by PhpStorm.
 * User: nomantufail
 * Date: 11/22/2016
 * Time: 6:46 PM
 */
namespace App\Traits;


use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Authenticatable as L_Authenticatable;

trait Authenticatable
{
    use Notifiable, L_Authenticatable, Authorizable, CanResetPassword;
}