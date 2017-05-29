<?php

namespace Requests;

use Requests\Request;

class InviteMemberRequest extends Request
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
        $rules = ['event_id'=>'required|exists:events,id'];
        if($this->input('user_id') != null){
            $rules['user_id'] = 'exists:users,id';
        }else if($this->input('fb_id') != null){
            $rules['fb_id'] = 'exists:users,fb_id';
        }
        return $rules;
    }

    public function getEmailMember(){
        $member = null;
        if($this->input('email') != null){
            $exploded = explode(',',$this->input('email'));
            $member = [
                'email'=>$exploded[0],
                'full_name'=>$exploded[1]
            ];
        }
        return $member;
    }
}
