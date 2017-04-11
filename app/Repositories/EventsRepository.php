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

class EventsRepository extends Repository
{
    public function __construct()
    {
        $this->setModel(new Event());
    }

    public function all(){
        return $this->getModel()->with('members')->with('admin')->get();
    }

    public function getPublicEvents($page = 1){
        return $this->getModel()->where('private',0)->with('members')->with('admin')->paginate(2);
    }
    public function create($event){
        return $this->getModel()->create($event);
    }

    public function acceptEvent($eventId, $userId){
        return DB::table('event_user')->where('event_id',$eventId)->where('user_id',$userId)->update(['accepted'=>1]);
    }

    public function declineEvent($eventId, $userId){
        return DB::table('event_user')->where('event_id',$eventId)->where('user_id',$userId)->delete();
    }

    public function getEventDetail($eventId){
        return $this->getModel()->with('members')->find($eventId);
    }

    public function fetchEventInvitations($eventId){
        return $this->getModel()->find($eventId)->members;
    }
}