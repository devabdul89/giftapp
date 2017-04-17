<?php
/**
 * Created by PhpStorm.
 * user: nomantufail
 * Date: 10/10/2016
 * Time: 10:13 AM
 */

namespace Repositories;

use App\Exceptions\ValidationErrorException;
use Illuminate\Support\Facades\DB;
use LaraModels\Friends;
use LaraModels\User as DbUser;
use Models\User;

class UsersRepository extends Repository
{
    public function __construct()
    {
        $this->setModel(new DbUser());
    }


    public function friends($userId){
        $fields = ['id','fb_id','full_name','email','password','profile_picture','password','password_created','session_token','walkthrough_completed','login_by','image_setted','address','birthday','device_id','device_type','created_at','updated_at'];
        $cases = "";
        foreach ($fields as $field){
            $cases.="
            CASE
                WHEN iFriends.user_id = $userId
                    THEN
                        fusers.$field
                    else
                        users.$field
                END as $field,
            ";
        }
        return $this->mapFriends($this->getModel()
            ->select(DB::raw("$cases
             iFriends.user_id as sender_id, iFriends.friend_id as receiver_id,
             iFriends.status as status, iFriends.id as friendship_id"))
            ->leftJoin('friends as iFriends', 'users.id', '=', 'iFriends.user_id')
            ->leftJoin('users as fusers','iFriends.friend_id','=','fusers.id')
            ->where(function($query)use($userId){
                $query->where("iFriends.user_id","=",$userId);
                $query->orWhere("iFriends.friend_id",$userId);
            })
            ->get()->all());
    }

    /**
     * @param User $user
     * @return User $user
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
        $dbUser->login_by = $user->getLoginBy();
        $dbUser->image_setted = $user->getImageSetted();
        $dbUser->save();
        return $this->mapUser($dbUser);
    }

    public function update(User $user){
        $this->getModel()->where('id',$user->getId())->update([
            'full_name'=>$user->getFullName(),
            'email' => $user->getEmail(),
            'password'=>$user->getPassword(),
            'session_token' => $user->getSessionToken(),
            'profile_picture' => $user->getProfilePicture(),
            'walkthrough_completed' => $user->getWalkthroughCompleted(),
            'login_by' => $user->getLoginBy(),
            'password_created' => $user->getPasswordCreated(),
            'fb_id' => $user->getFbId(),
            'birthday' => $user->getBirthday(),
            'device_id' => $user->getDeviceId(),
            'device_type' => $user->getDeviceType(),
            'image_setted' => $user->getImageSetted(),
            'address' => $user->getAddress()
        ]);
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
     * @param $id
     * @return User|null
     */
    public function findById($id){
        $user = parent::findById($id);
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

    public function findByFbId($fb_id)
    {
        $user = $this->getModel()->where('fb_id',$fb_id)->first();
        return ($user != null)?$this->mapUser($user):$user;
    }

    public function addFriendByFbId($senderId, $fbId){
        $receiver = $this->findByFbId($fbId);
        return Friends::create([
            'user_id'=> $senderId,
            'friend_id'=>$receiver->getId()
            ]);
    }
    public function addFriendByEmail($senderId, $email){
        $receiver = $this->findByEmail($email);
        return Friends::create([
            'user_id'=> $senderId,
            'friend_id'=>$receiver->getId()
        ]);
    }

    public function acceptFriend($requestId){
        return Friends::where('id',$requestId)->update([
            'status'=> 1
        ]);
    }
    public function rejectFriend($requestId){
        return Friends::where('id',$requestId)->delete();
    }

    public function mapFriends($friends){
        return array_map([$this, 'mapFriend'], $friends);
    }

    public function mapFriend($friend){
        return (object)[
            'friend'=>$this->mapUser($friend),
            'friendship' => [
                'friendship_id'=>$friend->friendship_id,
                'sender_id'=>$friend->sender_id,
                'receiver_id'=>$friend->receiver_id,
                'status'=>$friend->status
            ]
        ];
    }

    public function mapUsersCollection($users){
        return array_map([$this, 'mapUser'], $users);
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
        $transformedUser->setBirthday($user->birthday);
        $transformedUser->setDeviceId($user->device_id);
        $transformedUser->setDeviceType($user->device_type);
        $transformedUser->setAddress($user->address);
        $transformedUser->setImageSetted($user->image_setted);

        // appending host name for profile picture
        if($transformedUser->getProfilePicture() != '' && $transformedUser->getProfilePicture() != null){
            if($user->login_by == 'in_app')
            $transformedUser->setProfilePicture(
                ($transformedUser->getImageSetted())?env('APP_URL').$transformedUser->getProfilePicture():
                    $transformedUser->getProfilePicture());
        }
        return $transformedUser;
    }

    public function getAllUsers()
    {
        return $this->mapUsersCollection($this->getModel()->get()->all());
    }
}