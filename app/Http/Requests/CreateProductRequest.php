<?php

namespace Requests;

use Requests\Request;

class CreateProductRequest extends Request
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
            'title'=>'required|max:100'
        ];
    }

    public function getProductInfo(){
        return [
            'title'=>$this->input('title'),
            'detail'=>$this->input('detail'),
            'size_available'=>$this->input('size_available'),
            'material'=>$this->input('material'),
            'price' =>$this->input('price')
        ];
    }
}
