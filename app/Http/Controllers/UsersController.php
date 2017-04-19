<?php

namespace App\Http\Controllers;

use App\Exceptions\ValidationErrorException;
use App\Http\Response;
use Repositories\UsersRepository;
use Repositories\WishlistRepository;
use Requests\AcceptFriendRequest;
use Requests\AddAsFriendRequest;
use Requests\GetUserFriendsRequest;
use Requests\GetUsersRequest;
use Requests\RejectFriendRequest;
use Requests\ResetPasswordRequest;
use Requests\SearchFriendsRequest;
use Requests\UpdateProfilePictureRequest;
use Requests\UpdateProfileRequest;
use Requests\UpdateWalkthroughStatusRequest;
use Requests\UserProfileRequest;
use Traits\ImageHelper;
use Traits\ModelToJson;

class UsersController extends ParentController
{
    use ImageHelper, ModelToJson;

    public $usersRepo = null;
    public $response = null;
    public function __construct(UsersRepository $usersRepository)
    {
        $this->usersRepo = $usersRepository;
        $this->response = new Response();
    }


    /**
     * @param GetUserFriendsRequest $request
     * @return \App\Http\json
     */
    public function friends(GetUserFriendsRequest $request){
        try{
            return $this->response->respond([
                'data'=>[
                    'friends'=>$this->transformFriendsResponse($this->usersRepo->friends($request->user->getId()))
                ]
            ]);
        }catch(ValidationErrorException $ve){
            return $this->response->respondValidationFails([$ve->getMessage()]);
        }catch(\Exception $e){
            return $this->response->respondInternalServerError([$e->getMessage()]);
        }
    }


    /**
     * @param SearchFriendsRequest $request
     * @return \App\Http\json
     */
    public function searchFriends(SearchFriendsRequest $request){
        try{
            return $this->response->respond([
                'data'=>[
                    'friends'=>$this->transformFriendsResponse($this->usersRepo->searchFriends($request->user->getId(),$request->input('keyword')))
                ]
            ]);
        }catch(ValidationErrorException $ve){
            return $this->response->respondValidationFails([$ve->getMessage()]);
        }catch(\Exception $e){
            return $this->response->respondInternalServerError([$e->getMessage()]);
        }
    }

    private function transformFriendsResponse($friends){
        foreach ($friends as &$friend){
            $friend->friend = $friend->friend->toJson();
        }
        return $friends;
    }

    public function updateProfilePicture(UpdateProfilePictureRequest $request)
    {
        $user = clone($request->user);
        $user = $this->usersRepo->update($user->setProfilePicture($this->saveProfilePicture($request->file('profile_picture'))));
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

    public function getUsers(GetUsersRequest $request)
    {
        try{
            return $this->response->respond([
                'data' => $this->modelsToJson($this->usersRepo->getAllUsers())
            ]);
        }catch (ValidationErrorException $ve){
            return $this->response->respondValidationFails([$ve->getMessage()]);
        }catch (\Exception $e){
            return $this->response->respondInternalServerError([$e->getMessage()]);
        }
    }


    /**
     * @param AddAsFriendRequest $request
     * @return \App\Http\json
     */
    public function addFriend(AddAsFriendRequest $request){
        try{
            if($request->input('fb_id')){
                $this->usersRepo->addFriendByFbId($request->user->getId(),$request->input('fb_id'));
            }
            if($request->input('email')){
                $this->usersRepo->addFriendByEmail($request->user->getId(),$request->input('email'));
            }
            return $this->response->respond([
                'data'=>[
                    'friends'=>$this->transformFriendsResponse($this->usersRepo->friends($request->user->getId()))
                ]
            ]);
        }catch(ValidationErrorException $ve){
            return $this->response->respondValidationFails([$ve->getMessage()]);
        }catch(\Exception $e){
            return $this->response->respondInternalServerError([$e->getMessage()]);
        }
    }


    /**
     * @param AcceptFriendRequest $request
     * @return \App\Http\json
     */
    public function acceptFriend(AcceptFriendRequest $request){
        try{
            $this->usersRepo->acceptFriend($request->input('request_id'));
            return $this->response->respond([
                'data'=> $this->transformFriendsResponse($this->usersRepo->friends($request->user->getId()))
            ]);
        }catch(ValidationErrorException $ve){
            return $this->response->respondValidationFails([$ve->getMessage()]);
        }catch(\Exception $e){
            return $this->response->respondInternalServerError([$e->getMessage()]);
        }
    }


    /**
     * @param RejectFriendRequest $request
     * @return \App\Http\json
     */
    public function rejectFriend(RejectFriendRequest $request){
        try{
            $this->usersRepo->rejectFriend($request->input('request_id'));
            return $this->response->respond([
                'data'=>$this->transformFriendsResponse($this->usersRepo->friends($request->user->getId()))
            ]);
        }catch(ValidationErrorException $ve){
            return $this->response->respondValidationFails([$ve->getMessage()]);
        }catch(\Exception $e){
            return $this->response->respondInternalServerError([$e->getMessage()]);
        }
    }

    /**
     * @param UserProfileRequest $request
     * @return \App\Http\json
     */
    public function userProfile(UserProfileRequest $request){
        try{
            return $this->response->respond([
                'data'=>[
                    'profile'=>$this->usersRepo->findById($request->input('user_id'))->toJson(),
                    'wishlist'=>(new WishlistRepository())->getByUser($request->input('user_id'))
                ]
            ]);
        }catch(ValidationErrorException $ve){
            return $this->response->respondValidationFails([$ve->getMessage()]);
        }catch(\Exception $e){
            return $this->response->respondInternalServerError([$e->getMessage()]);
        }
    }
}