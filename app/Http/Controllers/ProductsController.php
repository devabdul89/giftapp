<?php

namespace App\Http\Controllers;

use App\Exceptions\ValidationErrorException;
use App\Http\Response;
use Repositories\ProductsRepository;
use Requests\CreateProductRequest;
use Requests\GetProductDetailsRequest;
use Requests\GetProductsByVendorRequest;
use Requests\GetProductsRequest;
use Requests\Request;
use Requests\SearchProductsByVendorRequest;
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
     * @param GetProductsByVendorRequest $request
     * @return \App\Http\json
     */
    public function getProductsByVendor(GetProductsByVendorRequest $request){
        try{
            $products = [];
            if($request->get('vendor') == 'in_app')
                $products = $this->productsRepo->searchInAppProducts(($request->get('keyword') == null)?"":$request->get('keyword'),$request->get('page'));
            else if($request->get('vendor') == 'amazon')
                $products = $this->productsRepo->amazonProducts($request->getConfigs(), $request->get('page'));
            else if($request->get('vendor') == 'bestbuy')
                $products = $this->productsRepo->getBestBuyProducts($request->getConfigs(), $request->get('page'));

            return $this->response->respond([
                'data'=>[
                    'products'=>$products
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
     * @param SearchProductsByVendorRequest $request
     * @return \App\Http\json
     */
    public function searchByVendor(SearchProductsByVendorRequest $request){
        try{
            $products = [];
            if($request->input('vendor') == 'in_app')
                $products = $this->productsRepo->searchInAppProducts($request->get('keyword'),$request->get('page'));
            else if($request->input('vendor') == 'amazon')
                $products = $this->productsRepo->searchAmazon($request->get('keyword'),$request->get('page'));

            return $this->response->respond([
                'data'=>[
                    'products'=>$products,
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
            $item = null;
            if($request->get('vendor') == 'in_app'){
                $item = $this->productsRepo->inAppProductDetail($request->get('item_id'));
            }else if($request->get('vendor') == 'bestbuy'){
                $item = $this->productsRepo->bestBuyItemLookup($request->get('item_id'));
            }else if($request->get('vendor') == 'amazon'){
                $item = $this->productsRepo->amazonProductLookup($request->get('item_id'));
            }
            return $this->response->respond([
                'data'=>[
                    'item'=>$item
                ]
            ]);
        }catch(ValidationErrorException $ve){
            return $this->response->respondValidationFails([$ve->getMessage()]);
        }catch(\Exception $e){
            return $this->response->respondInternalServerError([$e->getMessage()]);
        }
    }


    /**
     * @return \App\Http\json
     */
    public function getBestBuyProducts(){
        try{
            return $this->response->respond([
                'data'=>[
                    'products'=>json_decode($this->productsRepo->getBestBuyProducts())
                ]
            ]);
        }catch(ValidationErrorException $ve){
            return $this->response->respondValidationFails([$ve->getMessage()]);
        }catch(\Exception $e){
            return $this->response->respondInternalServerError([$e->getMessage()]);
        }
    }
}