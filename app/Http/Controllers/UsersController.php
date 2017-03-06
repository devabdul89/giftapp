<?php

namespace App\Http\Controllers;

use App\Http\Response;
use App\Traits\Transformers\UsersControllerTransformer;
use Repositories\UsersRepository;
use Requests\UpdateProfilePictureRequest;
use Requests\UpdateProfileRequest;

class UsersController extends ParentController
{
    use UsersControllerTransformer;

    public $usersRepo = null;
    public $response = null;
    public function __construct(UsersRepository $usersRepository)
    {
        $this->usersRepo = $usersRepository;
        $this->response = new Response();
    }

    public function updateProfilePicture(UpdateProfilePictureRequest $request)
    {
        $user = clone($request->user);
        $user = $this->usersRepo->store($user->setProfilePicture($this->saveProfilePicture($request->file('profile_picture'))));
        $user = $user->setProfilePicture(env('APP_URL').$user->getProfilePicture());
        return $this->response->respond(['data'=>[
            'user'=>$user->toJson()
        ]]);
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        return $this->response->respond(['data'=>[
            'user'=>$this->usersRepo->update($request->updatedUser())->toJson()
        ]]);
    }

    private function saveProfilePicture($image, $path = 'images/profile_pictures/'){
        $filename = uniqid().$image->getClientOriginalName();
        $image->move(public_path($path), $filename);
        return "public/".$path.$filename;
    }
}