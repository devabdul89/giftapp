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
            'profilePicture' =>$this->getProfilePicture(),
            'walkthroughCompleted' => $this->getWalkthroughCompleted(),
            'loginBy' => $this->getLoginBy(),
            'passwordCreated' => $this->getPasswordCreated(),
            'fbId' => $this->getFbId()
        ];
        // TODO: Implement toJson() method.
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
     */
    public function setId($id)
    {
        if($this->strict()){
            if($id == '')
                throw new ValidationErrorException('Usersl\'s Id cannot b empty');
        }
        $this->id = intval($id);
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
     */
    public function setFbId($fbId)
    {
        $this->fbId = $fbId;
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
     */
    public function setFullName($fullName, $strict = true)
    {
        if($this->strict()){
            if($fullName == ''){
                throw new ValidationErrorException('users\'s full name cannot be empty');
            }
        }
        $this->fullName = $fullName;
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
     */
    public function setEmail($email)
    {
        if($this->strict()){
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new ValidationErrorException('Email is not valid');
            }
            if($email == ''){
                throw new ValidationErrorException('Email is required');
            }
        }
        $this->email = $email;
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
     */
    public function setSessionToken($sessionToken)
    {
        $this->sessionToken = $sessionToken;
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
     */
    public function setPassword($password)
    {
        $this->password = $password;
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
     */
    public function setProfilePicture($profilePicture)
    {
        $this->profilePicture = $profilePicture;
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
     */
    public function setWalkthroughCompleted($walkthroughCompleted)
    {
        $this->walkthroughCompleted = intval($walkthroughCompleted);
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
     */
    public function setLoginBy($loginBy)
    {
        $this->loginBy = $loginBy;
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
     */
    public function setPasswordCreated($passwordCreated)
    {
        $this->passwordCreated = intval($passwordCreated);
    }
}