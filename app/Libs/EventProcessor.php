<?php
/**
 * Created by PhpStorm.
 * User: nomantufail
 * Date: 18/04/2017
 * Time: 3:25 PM
 */

namespace Libs;


use LaraModels\User;
use Repositories\EventsRepository;
use Repositories\OrdersRepository;
use Repositories\PaymentsRepository;

class EventProcessor
{
    public $event = null;
    public function __construct($event = null)
    {
        $this->event = $event;
        return $this;
    }

    public function setEvent($event){
        $this->event = $event;
        return $this;
    }

    /*
     * stripe payments for all event invited members
     * */
    private function cashOut(){
        //(new User())->charge(100);
        return $this;
    }

    /*
     * create a new order for the under-process event
     * */
    public function createOrder(){
        $order = (new OrdersRepository())->createOrder([
           'event_id'=>$this->event->id,
            'price'=>$this->event->price
        ]);
        return $this->createPayment(['order_id'=>$order->id,'price'=>$order->price]);
    }

    public function createPayment($payment){
        (new PaymentsRepository())->create($payment);
        return $this;
    }

    /*
     * update event status as processed in events table.
     * */
    public function updateEventStatus(){
        (new EventsRepository())->updateStatus($this->event->id, 1);
        return $this;
    }

    /*
     * process a given event
     * */
    public function process(){
        $this->createOrder()->updateEventStatus();
        return true;
    }
}