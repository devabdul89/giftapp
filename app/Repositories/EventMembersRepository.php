<?php
/**
 * Created by PhpStorm.
 * user: nomantufail
 * Date: 10/10/2016
 * Time: 10:13 AM
 */

namespace Repositories;


use Illuminate\Support\Facades\DB;
use LaraModels\User;

class EventMembersRepository extends Repository
{
    public function __construct()
    {
        $this->setModel(new User());
    }

    public function inviteAll($eventId, array $userIds){
        $records = [];
        foreach ($userIds as $id){
            $records[] = ['event_id'=>$eventId,'user_id'=>$id, 'created_at'=>date('Y-m-d h:i:s')];
        }
        return DB::table('event_user')->insert($records);
    }
    public function inviteAllByFbIds($eventId, array $fbIds){
        $users = User::whereIn('fb_id',$fbIds)->get();
        $records = [];
        foreach ($users as $user){
            $records[] = ['event_id'=>$eventId,'user_id'=>$user->id, 'created_at'=>date('Y-m-d h:i:s')];
        }
        return DB::table('event_user')->insert($records);
    }

    public function inviteAllByEmailIds($eventId, $emailMembers){
        $insertableArray = [];
        foreach ($emailMembers as $member){
            $member['event_id'] = $eventId;
            $insertableArray[] = $member;
        }
        return DB::table('awaiting_members')->insert($insertableArray);
    }
}