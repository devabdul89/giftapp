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
        'title', 'description', 'date','private', 'product_id', 'product_vendor','private','minimum_members'
    ];

    public function members(){
        return $this->belongsToMany(User::class)->withPivot('accepted');
    }
}
