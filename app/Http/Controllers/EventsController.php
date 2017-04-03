<?php

namespace App\Http\Controllers;


use App\Exceptions\ValidationErrorException;
use App\Http\Response;
use Repositories\EventMembersRepository;
use Repositories\BillingRepository;
use Repositories\EventsRepository;
use Repositories\UsersRepository;
use Requests\CreateEventRequest;
use Requests\GetAllEventsRequest;

class EventsController extends ParentController
{

    /**
     * @var null|UsersRepository
     */
    public $eventsRepo = null;
    /**
     * @var Response|null
     */
    public $response = null;

    public $membersRepo = null;

    /**
     * @var null|BillingRepository
     */
    public $billingRepo = null;
    public function __construct(EventsRepository $eventsRepository)
    {
        $this->eventsRepo = $eventsRepository;
        $this->response = new Response();
        $this->membersRepo = new EventMembersRepository();
    }

    /**
     * @param GetAllEventsRequest $request
     * @return \App\Http\json
     */
    public function getAllEvents(GetAllEventsRequest $request)
    {
        try{
            return $this->response->respond([
                'data'=>[
                    'events'=>$this->eventsRepo->all()
                ]
            ]);
        }catch (ValidationErrorException $e){
            return $this->response->respondValidationFails([$e->getMessage()]);
        }catch (\Exception $e){
            return $this->response->respondInternalServerError([$e->getMessage()]);
        }
    }


    /**
     * @param CreateEventRequest $request
     * @return \App\Http\json
     */
    public function create(CreateEventRequest $request){
        try{
            $event = $this->eventsRepo->create([
                'title'=>$request->input('title'),
                'description'=>$request->input('description'),
                'date'=>$request->input('date')
            ]);
            $this->inviteMembers($event->id,[$request->user->getId()]);
            return $this->response->respond([
                'data'=>[]
            ]);
        }catch(ValidationErrorException $ve){
            return $this->response->respondValidationFails([$ve->getMessage()]);
        }catch(\Exception $e){
            return $this->response->respondInternalServerError([$e->getMessage()]);
        }
    }

    public function inviteMembers($eventId,$userIds){
        $this->membersRepo->inviteAll($eventId,$userIds);
    }
}