<?php
/**
 * Created by PhpStorm.
 * User: nomantufail
 * Date: 18/04/2017
 * Time: 3:25 PM
 */

namespace Libs;


use Davibennun\LaravelPushNotification\Facades\PushNotification;
use LaraModels\User;
use Repositories\EventsRepository;
use Repositories\NotificationsRepository;
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

    public function sendNotifications(){
        try{
            $admin = $this->event->admin;
            $title = 'Your event \''.$this->event->title.'\' is being processed. ';
            (new NotificationsRepository())->saveNotification([
                'title' => $title,
                'event'=>$this->event,
                'data' => json_encode($admin),
                'user_id'=>$admin->id,
                'type'=>'event_processing'
            ]);
            if($admin->device_id != null && $admin->device_type != null) {
                PushNotification::app($admin->device_type)
                    ->to($admin->device_id)
                    ->send($title, array(
                        'data' => array(//'event'=>$this->event
                        )
                    ));
            }
            return $this;
        }catch (\Exception $e){
            return $this;
        }
    }

    /*
     * process a given event
     * */
    public function process(){
        $this->createOrder()->updateEventStatus()->sendNotifications();
        return true;
    }
}