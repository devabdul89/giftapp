<?php
/**
 * Created by PhpStorm.
 * User: officeaccount
 * Date: 06/03/2017
 * Time: 10:10 AM
 */

namespace Models;


class BillingCard extends Model
{

    private $cardHolder = '';
    private $cardNumber = '';
    private $cardType = '';
    private $cvc = '';
    private $cardExpiry = '';

    public function __construct(){}

    /**
     * This function convert a model to a Json object
     **/
    public function toJson()
    {
        return (object)[
            'cardHolder' => $this->getCardHolder(),
            'cardNumber' => $this->getCardNumber(),
            'cardType' => $this->getCardType(),
            'cvc' => $this->getCvc(),
            'cardExpiry'=>$this->getCardExpiry()
        ];
    }

    /**
     * @return string
     */
    public function getCardHolder()
    {
        return $this->cardHolder;
    }

    /**
     * @param string $cardHolder
     * @return $this
     */
    public function setCardHolder($cardHolder)
    {
        $this->cardHolder = $cardHolder;
        return $this;
    }

    /**
     * @return string
     */
    public function getCardNumber()
    {
        return $this->cardNumber;
    }

    /**
     * @param string $cardNumber
     * @return $this
     */
    public function setCardNumber($cardNumber)
    {
        $this->cardNumber = $cardNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getCardType()
    {
        return $this->cardType;
    }

    /**
     * @param string $cardType
     * @return $this
     */
    public function setCardType($cardType)
    {
        if($cardType == "" || $cardType == null)
            $this->cardType = 'visa';
        else
            $this->cardType = $cardType;
        return $this;
    }

    /**
     * @return string
     */
    public function getCvc()
    {
        return $this->cvc;
    }

    /**
     * @param string $cvc
     * @return $this
     */
    public function setCvc($cvc)
    {
        $this->cvc = $cvc;
        return $this;
    }

    /**
     * @return string
     */
    public function getCardExpiry()
    {
        return $this->cardExpiry;
    }

    /**
     * @param string $cardExpiry
     * @return $this
     */
    public function setCardExpiry($cardExpiry)
    {
        $this->cardExpiry = $cardExpiry;
        return $this;
    }


}