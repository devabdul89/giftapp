<?php

namespace LaraModels;

use Illuminate\Database\Eloquent\Model;

class BillingCard extends Model
{
    protected $table = 'billing_cards';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'card_holder', 'card_number','card_type', 'cvc', 'card_expiry'
    ];
}
