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
}

