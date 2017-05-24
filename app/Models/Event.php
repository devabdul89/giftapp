<?php
/**
 * Created by PhpStorm.
 * User: officeaccount
 * Date: 06/03/2017
 * Time: 10:10 AM
 */

namespace Models;


class Event extends Model
{

    private $title = '';
    private $id = '';
    private $date = '';
    private $description = '';
    private $productId = '';
    private $productVendor='amazon';
    private $private = false;
    private $minimumMembers = 1;
    private $members = [];
    private $message_code = "";

    public function __construct(){}

    /**
     * This function convert a model to a Json object
     **/
    public function toJson()
    {
        return (object)[
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'date' => $this->getDate(),
            'product_id'=>$this->getProductId(),
            'product_vendor'=>$this->getProductVendor(),
            'private'=>$this->isPrivate(),
            'minimum_members'=>$this->getMinimumMembers(),
            'message_code'=>$this->getMessageCode()
        ];
    }

    /**
     * @return string
     */
    public function getMessageCode()
    {
        return $this->message_code;
    }

    /**
     * @param string $message_code
     */
    public function setMessageCode($message_code)
    {
        $this->message_code = $message_code;
    }


    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return $this
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param string $date
     * @return $this
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * @param string $productId
     * @return $this
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;
        return $this;
    }

    /**
     * @return string
     */
    public function getProductVendor()
    {
        return $this->productVendor;
    }

    /**
     * @param string $productVendor
     * @return $this
     */
    public function setProductVendor($productVendor)
    {
        $this->productVendor = $productVendor;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPrivate()
    {
        return $this->private;
    }

    /**
     * @param bool $private
     * @return $this
     */
    public function setPrivate($private)
    {
        $this->private = $private;
        return $this;
    }

    /**
     * @return int
     */
    public function getMinimumMembers()
    {
        return $this->minimumMembers;
    }

    /**
     * @param int $minimumMembers
     * @return $this
     */
    public function setMinimumMembers($minimumMembers)
    {
        $this->minimumMembers = $minimumMembers;
        return $this;
    }
}