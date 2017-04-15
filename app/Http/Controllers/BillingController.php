<?php

namespace App\Http\Controllers;


use App\Exceptions\ValidationErrorException;
use App\Http\Response;
use Repositories\BillingRepository;
use Repositories\UsersRepository;
use Requests\AddBillingCardRequest;

class BillingController extends ParentController
{

    /**
     * @var null|UsersRepository
     */
    public $usersRepo = null;
    /**
     * @var Response|null
     */
    public $response = null;

    /**
     * @var null|BillingRepository
     */
    public $billingRepo = null;
    public function __construct(UsersRepository $usersRepository)
    {
        $this->usersRepo = $usersRepository;
        $this->response = new Response();
        $this->billingRepo = new BillingRepository();
    }

    /**
     * @param AddBillingCardRequest $request
     * @return \App\Http\json
     */
    public function addBillingCard(AddBillingCardRequest $request)
    {
        try{
            return $this->response->respond([
                'data'=>[
                    'card'=>$this->billingRepo->storeCardInformation($request->user->getId(), $request->card())->toJson()
                ]
            ]);
        }catch (ValidationErrorException $e){
            return $this->response->respondValidationFails([$e->getMessage()]);
        }catch (\Exception $e){
            return $this->response->respondInternalServerError([$e->getMessage()]);
        }
    }
}