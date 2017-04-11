<?php

namespace App\Http\Controllers;


use App\Exceptions\ValidationErrorException;
use App\Http\Response;
use Repositories\EventMembersRepository;
use Repositories\BillingRepository;
use Repositories\EventsRepository;
use Repositories\UsersRepository;
use Requests\AcceptEventInvitationRequest;
use Requests\CreateEventRequest;
use Requests\DeclineEventInvitationRequest;
use Requests\FetchEventInvitationsRequest;
use Requests\GetAllEventsRequest;
use Requests\GetEventDetailRequest;
use Requests\GetPublicEventsRequests;

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
     * @param GetPublicEventsRequests $request
     * @return \App\Http\json
     */
    public function getPublicEvents(GetPublicEventsRequests $request){
        try{
            return $this->response->respond([
                'data'=>[
                    'events'=>$this->eventsRepo->getPublicEvents($request->get('page'))
                ]
            ]);
        }catch(ValidationErrorException $ve){
            return $this->response->respondValidationFails([$ve->getMessage()]);
        }catch(\Exception $e){
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
                'date'=>$request->input('date'),
                'admin_id' => $request->user->getId(),
                'private' => ($request->input('private') != null) ? $request->input('private'):0
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

    /**
     * @param  AcceptEventInvitationRequest $request
     * @return \App\Http\json
     */
    public function acceptEventInvitation(AcceptEventInvitationRequest $request){

        try{
            $this->eventsRepo->acceptEvent($request->input('event_id'),$request->user->getId());
            return $this->response->respond([
                'data'=>[]
            ]);
        }catch(ValidationErrorException $ve){
            return $this->response->respondValidationFails([$ve->getMessage()]);
        }catch(\Exception $e){
            return $this->response->respondInternalServerError([$e->getMessage()]);
        }
    }


    /**
     * @param DeclineEventInvitationRequest $request
     * @return \App\Http\json
     */
    public function declineEventInvitation(DeclineEventInvitationRequest $request){
        try{
            $this->eventsRepo->declineEvent($request->input('event_id'),$request->user->getId());
            return $this->response->respond([
                'data'=>[]
            ]);
        }catch(ValidationErrorException $ve){
            return $this->response->respondValidationFails([$ve->getMessage()]);
        }catch(\Exception $e){
            return $this->response->respondInternalServerError([$e->getMessage()]);
        }
    }

   /**
    * @param GetEventDetailRequest $request
    * @return \App\Http\json
    */
   public function getEventDetail(GetEventDetailRequest $request){
       try{
           return $this->response->respond([
               'data'=>[
                    'event'=>$this->eventsRepo->getEventDetail($request->get('event_id'))
               ]
           ]);
       }catch(ValidationErrorException $ve){
           return $this->response->respondValidationFails([$ve->getMessage()]);
       }catch(\Exception $e){
           return $this->response->respondInternalServerError([$e->getMessage()]);
       }
   }


   /**
    * @param FetchEventInvitationsRequest $request
    * @return \App\Http\json
    */
   public function fetchEventInvitations(FetchEventInvitationsRequest $request){
       try{
           return $this->response->respond([
               'data'=>[
                   'invitations'=> $this->eventsRepo->fetchEventInvitations($request->get('event_id'))
               ]
           ]);
       }catch(ValidationErrorException $ve){
           return $this->response->respondValidationFails([$ve->getMessage()]);
       }catch(\Exception $e){
           return $this->response->respondInternalServerError([$e->getMessage()]);
       }
   }

}
