<?php
/**
 * Created by PhpStorm.
 * User: waqas
 * Date: 3/16/2016
 * Time: 1:46 PM
 */

namespace App\Http;

use App\Libs\Auth\Auth;

class Response
{
    public $CUSTOM_STATUS = 0;
    public $HTTP_STATUS = 200;
    public $ERROR_MESSAGES = [];

    public function setHttpStatus($status)
    {
        $this->HTTP_STATUS = $status;
        return $this;
    }

    public function getHttpStatus()
    {
        return $this->HTTP_STATUS;
    }

    public function setCustomStatus($status)
    {
        $this->CUSTOM_STATUS = $status;
        return $this;
    }

    public function getCustomStatus()
    {
        return $this->CUSTOM_STATUS;
    }

    public function setErrorMessages($messages)
    {
        $this->ERROR_MESSAGES = $messages;
        return $this;
    }

    public function getErrorMessages()
    {
        return $this->ERROR_MESSAGES;
    }


    /**
     * @param $response
     * @param $headers
     * @return json
     * @description
     * following function accepts data from
     * controllers and return a pre-setted view.
     **/
    public function respond(array $response = [], array $headers = []){
        $response['status'] = ($this->getHttpStatus() == 200)?1:0;
        $response['message'] = (isset($response['message']))?$response['message']:(($response['status'] == 1)?config('constants.SUCCESS_MESSAGE'):config('constants.ERROR_MESSAGE'));
        $response['access_token'] = (!isset($response['access_token']))?((Auth::user() != null)?Auth::user()->access_token: ""):$response['access_token'];
        return response()->json($response, $this->getHttpStatus(), $headers);
    }

    public function respondWithErrors()
    {
        return $this->respond([
            'status' => 0,
            'error' => [
                'messages' => $this->getErrorMessages(),
                'code' => $this->getCustomStatus(),
                'http_status' => $this->getHttpStatus(),
            ],
            'data' => null
        ]);
    }

    public function respondNotFound($messages = ["record not found"])
    {
        return $this->setHttpStatus(404)->setCustomStatus(404)->setErrorMessages($messages)->respondWithErrors();
    }

    public function respondInternalServerError($messages = ["Something went wrong with the server!"])
    {
        return $this->setHttpStatus(500)->setCustomStatus(505)->setErrorMessages($messages)->respondWithErrors();
    }

    public function respondValidationFails($messages = ["Your request did not passed our server requirements!"])
    {
        return $this->setHttpStatus(400)->setCustomStatus(400)->setErrorMessages($messages)->respondWithErrors();
    }

    public function respondAuthenticationFailed($messages = ["Dear user you are not logged in."])
    {
        return $this->setHttpStatus(401)->setCustomStatus(401)->setErrorMessages($messages)->respondWithErrors();
    }

    public function respondInvalidCredentials($messages = ["Invalid username or password"])
    {
        return $this->setHttpStatus(404)->setCustomStatus(404)->setErrorMessages($messages)->respondWithErrors();
    }

    public function respondAccessTokenNotProvided($messages = ["Session expired, please login again."])
    {
        return $this->setHttpStatus(404)->setCustomStatus(404)->setErrorMessages($messages)->respondWithErrors();
    }

    public function respondInvalidAccessToken($messages = ["Session expired, please login again."])
    {
        return $this->setHttpStatus(404)->setCustomStatus(404)->setErrorMessages($messages)->respondWithErrors();
    }

    public function respondOwnershipConstraintViolation($messages = ["Ownership Constraint Violation."])
    {
        return $this->setHttpStatus(404)->setCustomStatus(404)->setErrorMessages($messages)->respondWithErrors();
    }

}