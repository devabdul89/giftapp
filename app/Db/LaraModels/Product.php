<?php

namespace LaraModels;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title','detail','material','price','size_available'
    ];

    public function images(){
        return $this->hasMany(ProductImage::class,'product_id');
    }

    public function wishers(){
        return $this->belongsToMany(User::class,'wishlist','product_id','user_id')->withPivot('product_vendor');
    }
}

