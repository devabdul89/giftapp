<?php

namespace LaraModels;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'events';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description','admin_id', 'date', 'product_id', 'price','currency', 'shipping_address', 'product_vendor','private','minimum_members', 'lat_lng','message_code', 'message_invite_count'
    ];

    public function members(){
        return $this->belongsToMany(User::class)->withPivot('accepted');
    }

    public function awaiting_members(){
        return $this->hasMany(AwaitingMember::class,'event_id');
    }

    public function admin(){
        return $this->belongsTo(User::class,'admin_id');
    }

    public function order(){
        return $this->hasOne(Order::class);
    }
}
