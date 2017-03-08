<?php

namespace App\Http\Controllers\Auth;


use App\Exceptions\ValidationErrorException;
use App\Http\Controllers\ParentController;
use App\Http\Response;
use App\Libs\Auth\Auth;
use Repositories\UsersRepository;
use Requests\FbLoginRequest;
use Requests\LoginRequest;
use Requests\LogoutRequest;
use Requests\RegisterRequest;
use Traits\ImageHelper;

class AuthController extends ParentController
{
    use ImageHelper;
    /**
     * @var UsersRepository|null
     */
    public $usersRep = null;
    /**
     * @var Response|null
     */
    public $response = null;

    public function __construct(UsersRepository $usersRepository)
    {
        $this->usersRep = $usersRepository;
        $this->response = new Response();
    }

    public function fblogin(FbLoginRequest $request)
    {
        try{
            $existingUser = $this->usersRep->findByEmail($request->newUser()->getEmail());
            $loggedInUser = Auth::login(($existingUser)?$existingUser:$this->usersRep->store($request->newUser()));
            return $this->response->respond([
                'data'=>[
                    'user'=>$loggedInUser->toJson()
                ],
                'access_token' => $loggedInUser->getSessionToken()
            ]);
        }catch (ValidationErrorException $e){
            return $this->response->respondValidationFails([$e->getMessage()]);
        }catch (\Exception $e){
            return $this->response->respondInternalServerError([$e->getMessage()]);
        }
    }

    public function register(RegisterRequest $request)
    {
        try{
            $user = $request->newUser();
            if($request->file('profile_picture') != null){
                $user->setProfilePicture($this->saveProfilePicture($request->file('profile_picture')));
                $user->setImageSetted(true);
            }
            $user = Auth::login($this->usersRep->store($user));

            //setting profile picture with base path
            if($user->getImageSetted()){
                $user->setProfilePicture(env('APP_URL').$user->getProfilePicture());
            }
            return $this->response->respond([
                'data'=>[
                    'user' => $user->toJson()
                ],
                'access_token' => $user->getSessionToken()
            ]);
        }catch(ValidationErrorException $ve){
            return $this->response->respondValidationFails([$ve->getMessage()]);
        }catch (\Exception $e){
            return $this->response->respondInternalServerError($e->getMessage());
        }
    }

    public function login(LoginRequest $request)
    {
        try{
            if(Auth::attempt(['email'=>$request->input('email'), 'password'=>$request->input('password')])){
                $loggedInUser = Auth::login($this->usersRep->findByEmail($request->input('email')));
                return $this->response->respond([
                    'data'=>[
                        'user'=>$loggedInUser->toJson()
                    ],
                    'access_token'=>$loggedInUser->getSessionToken()
                ]);
            }else{
                return $this->response->respondInvalidCredentials();
            }
        }catch (ValidationErrorException $e){
            return $this->response->respondValidationFails([$e->getMessage()]);
        }catch (\Exception $e){
            return $this->response->respondInternalServerError([$e->getMessage()]);
        }
    }

    public function logout(LogoutRequest $request)
    {
        try{
            return $this->response->respond(['data'=>[
                'user' => $this->usersRep->update($request->user()->setSessionToken(null))->toJson()
            ]]);
        }catch (ValidationErrorException $e){
            return $this->response->respondValidationFails([$e->getMessage()]);
        }catch (\Exception $e){
            return $this->response->respondInternalServerError([$e->getMessage()]);
        }
    }

}