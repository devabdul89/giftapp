<?php
/**
 * Created by PhpStorm.
 * User: waqas
 * Date: 3/17/2016
 * Time: 12:08 PM
 */

namespace App\Libs\Auth;

use Repositories\UsersRepository;
use Models\User;
use Illuminate\Support\Facades\Hash;
class Auth
{

    /**
     * @param array $credentials
     * @return bool
     */
    public static function attempt(array $credentials)
    {
        try{
            $user = (new UsersRepository())->findByEmail($credentials['email']);
        }catch (\Exception $e){
            return false;
        }

        if(!Hash::check($credentials['password'], $user->getPassword()))
            return false;

        return true;
    }

    /**
     * @param User $authenticatedUser
     * @return User
     */
    public static function login(User $authenticatedUser){
        $authenticatedUser->setSessionToken(bcrypt($authenticatedUser->getId()));
        (new UsersRepository())->updateSessionToken($authenticatedUser);
        return $authenticatedUser;
    }

    public static function logout(User $authenticatedUser = null)
    {
        $authenticatedUser->session_id = "";
        $authenticatedUser->save();
        return true;
    }

    public static function authenticateWithToken($token)
    {
        return ((new UsersRepository())->findByToken($token) == null)?false:true;
    }

    /**
     * @return User $user
     * */
    public static function user()
    {
        if(isset(getallheaders()['Authorization']) && getallheaders()['Authorization'] != ""){
            $user = (new UsersRepository())->findByToken(getallheaders()['Authorization']);
            if($user != null)
                return $user;
        }
        return null;
    }

    public static function check()
    {
        return (Auth::user() != null);
    }
}