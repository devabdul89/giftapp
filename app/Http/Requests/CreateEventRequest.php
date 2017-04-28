<?php

namespace Requests;

use Requests\Request;

class CreateEventRequest extends Request
{

    public function __construct(){
        parent::__construct();
        $this->authenticatable = true;
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
            'date'=>'required',
            'title'=>'required'
        ];
    }

    public function getMemberIds(){
        $members = ($this->input('members') != null)?explode(',',$this->input('members')):[];
        array_push($members, $this->user->getId());
        return $members;
    }

    public function getFbMemberIds(){
        return ($this->input('fb_members') != null)?explode(',',$this->input('fb_members')):[];
    }
}
