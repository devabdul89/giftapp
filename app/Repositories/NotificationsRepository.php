<?php
/**
 * Created by PhpStorm.
 * user: nomantufail
 * Date: 10/10/2016
 * Time: 10:13 AM
 */

namespace Repositories;

use LaraModels\Notification;

class NotificationsRepository extends Repository
{
    public function __construct()
    {
        $this->setModel(new Notification());
    }

    public function saveNotification($notification){
        return $this->getModel()->create($notification);
    }

    public function fetchUserNotification($userId){
        return $this->getModel()->where('user_id',$userId)->orderBy('created_at','desc')->get();
    }
}