<?php

namespace App\Http\Controllers;

use App\Exceptions\ValidationErrorException;
use App\Http\Response;
use Repositories\ProductsRepository;
use Requests\CreateProductRequest;
use Requests\GetProductDetailsRequest;
use Requests\GetProductsRequest;
use Requests\Request;
use Requests\SearchProductsRequest;

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
     * @param CreateProductRequest $request
     * @return \App\Http\json
     */
    public function create(CreateProductRequest $request){
        try{
            $this->productsRepo->create($request->getProductInfo());
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

    /**
     * @param SearchProductsRequest $request
     * @return \App\Http\json
     */
    public function searchProducts(SearchProductsRequest $request){
        try{
            return $this->response->respond([
                'data'=>[
                    'products'=>[
                        'amazon'=>$this->productsRepo->searchAmazon($request->get('keyword'),$request->get('page')),
                        'in_app'=>$this->productsRepo->searchInAppProducts($request->get('keyword'),$request->get('page'))
                    ],
                ]
            ]);
        }catch(ValidationErrorException $ve){
            return $this->response->respondValidationFails([$ve->getMessage()]);
        }catch(\Exception $e){
            return $this->response->respondInternalServerError([$e->getMessage()]);
        }
    }


    /**
     * @param GetProductDetailsRequest $request
     * @return \App\Http\json
     */
    public function productDetail(GetProductDetailsRequest $request){
        try{
            return $this->response->respond([
                'data'=>[
                    'item'=>($request->get('vendor') == 'in_app')?$this->productsRepo->inAppProductDetail($request->get('item_id')):$this->productsRepo->amazonProductLookup($request->get('item_id'))
                ]
            ]);
        }catch(ValidationErrorException $ve){
            return $this->response->respondValidationFails([$ve->getMessage()]);
        }catch(\Exception $e){
            return $this->response->respondInternalServerError([$e->getMessage()]);
        }
    }
}