<?php
/**
 * Created by PhpStorm.
 * User: nomantufail
 * Date: 18/04/2017
 * Time: 3:25 PM
 */

namespace Libs;


use LaraModels\User;

class EventProcessor
{
    public $event = null;
    public function setEvent($event){
        $this->event = $event;
        return $this;
    }

    /*
     * stripe payments for all event invited members
     * */
    private function cashOut(){
        (new User())->charge(100);
        return $this;
    }

    /*
     * create a new order for the under-process event
     * */
    public function createOrder(){
        //todo: implement
        return $this;
    }

    /*
     * update event status as processed in events table.
     * */
    public function updateEventStatus(){
        //todo: implement
        return $this;
    }

    /*
     * process a given event
     * */
    public function process(){
        $this->cashOut()->createOrder()->updateEventStatus();
        return true;
    }
}