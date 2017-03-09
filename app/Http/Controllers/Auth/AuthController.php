<?php

namespace App\Http\Controllers\Auth;


use App\Exceptions\ValidationErrorException;
use App\Http\Controllers\ParentController;
use App\Http\Response;
use App\Libs\Auth\Auth;
use Repositories\BillingRepository;
use Repositories\UsersRepository;
use Requests\FbLoginRequest;
use Requests\ForgotPasswordRequest;
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
     * @var BillingRepository|null
     */
    public $billingCardsRepo = null;
    /**
     * @var Response|null
     */
    public $response = null;

    public function __construct(UsersRepository $usersRepository)
    {
        $this->usersRep = $usersRepository;
        $this->response = new Response();
        $this->billingCardsRepo = new BillingRepository();
    }

    public function fblogin(FbLoginRequest $request)
    {
        try{
            $existingUser = $this->usersRep->findByEmail($request->newUser()->getEmail());
            $loggedInUser = Auth::login(($existingUser)?$existingUser:$this->usersRep->store($request->newUser()));
            $billingCard = $this->billingCardsRepo->findByUserId($loggedInUser->getId());
            return $this->response->respond([
                'data'=>[
                    'user'=>$loggedInUser->toJson(),
                    'billing_card' => ($billingCard == null)?$billingCard:$billingCard->toJson()
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
            }
            $user = Auth::login($this->usersRep->store($user));

            $billingCard = $this->billingCardsRepo->findByUserId($user->getId());
            if($user->getImageSetted()){
                $user->setProfilePicture(env('APP_URL').$user->getProfilePicture());
            }
            return $this->response->respond([
                'data'=>[
                    'user' => $user->toJson(),
                    'billing_card' => ($billingCard == null)?$billingCard:$billingCard->toJson()
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
            if(Auth::attempt(['email'=>$request->input('email'), 'password'=> $request->input('password')])){
                $loggedInUser = Auth::login($this->usersRep->findByEmail($request->input('email')));
                $billingCard = $this->billingCardsRepo->findByUserId($loggedInUser->getId());
                return $this->response->respond([
                    'data'=>[
                        'user'=>$loggedInUser->toJson(),
                        'billing_card' => ($billingCard == null)?null:$billingCard->toJson()
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

    /**
     * @param ForgotPasswordRequest $request
     * @return \App\Http\json
     */
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        try{
            return $this->response->respond([
                    'data'=>[]
                ]);
        }catch (ValidationErrorException $ve){
            return $this->response->respondValidationFails([$ve->getMessage()]);
        }catch (\Exception $e){
            return $this->response->respondInternalServerError([$e->getMessage()]);
        }
    }

}