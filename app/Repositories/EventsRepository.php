<?php
/**
 * Created by PhpStorm.
 * user: nomantufail
 * Date: 10/10/2016
 * Time: 10:13 AM
 */

namespace Repositories;


use Illuminate\Support\Facades\DB;
use LaraModels\AwaitingMember;
use LaraModels\Event;
use LaraModels\EventUser;
use LaraModels\User;

class EventsRepository extends Repository
{
    public function __construct()
    {
        $this->setModel(new Event());
    }

    public function add_joined_key($events){
        foreach ($events as &$event){
            $event->joined_members_count = 0;
            foreach ($event->members as $member){
                if($member->pivot->accepted > 0){
                    $event->joined_members_count += 1;
                }
            }
        }
        return $events;
    }

    public function all(){
        return $this->add_joined_key($this->getModel()->with(array('members'=>function($query)
        {
            $query->orderBy('created_at', 'desc');
        }))->with('awaiting_members')->with('admin')->get());
    }

    public function decrementMessageHashCount($eventId){
        return DB::table('events')->where('id',$eventId)->decrement('message_invite_count');
    }

    public function getReadyEvents(){
        $events = $this->getModel()->where('date','<=',date('Y-m-d'))->with('members')->with('awaiting_members')->where('status',0)->get();
        $final_events = [];
        foreach($events as $event){
            $joinedMembers = 0;
            foreach ($event->members as $member){
                if($member->pivot->accepted > 0){
                    $joinedMembers++;
                }
            }

            if($joinedMembers >= $event->minimum_members){
                array_push($final_events,$event);
            }
        }
        return $final_events;
    }

    public function getEventMembers($eventId){
        return $this->add_joined_key($this->getModel()->with(array('members'=>function($query)
        {
            $query->orderBy('created_at', 'desc');
        }))->with('awaiting_members')->where('id',$eventId)->get())[0];
    }
    public function cancelMember($eventId, $userId){
        return EventUser::where('event_id',$eventId)->where('user_id',$userId)->delete();
    }
    public function getMyEvents($userId){
        return $this->add_joined_key(User::where('id',$userId)->first()->events()->where('status','!=',3)->with('admin')->with('awaiting_members')->with((array('members'=>function($query){
            $query->orderBy('created_at','desc');
        })))->orderBy('created_at','desc')->paginate(10));
    }

    public function getPublicEvents($page = 1){
        return $this->add_joined_key($this->getModel()->where('private',0)->where('status','!=',3)->with('awaiting_members')->with(array('members'=>function($query){
            $query->orderBy('created_at','desc');
        }))->with('admin')->orderBy('created_at','desc')->paginate(10));
    }

    public function create($event){
        return $this->getModel()->create($event);
    }

    public function joinEvent($eventId, $userId){
        return EventUser::create([
            'event_id'=>$eventId,
            'user_id'=>$userId,
            'accepted' => 1
        ]);
    }

    public function acceptEvent($eventId, $userId){
        return DB::table('event_user')->where('event_id',$eventId)->where('user_id',$userId)->update(['accepted'=>1]);
    }

    public function declineEvent($eventId, $userId){
        return DB::table('event_user')->where('event_id',$eventId)->where('user_id',$userId)->delete();
    }

    public function getEventDetail($eventId){
        $event = $this->getModel()->with('members')->find($eventId);
        if($event != null){
            return $this->add_joined_key([$event])[0];
        }else{
            return $event;
        }
    }

    public function fetchEventInvitations($eventId){
        return $this->getModel()->find($eventId)->members;
    }

    public function cancelEvent($eventId){
        $this->deleteById($eventId);
    }

    public function updateStatus($eventId, $status = 1){
        return $this->getModel()->where('id',$eventId)->update(['status'=>$status]);
    }

    public function userCompletedEvents($userId){
        return $this->add_joined_key($this->getModel()->select(DB::raw("events.*"))->where('events.status',3)->where('event_user.user_id',$userId)
            ->leftJoin('event_user','event_user.event_id','=','events.id')
            ->with('members')->with('admin')->get());
    }

    public function expireOutDatedEvents(){
        $events = $this->getModel()->where('date','<=',date('Y-m-d'))->with('members')->where('status',0)->get();
        //todo: handle those members also who haven't joined yet.
        $final_events = [];
        foreach($events as $event){
            $joinedMembers = 0;
            foreach ($event->members as $member){
                if($member->pivot->accepted > 0){
                    $joinedMembers++;
                }
            }

            if($joinedMembers < $event->minimum_members){
                array_push($final_events,$event->id);
            }
        }
        return DB::table('events')->whereIn('id', $final_events)->update(['status'=>2]);
    }

    public function cancelAwaitingMember($id){
        return AwaitingMember::where('id',$id)->delete();
    }
    public function cancelAwaitingMemberByEmail($email){
        return AwaitingMember::where('email',$email)->delete();
    }

    public function findByHashCode($messageCode){
        return $this->getModel()->where('message_code',$messageCode)->where('message_invite_count','>',0)->first();
    }
}