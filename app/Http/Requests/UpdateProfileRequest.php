<?php

namespace Requests;

use Models\User;
use Requests\Request;

class UpdateProfileRequest extends Request
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
            'email' => 'required|email|max:100',
            'full_name' => 'required|max:100'
        ];
    }


    /**
     * @return User
     */
    public function updatedUser()
    {
        $user = clone($this->user);
        return $user
            ->setEmail($this->input('email'))
            ->setFullName($this->input('full_name'))
            ->setAddress($this->input('address'));
    }
}
