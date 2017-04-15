<?php

namespace App\Http\Controllers;


use App\Exceptions\ValidationErrorException;
use App\Http\Response;
use Repositories\BillingRepository;
use Repositories\ProductsRepository;
use Repositories\UsersRepository;
use Repositories\WishlistRepository;
use Requests\AddBillingCardRequest;
use Requests\AddToWishlistRequest;
use Requests\GetWishlistRequest;
use Requests\RemoveFromWishlistRequest;

class WishlistController extends ParentController
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
     * @var WishlistRepository|null
     * */
    public $wishlistRepo = null;
    /**
     * @var null|BillingRepository
     */
    public $billingRepo = null;
    public function __construct(UsersRepository $usersRepository)
    {
        $this->usersRepo = $usersRepository;
        $this->response = new Response();
        $this->wishlistRepo = new WishlistRepository();
    }

    /**
         * @param GetWishlistRequest $request
         * @return \App\Http\json
         */
        public function get(GetWishlistRequest $request){
            try{
                $wishlist = $this->wishlistRepo->getByUser($request->user->getId());
                return $this->response->respond([
                    'data'=>[
                        'wishlist'=>$wishlist
                    ]
                ]);
            }catch(ValidationErrorException $ve){
                return $this->response->respondValidationFails([$ve->getMessage()]);
            }catch(\Exception $e){
                return $this->response->respondInternalServerError([$e->getMessage()]);
            }
        }
    
    
        /**
         * @param RemoveFromWishlistRequest $request
         * @return \App\Http\json
         */
        public function remove(RemoveFromWishlistRequest $request){
            try{
                $this->wishlistRepo->removeProduct($request->user->getId(), $request->input('product_id'));
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
         * @param AddToWishlistRequest $request
         * @return \App\Http\json
         */
        public function add(AddToWishlistRequest $request){
            try{
                $this->wishlistRepo->add([
                    'user_id'=>$request->user->getId(),
                    'product_id'=>$request->input('product_id'),
                    'product_vendor'=>$request->input('product_vendor')
                ]);
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