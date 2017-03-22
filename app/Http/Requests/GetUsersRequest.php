<?php

namespace Requests;


use Models\BillingCard;

/**
 * Class AddBillingCardRequest
 * @package Requests
 */
class GetUsersRequest extends Request
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

        ];
    }


    /**
     * @return BillingCard
     */
}
