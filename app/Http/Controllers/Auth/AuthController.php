<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\ParentController;
use App\Http\Response;
use App\Libs\Auth\Auth;
use App\Repositories\UsersRepository;
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
            $existingUser = $this->usersRep->findByEmail($request->user()->getEmail());
            if($existingUser){
                return $this->response->respond(['data'=>[
                    'user'=>Auth::login($existingUser)
                ]]);
            }else{
                $this->usersRep->store($request->user());
                return $this->response->respond(['data'=>[
                    'user'=>$request->user()
                ]]);
            }
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