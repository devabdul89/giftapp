<?php
/**
 * Created by PhpStorm.
 * User: officeaccount
 * Date: 28/02/2017
 * Time: 3:54 PM
 */

namespace Models;


class User
{

    public $id = '';
    public $fullName = '';
    public $email = '';
    public $sessionToken = '';
    public $password = '';
    public $profilePicture = '';
    public $walkthroughCompleted = 0;
    public $loginBy = '';
    public $passwordCreated = 0;
    public $fbId = '';
    public function __construct(
        $id = '',
        $fullName = '', $email = '', $profilePicture = '', $password = '', $sessionToken = '',
        $walkthroughCompleted = '', $loginBy = '', $passwordCreated='', $fbId = ''
    )
    {
        $this->setId($id);
        $this->setFullName($fullName);
        $this->setEmail($email);
        $this->setProfilePicture($profilePicture);
        $this->setPassword($password);
        $this->setSessionToken($sessionToken);
        $this->setWalkthroughCompleted($walkthroughCompleted);
        $this->setLoginBy($loginBy);
        $this->setPasswordCreated($passwordCreated);
        $this->setFbId($fbId);
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
     */
    public function setId($id)
    {
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
     */
    public function setFullName($fullName)
    {
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
     */
    public function setEmail($email)
    {
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