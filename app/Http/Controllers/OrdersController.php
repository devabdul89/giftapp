<?php

namespace App\Http\Controllers;


use App\Exceptions\ValidationErrorException;
use App\Http\Response;
use Davibennun\LaravelPushNotification\Facades\PushNotification;
use Repositories\EventMembersRepository;
use Repositories\BillingRepository;
use Repositories\EventsRepository;
use Repositories\OrdersRepository;
use Repositories\UsersRepository;
use Requests\AcceptEventInvitationRequest;
use Requests\CancelEventMemberRequest;
use Requests\CancelEventRequest;
use Requests\CreateEventRequest;
use Requests\DeclineEventInvitationRequest;
use Requests\FetchEventInvitationsRequest;
use Requests\GetAllEventsRequest;
use Requests\GetEventDetailRequest;
use Requests\GetMyEventsRequest;
use Requests\GetPublicEventsRequests;
use Requests\GetUserCompletedOrders;
use Requests\InviteMemberRequest;
use Requests\JoinEventRequest;
use Requests\UpdateEventRequest;

class OrdersController extends ParentController
{

    /**
     * @var null|OrdersRepository
     */
    public $ordersRepo = null;
    /**
     * @var Response|null
     */
    public $response = null;

    public function __construct(OrdersRepository $ordersRepository)
    {
        $this->ordersRepo = $ordersRepository;
        $this->response = new Response();
    }


    /**
     * @param GetUserCompletedOrders $request
     * @return \App\Http\json
     */
    public function userCompletedOrders(GetUserCompletedOrders $request){
        try{
            return $this->response->respond([
                'data'=>[
                    'orders' => $this->ordersRepo->getUserCompletedOrders()
                ]
            ]);
        }catch(ValidationErrorException $ve){
            return $this->response->respondValidationFails([$ve->getMessage()]);
        }catch(\Exception $e){
            return $this->response->respondInternalServerError([$e->getMessage()]);
        }
    }

}
