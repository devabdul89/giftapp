<?php
/**
 * Created by PhpStorm.
 * User: officeaccount
 * Date: 01/03/2017
 * Time: 12:58 PM
 */

namespace Models;


abstract class Model
{

    /**
     * This function convert a model to a Json object
     **/
    public abstract function toJson();

    /**
     * Models are by default strict
     * it means that they will validate them self when you assign values
     * to there properties. if they feel anything wrong, they will
     * throw an exception "ValidationErrorException" with the message
     *
     * user can set this property to false if he doesn't want to allow
     * these models to validate data.
     **/
    private $strict = true;

    /**
     * @return mixed
     */
    public function strict()
    {
        return $this->strict;
    }

    /**
     * @param mixed $strict
     */
    public function setStrict($strict)
    {
        $this->strict = $strict;
    }


}