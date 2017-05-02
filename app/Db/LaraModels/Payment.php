<?php

namespace LaraModels;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id','price'
    ];

    public function order(){
        return $this->belongsTo(Order::class);
    }
}
