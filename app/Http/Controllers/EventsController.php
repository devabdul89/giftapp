<?php

namespace App\Http\Controllers;


use App\Exceptions\ValidationErrorException;
use App\Http\Response;
use Repositories\EventMembersRepository;
use Repositories\BillingRepository;
use Repositories\EventsRepository;
use Repositories\UsersRepository;
use Requests\AcceptEventInvitationRequest;
use Requests\CancelEventRequest;
use Requests\CreateEventRequest;
use Requests\DeclineEventInvitationRequest;
use Requests\FetchEventInvitationsRequest;
use Requests\GetAllEventsRequest;
use Requests\GetEventDetailRequest;
use Requests\GetMyEventsRequest;
use Requests\GetPublicEventsRequests;
use Requests\JoinEventRequest;
use Requests\UpdateEventRequest;

class EventsController extends ParentController
{

    /**
     * @var null|EventsRepository
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
     * @param UpdateEventRequest $request
     * @return \App\Http\json
     */
    public function update(UpdateEventRequest $request){
        try{
            $this->eventsRepo->updateWhere(['id'=>$request->input('event_id')],$request->updateableData());
            return $this->response->respond([
                'data'=>[

                ]
            ]);
        }catch(ValidationErrorException $ve){
            return $this->response->respondValidationFails([$ve->getMessage()]);
        }catch(\Exception $e){
            return $this->response->respondInternalServerError([$e->getMessage()]);
        }
    }

    /**
     * @param GetMyEventsRequest $request
     * @return \App\Http\json
     */
    public function getMyEvents(GetMyEventsRequest $request)
    {
        try{
            return $this->response->respond([
                'data'=>[
                    'events'=>$this->eventsRepo->getMyEvents($request->user->getId())
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
                    'events'=>$this->amIinvited($this->eventsRepo->getPublicEvents($request->get('page')),$request->user->getId())
                ]
            ]);
        }catch(ValidationErrorException $ve){
            return $this->response->respondValidationFails([$ve->getMessage()]);
        }catch(\Exception $e){
            return $this->response->respondInternalServerError([$e->getMessage()]);
        }
    }

    private function amIinvited($events, $userId){
        $updatedEvents = $events->all();
        foreach($updatedEvents as &$event){
            $event->pivot = null;
            foreach ($event->members as $member){
                if($member->pivot->user_id == $userId){
                    $event->pivot = $member->pivot;
                }
            }
        }
        $events->events = $updatedEvents;
        return $events;
    }
    
    /**
     * @param JoinEventRequest $request
     * @return \App\Http\json
     */
    public function joinEvent(JoinEventRequest $request){
        try{
            $this->eventsRepo->joinEvent($request->input('event_id'), $request->user->getId());
            return $this->response->respond([
                'data'=>[

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
                'private' => ($request->input('private') != null) ? $request->input('private'):0,
                'product_id' => $request->input('product_id'),
                'price'=>$request->input('price'),
                'shipping_address' => $request->input('shipping_address'),
                'currency' => $request->input('currency'),
                'lat_lng'=>$request->input('lat_lng')
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
    
    
    /**
     * @param InviteMember $request
     * @return \App\Http\json
     */
    public function inviteMemberRequest(InviteMember $request){
        try{
            
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
   
   
   /**
    * @param CancelEventRequest $request
    * @return \App\Http\json
    */
   public function cancel(CancelEventRequest $request){
       try{
           $this->eventsRepo->cancelEvent($request->input('event_id'));
            return $this->response->respond([
                'data'=>[
                    'events'=>$this->eventsRepo->getMyEvents($request->user->getId())
                ]
            ]);
       }catch(ValidationErrorException $ve){
           return $this->response->respondValidationFails([$ve->getMessage()]);
       }catch(\Exception $e){
           return $this->response->respondInternalServerError([$e->getMessage()]);
       }
   }

}
