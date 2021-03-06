<?php

namespace Requests;

use Requests\Request;

class CancelEventMemberRequest extends Request
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
            'event_id'=>'required|exists:events,id',
            'user_id'=>'exists:users,id',
            'awaiting_member_id'=>'exists:awaiting_members,id'
        ];
    }
}
