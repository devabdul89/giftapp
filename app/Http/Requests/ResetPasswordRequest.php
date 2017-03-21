<?php

namespace Requests;

use Requests\Request;

class ResetPasswordRequest extends Request
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

    public function messages()
    {
        return array_merge(parent::messages(),[
            'old_password.match_password'=>'old password is incorrect'
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'old_password'=>'required|match_password:'.$this->user->getId(),
            'new_password' => 'required|string|min:6|max:15'
        ];
    }
}
