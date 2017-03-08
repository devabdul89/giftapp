<?php

namespace Requests;


use Models\BillingCard;

/**
 * Class AddBillingCardRequest
 * @package Requests
 */
class AddBillingCardRequest extends Request
{

    public function __construct(){
        parent::__construct();
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'card_holder' => 'String|required|max:100',
            'card_number' => 'String|required|max:100',
            'stripe_token' => 'String|required|max:200'
        ];
    }


    /**
     * @return BillingCard
     */
    public function card(){
        return (new BillingCard())
                ->setCardHolder($this->input('card_holder'))
                ->setCardNumber($this->input('card_number'))
                ->setCardType($this->input('card_type'))
                ->setCvc($this->input('cvc'))
                ->setCardExpiry($this->input('card_expiry'));
    }
}
