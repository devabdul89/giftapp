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
        return $this->getModel()->with('members')->get();
    }

    public function create($event){
        return $this->getModel()->create($event);
    }
}