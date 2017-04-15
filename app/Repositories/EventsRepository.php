<?php
/**
 * Created by PhpStorm.
 * user: nomantufail
 * Date: 10/10/2016
 * Time: 10:13 AM
 */

namespace Repositories;


use Illuminate\Support\Facades\DB;
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
        }))->with('admin')->get());
    }

    public function getMyEvents($userId){
        return $this->add_joined_key(User::where('id',$userId)->first()->events()->with('admin')->with((array('members'=>function($query){
            $query->orderBy('created_at','desc');
        })))->orderBy('created_at','desc')->paginate(10));
    }

    public function getPublicEvents($page = 1){
        return $this->add_joined_key($this->getModel()->where('private',0)->with(array('members'=>function($query){
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
}