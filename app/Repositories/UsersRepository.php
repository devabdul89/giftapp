<?php
/**
 * Created by PhpStorm.
 * user: nomantufail
 * Date: 10/10/2016
 * Time: 10:13 AM
 */

namespace Repositories;

use App\Exceptions\ValidationErrorException;
use LaraModels\User as DbUser;
use Models\User;

class UsersRepository extends Repository
{
    public function __construct()
    {
        $this->setModel(new DbUser());
    }


    /**
     * @param User $user
     * @return mixed
     * @throws ValidationErrorException
     */
    public function store($user)
    {
        $dbUser = new DbUser();
        $dbUser->fb_id = $user->getFbId();
        $dbUser->password = $user->getPassword();
        $dbUser->email = $user->getEmail();
        $dbUser->full_name = $user->getFullName();
        $dbUser->profile_picture = $user->getProfilePicture();
        $dbUser->save();
        return $user;
    }

    public function updateSessionToken(User $user){
        $this->updateWhere(['id'=>$user->getId()], ['session_token'=>$user->getSessionToken()]);
        return $user;
    }

    /**
     * @param $email
     * @return User|null
     */
    public function findByEmail($email){
        $user = $this->getModel()->where('email',$email)->first();
        return ($user != null)?$this->mapUser($user):$user;
    }

    /**
     * @param $session_token
     * @return User|null
     */
    public function findByToken($session_token){
        $user = $this->getModel()->where('session_token',$session_token)->first();
        return ($user != null)?$this->mapUser($user):$user;
    }

    public function mapUsersCollection($users){

    }


    /**
     * @param $user
     * @return User
     */
    public function mapUser($user){
        $transformedUser = new User();
        $transformedUser->setId($user->id);
        $transformedUser->setEmail($user->email);
        $transformedUser->setFbId($user->fb_id);
        $transformedUser->setFullName($user->full_name);
        $transformedUser->setProfilePicture($user->profile_picture);
        $transformedUser->setLoginBy($user->login_by);
        $transformedUser->setPasswordCreated($user->password_created);
        $transformedUser->setPassword($user->password);
        $transformedUser->setWalkthroughCompleted($user->walkthrough_completed);
        $transformedUser->setSessionToken($user->session_token);
        return $transformedUser;
    }

}