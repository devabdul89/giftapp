<?php

namespace Requests;

use Models\User;

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
            'profile_picture'=>'image',
            'device_type' => 'String|max:1000',
            'device_id' => 'String|max:1000'
        ];
    }

    public function newUser(){
        $user = new User();
        $user->setPassword(bcrypt($this->input('password')));
        $user->setPasswordCreated(1);
        $user->setEmail($this->input('email'));
        $user->setFullName($this->input('full_name'));
        $user->setDeviceId($this->input('device_id'));
        $user->setDeviceType($this->input('device_type'));
        $user->setAddress($this->input('address'));
        $user->setLoginBy('in_app');
        return $user;
    }
}
