<?php

namespace Requests;

use Repositories\UsersRepository;
use Requests\Request;

class AddAsFriendRequest extends Request
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
        $messages = [];
        $messages['fb_id.required'] = 'Please profile fb_id or email to add as friend.';
        return $messages;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if($this->input('fb_id') != null){
            return [
                'fb_id'=>'required|exists:users,fb_id'
            ];
        }

        if($this->input('email') != null){
            return [
                'email'=>'required|exists:users,email'
            ];
        }

        return [
            'fb_id'=>'required'
        ];
    }
}
