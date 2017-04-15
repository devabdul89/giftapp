<?php

namespace LaraModels;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $table = 'wishlist';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'product_id', 'product_vendor'
    ];

    public function user(){
        return $this->hasOne(User::class);
    }

    public function product(){
        return $this->hasOne(Product::class,'product_id');
    }
}
