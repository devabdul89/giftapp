<?php
/**
 * Created by PhpStorm.
 * user: nomantufail
 * Date: 10/10/2016
 * Time: 10:13 AM
 */

namespace Repositories;


use Illuminate\Support\Facades\DB;
use LaraModels\Payment;

class PaymentsRepository extends Repository
{
    public function __construct()
    {
        $this->setModel(new Payment());
    }

    public function create($payment){
        return $this->getModel()->create($payment);
    }
}