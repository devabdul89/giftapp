<?php
/**
 * Created by PhpStorm.
 * User: nomantufail
 * Date: 12/8/2016
 * Time: 9:55 AM
 */
namespace App\Traits\Transformers;
trait UsersControllerTransformer{
    public function transformCheckins($checkins)
    {
        $transformedCheckins = [];
        collect($checkins)->each(function($checkedIn) use (&$transformedCheckins){
            $transformedCheckins[] = (object)[
                'id' => $checkedIn->id,
                'first_name' => $checkedIn->first_name,
                'last_name' => $checkedIn->last_name,
                'gender' => $checkedIn->gender,
                'birthday' => $checkedIn->birthday,
                'email' => $checkedIn->email,
                'about' => $checkedIn->about,
                'fb_id' => $checkedIn->fb_id,
                'i_am_interested_in' => $checkedIn->i_am_interested_in,
                'interested_in_me' => $checkedIn->interested_in_me,
                'he_like_me' => $checkedIn->he_like_me,
                'i_like_him' => $checkedIn->i_like_him,
            ];
        });
        return $transformedCheckins;
    }
}