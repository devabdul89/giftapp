<?php

namespace App\Http\Controllers\Auth;


use App\Exceptions\ValidationErrorException;
use App\Http\Controllers\ParentController;
use App\Http\Response;
use App\Libs\Auth\Auth;
use Repositories\UsersRepository;
use Models\User;
use Requests\FbLoginRequest;
use Requests\LoginRequest;

class AuthController extends ParentController
{

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
            if($existingUser){
                return $this->response->respond(['data'=>[
                    'user'=>Auth::login($existingUser)->toJson()
                ]]);
            }else{
                $this->usersRep->store($request->newUser());
                return $this->response->respond(['data'=>[
                    'user'=>$request->newUser()->toJson()
                ]]);
            }
        }catch (ValidationErrorException $e){
            return $this->response->respondValidationFails([$e->getMessage()]);
        }catch (\Exception $e){
            return $this->response->respondInternalServerError([$e->getMessage()]);
        }
    }

    public function login(LoginRequest $request)
    {
        return $this->response->respond(['data'=>[
            'user' => new User()
        ]]);
    }
}