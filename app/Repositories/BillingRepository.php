<?php
/**
 * Created by PhpStorm.
 * user: nomantufail
 * Date: 10/10/2016
 * Time: 10:13 AM
 */

namespace Repositories;

use LaraModels\BillingCard as LaraBillingCard;
use Models\BillingCard;

class BillingRepository extends Repository
{
    public function __construct()
    {
        $this->setModel(new LaraBillingCard());
    }


    public function storeCardInformation($userId, BillingCard $billingCard){
        $this->getModel()->create([
            'user_id' => $userId,
            'card_holder' => $billingCard->getCardHolder(),
            'card_number' => $billingCard->getCardNumber(),
            'card_type' => $billingCard->getCardType(),
            'cvc' => $billingCard->getCvc(),
            'card_expiry'=>$billingCard->getCardExpiry()
        ]);
        return $billingCard;
    }
}