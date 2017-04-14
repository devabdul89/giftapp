<?php

namespace Requests;

use Requests\Request;

class UpdateEventRequest extends Request
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
            'event_id'=>'required|exists:events,id'
        ];
    }

    public function updateableData(){
        $data = [];
        if($this->input('title') != null){
            $data['title'] = $this->input('title');
        }
        if($this->input('description') != null){
            $data['description'] = $this->input('description');
        }
        if($this->input('product_id') != null){
            $data['product_id'] = $this->input('product_id');
        }
        if($this->input('date') != null){
            $data['date'] = $this->input('date');
        }
        if($this->input('price') != null){
            $data['price'] = $this->input('price');
        }
        if($this->input('currency') != null){
            $data['currency'] = $this->input('currency');
        }
        if($this->input('product_vendor') != null){
            $data['product_vendor'] = $this->input('product_vendor');
        }
        if($this->input('shipping_address') != null){
            $data['shipping_address'] = $this->input('shipping_address');
        }
        if($this->input('minimum_members') != null){
            $data['minimum_members'] = $this->input('minimum_members');
        }
        return $data;
    }
}
