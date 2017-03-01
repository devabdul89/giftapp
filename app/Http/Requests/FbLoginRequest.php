<?php

namespace Requests;

use Models\User;
use Requests\Request;

class FbLoginRequest extends Request
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
            'fb_id'=>'required',
            'email'=>'required|email',
            'full_name'=>'required|max:100'
        ];
    }

    public function newUser(){
        $user = new User();
        $user->setFbId($this->input('fb_id'));
        $user->setEmail($this->input('email'));
        $user->setProfilePicture($this->input('profile_picture'));
        $user->setFullName($this->input('full_name'));
        return $user;
    }
}
