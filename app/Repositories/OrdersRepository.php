<?php
/**
 * Created by PhpStorm.
 * user: nomantufail
 * Date: 10/10/2016
 * Time: 10:13 AM
 */

namespace Repositories;


use Illuminate\Support\Facades\DB;
use LaraModels\Order;

class OrdersRepository extends Repository
{
    public function __construct()
    {
        $this->setModel(new Order());
    }

    public function getEventOrder($eventId){
        return $this->getModel()->where('event_id',$eventId)->first();
    }

    public function createOrder($order){
        return $this->getModel()->create($order);
    }

    public function getUserCompletedOrders(){
        return $this->getModel()->where('status','!=', 0)->get();
    }
}