<?php

namespace Requests;

use Models\User;
use Requests\Request;

class RegisterRequest extends Request
{

    public function __construct(){
        parent::__construct();
        $this->authenticatable = false;
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
            //TODO: apply rules here.
            'full_name'=>'required|max:100',
            'email'=>'required|unique:users',
            'password' =>'String|required|max:15|min:6',
            'profile_picture'=>'image'
        ];
    }

    public function newUser(){
        $user = new User();
        $user->setPassword($this->input('password'));
        $user->setEmail($this->input('email'));
        $user->setFullName($this->input('full_name'));
        return $user;
    }
}
