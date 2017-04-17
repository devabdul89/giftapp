<?php

namespace LaraModels;


use Illuminate\Database\Eloquent\Model;

class Friends extends Model
{
    protected $table = 'friends';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','friend_id','status'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function friend(){
        return $this->belongsTo(User::class, 'friend_id');
    }
}
