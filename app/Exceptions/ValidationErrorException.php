<?php
/**
 * Created by PhpStorm.
 * User: officeaccount
 * Date: 01/03/2017
 * Time: 12:38 PM
 */

namespace App\Exceptions;


use Exception;

class ValidationErrorException extends \Exception
{
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}