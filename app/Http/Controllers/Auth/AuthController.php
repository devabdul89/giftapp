<?php

namespace App\Http\Controllers\Auth;


use App\Exceptions\ValidationErrorException;
use App\Http\Controllers\ParentController;
use App\Http\Response;
use App\Libs\Auth\Auth;
use Illuminate\Support\Facades\Mail;
use Repositories\BillingRepository;
use Repositories\UsersRepository;
use Requests\FbLoginRequest;
use Requests\ForgotPasswordRequest;
use Requests\LoginRequest;
use Requests\LogoutRequest;
use Requests\RegisterRequest;
use Requests\UpdateSessionRequest;
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
        try
        {
            $user = $request->newUser();
            if($request->file('profile_picture') != null){
                $user->setProfilePicture($this->saveProfilePicture($request->file('profile_picture')));
            }
            $user = Auth::login($this->usersRep->store($user));
            $billingCard = $this->billingCardsRepo->findByUserId($user->getId());
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
                // update device id and type
                $this->usersRep->updateWhere(['email'=>$request->input('email')],['device_id'=>$request->input('device_id'), 'device_type'=>$request->input('device_type')]);
                $loggedInUser = $loggedInUser->setDeviceId($request->input('device_id'))->setDeviceType($request->input('device_type'));
                //fetching billing card information
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
            $newPassword = substr(md5($request->input('email')), 0, 5);
            Mail::send('forgot_pass', ['password'=>$newPassword], function ($m) use ($request) {
                $m->from(env('MAIL_USERNAME'), 'Group Gift');
                $m->to($request->input('email'))->subject('Forget Password');
            });
            $this->usersRep->updatePassword($request->user->getId(), bcrypt($newPassword));
            return $this->response->respond([
                    'data'=>[]
                ]);
        }catch (ValidationErrorException $ve){
            return $this->response->respondValidationFails([$ve->getMessage()]);
        }catch (\Exception $e){
            return $this->response->respondInternalServerError([$e->getMessage()]);
        }
    }

    /**
     * @param UpdateSessionRequest $request
     * @return \App\Http\json
     */
    public function updateToken(UpdateSessionRequest $request){
        try{
            $this->usersRep->updateWhere(['id'=>$request->user->getId()],['session_token'=>$request->input('session_id')]);
            $loggedInUser = $this->usersRep->findByToken($request->input('session_id'));
            $billingCard = $this->billingCardsRepo->findByUserId($loggedInUser->getId());
            return $this->response->respond([
                'data'=>[
                    'user'=>$loggedInUser->toJson(),
                    'billing_card' => ($billingCard == null)?null:$billingCard->toJson()
                ],
                'access_token'=>$loggedInUser->getSessionToken()
            ]);
        }catch(ValidationErrorException $ve){
            return $this->response->respondValidationFails([$ve->getMessage()]);
        }catch(\Exception $e){
            return $this->response->respondInternalServerError([$e->getMessage()]);
        }
    }

}