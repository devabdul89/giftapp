<?php
/**
 * Created by PhpStorm.
 * User: officeaccount
 * Date: 28/02/2017
 * Time: 3:54 PM
 */

namespace Models;


use App\Exceptions\ValidationErrorException;

class User extends Model
{

    private $id = '';
    private $fullName = '';
    private $email = '';
    private $sessionToken = '';
    private $password = '';
    private $profilePicture = '';
    private $walkthroughCompleted = 0;
    private $loginBy = '';
    private $passwordCreated = 0;
    private $fbId = '';
    private $birthday = '';
    private $deviceId = '';
    private $deviceType = '';
    private $imageSetted = false;
    private $address = '';

    public function __construct()
    {

    }

    /**
     * @return object
     */
    public function toJson()
    {
        return (object)[
            'id'=>$this->getId(),
            'fullName'=>$this->getFullName(),
            'email' => $this->getEmail(),
            'password'=>$this->getPassword(),
            'sessionToken' => $this->getSessionToken(),
            'profilePicture' => $this->getProfilePicture(),
            'walkthroughCompleted' => $this->getWalkthroughCompleted(),
            'loginBy' => $this->getLoginBy(),
            'passwordCreated' => $this->getPasswordCreated(),
            'fbId' => $this->getFbId(),
            'birthday' => $this->getBirthday(),
            'deviceId' => $this->getDeviceId(),
            'deviceType' => $this->getDeviceType(),
            'imageSetted' => $this->getImageSetted(),
            'address' => $this->getAddress()
        ];
        // TODO: Implement toJson() method.
    }

    /**
     * @return string
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param string $birthday
     * @return $this
     */
    public function setBirthday($birthday)
    {
        $this->birthday = ($birthday == "")?null:$birthday;
        return $this;
    }

    /**
     * @return string
     */
    public function getDeviceId()
    {
        return $this->deviceId;
    }

    /**
     * @param string $deviceId
     * @return $this
     */
    public function setDeviceId($deviceId)
    {
        $this->deviceId = ($deviceId == null)?"":$deviceId;
        return $this;
    }

    /**
     * @return string
     */
    public function getDeviceType()
    {
        return $this->deviceType;
    }

    /**
     * @param string $deviceType
     * @return $this
     */
    public function setDeviceType($deviceType)
    {
        $this->deviceType = ($deviceType == null)?"":$deviceType;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getImageSetted()
    {
        return $this->imageSetted;
    }

    /**
     * @param string $imageSetted
     * @return $this
     */
    public function setImageSetted($imageSetted)
    {
        $this->imageSetted = ($imageSetted == "" || $imageSetted == null)?0:$imageSetted;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return $this;
     */
    public function setAddress($address)
    {
        $this->address = ($address == null)?"":$address;
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
     * @param string $id
     * @throws ValidationErrorException
     * @return $this
     */
    public function setId($id)
    {
        if($this->strict()){
            if($id == '')
                throw new ValidationErrorException('Usersl\'s Id cannot b empty');
        }
        $this->id = intval($id);
        return $this;
    }



    /**
     * @return string
     */
    public function getFbId()
    {
        return $this->fbId;
    }

    /**
     * @param string $fbId
     * @return $this
     */
    public function setFbId($fbId)
    {
        $this->fbId = $fbId;
        return $this;
    }


    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @param string $fullName
     * @throws ValidationErrorException
     * @return $this
     */
    public function setFullName($fullName, $strict = true)
    {
        if($this->strict()){
            if($fullName == ''){
                throw new ValidationErrorException('users\'s full name cannot be empty');
            }
        }
        $this->fullName = $fullName;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @throws ValidationErrorException
     * @return $this
     */
    public function setEmail($email)
    {
        if($this->strict()){
            if($email == ''){
                throw new ValidationErrorException('Email is required');
            }
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new ValidationErrorException('Email is not valid');
            }
        }
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getSessionToken()
    {
        return $this->sessionToken;
    }

    /**
     * @param string $sessionToken
     * @return $this;
     */
    public function setSessionToken($sessionToken)
    {
        $this->sessionToken = $sessionToken;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return $this;
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getProfilePicture()
    {
        return $this->profilePicture;
    }

    /**
     * @param string $profilePicture
     * @return $this;
     */
    public function setProfilePicture($profilePicture)
    {
        $this->profilePicture = ($profilePicture == null)?'':$profilePicture;
        if($profilePicture != '')
            $this->setImageSetted(true);
        return $this;
    }

    /**
     * @return string
     */
    public function getWalkthroughCompleted()
    {
        return $this->walkthroughCompleted;
    }

    /**
     * @param string $walkthroughCompleted
     * @return $this
     */
    public function setWalkthroughCompleted($walkthroughCompleted)
    {
        $this->walkthroughCompleted = intval($walkthroughCompleted);
        return $this;
    }

    /**
     * @return string
     */
    public function getLoginBy()
    {
        return $this->loginBy;
    }

    /**
     * @param string $loginBy
     * @return $this
     */
    public function setLoginBy($loginBy)
    {
        $this->loginBy = $loginBy;
        return $this;
    }

    /**
     * @return string
     */
    public function getPasswordCreated()
    {
        return $this->passwordCreated;
    }

    /**
     * @param string $passwordCreated
     * @return $this;
     */
    public function setPasswordCreated($passwordCreated)
    {
        $this->passwordCreated = intval($passwordCreated);
        return $this;
    }
}