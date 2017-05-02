<?php

namespace App\Http\Controllers;


use App\Exceptions\ValidationErrorException;
use App\Http\Response;
use Davibennun\LaravelPushNotification\Facades\PushNotification;
use Repositories\EventMembersRepository;
use Repositories\BillingRepository;
use Repositories\EventsRepository;
use Repositories\NotificationsRepository;
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

class EventsControllerTemp extends ParentController
{
    /**
         * @param  $request
         * @return \App\Http\json
         */
        public function get( $request){
            try{
                
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
         * @param  $request
         * @return \App\Http\json
         */
        public function remove( $request){
            try{
                
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
         * @param  $request
         * @return \App\Http\json
         */
        public function add( $request){
            try{
                
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
         * @param  $request
         * @return \App\Http\json
         */
        public function update( $request){
            try{
                
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
}
