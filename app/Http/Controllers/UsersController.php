<?php

namespace App\Http\Controllers;

use App\Exceptions\ValidationErrorException;
use App\Http\Response;
use Repositories\UsersRepository;
use Requests\ResetPasswordRequest;
use Requests\UpdateProfilePictureRequest;
use Requests\UpdateProfileRequest;
use Requests\UpdateWalkthroughStatusRequest;
use Traits\ImageHelper;

class UsersController extends ParentController
{
    use ImageHelper;

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
        $user = $this->usersRepo->update($user->setProfilePicture($this->saveProfilePicture($request->file('profile_picture')))->setImageSetted(1));
        $user = $user->setProfilePicture(env('APP_URL').$user->getProfilePicture());
        return $this->response->respond(['data'=>[
            'user'=>$user->toJson()
        ]]);
    }

    /**
     * @param UpdateProfileRequest $request
     * @return \App\Http\json
     */
    public function updateProfile(UpdateProfileRequest $request)
    {
        try{
            return $this->response->respond([
                    'data'=>[
                        'user'=>$this->usersRepo->update($request->updatedUser())->toJson()
                    ]
                ]);
        }catch (ValidationErrorException $ve){
            return $this->response->respondValidationFails([$ve->getMessage()]);
        }catch (\Exception $e){
            return $this->response->respondInternalServerError([$e->getMessage()]);
        }
    }

    public function updateWalkthroughStatus(UpdateWalkthroughStatusRequest $request)
    {
        try{
            $user = clone($request->user());
            return $this->response->respond(['data'=>[
                'user' => $this->usersRepo->update($user->setWalkthroughCompleted($request->input('status')))->toJson()
            ]]);
        }catch (ValidationErrorException $ve){
            return $this->response->respondValidationFails([$ve->getMessage()]);
        }catch (\Exception $e){
            return $this->response->respondInternalServerError([$e->getMessage()]);
        }

    }

    /**
     * @param ResetPasswordRequest $request
     * @return \App\Http\json
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        try{
            $this->usersRepo->update($request->user->setPassword(bcrypt($request->input('new_password'))));
            return $this->response->respond();
        }catch (ValidationErrorException $ve){
            return $this->response->respondValidationFails([$ve->getMessage()]);
        }catch (\Exception $e){
            return $this->response->respondInternalServerError([$e->getMessage()]);
        }
    }
}