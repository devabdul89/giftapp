<?php

namespace LaraModels;

use Illuminate\Database\Eloquent\Model;

class AwaitingMember extends Model
{
    protected $table = 'awaiting_members';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'event_id','full_name','email'
    ];

    public function event(){
        return $this->belongsTo(Event::class);
    }
}
