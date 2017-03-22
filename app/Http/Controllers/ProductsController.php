<?php

namespace App\Http\Controllers;

use App\Exceptions\ValidationErrorException;
use App\Http\Response;
use Repositories\ProductsRepository;
use Requests\GetProductsRequest;
use Requests\Request;

class ProductsController extends ParentController
{
    public $productsRepo = null;
    public $response = null;
    public function __construct(ProductsRepository $productsRepository)
    {
        $this->productsRepo = $productsRepository;
        $this->response = new Response();
    }



    /**
     * @param GetProductsRequest $request
     * @return \App\Http\json
     */
    public function getProducts(GetProductsRequest $request){
        try{
            return $this->response->respond([
                'data'=>[
                    'products'=>$this->productsRepo->amazonProducts()
                ]
            ]);
        }catch(ValidationErrorException $ve){
            return $this->response->respondValidationFails([$ve->getMessage()]);
        }catch(\Exception $e){
            return $this->response->respondInternalServerError([$e->getMessage()]);
        }
    }
}