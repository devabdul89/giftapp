<?php

namespace App\Http\Controllers;


use Requests\FooRequest;
use App\Http\Response;
use App\Repositories\UsersRepository;
use App\Traits\Transformers\UsersControllerTransformer;

class UsersController extends ParentController
{
    use UsersControllerTransformer;

    public $users = null;
    public $checkIns = null;
    public $response = null;
    public $likes = null;
    public function __construct(UsersRepository $users)
    {
        $this->users = $users;
        $this->response = new Response();
    }
}