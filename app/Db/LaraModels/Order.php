<?php

namespace LaraModels;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'event_id','status','price'
    ];

    public function event(){
        return $this->belongsTo(Event::class);
    }

    public function payment(){
        return $this->hasOne(Payment::class);
    }
}
