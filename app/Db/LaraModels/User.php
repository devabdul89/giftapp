<?php

namespace LaraModels;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function events(){
        return $this->belongsToMany(Event::class)->withPivot('accepted');
    }

    public function wishlist(){
        return $this->belongsToMany(Product::class,'wishlist','user_id','product_id')->withPivot('product_vendor');
    }

    public function ownedEvents(){
        return $this->hasMany(Event::class,'admin_id');
    }
}
