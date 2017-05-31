<?php
/**
 * Created by PhpStorm.
 * user: nomantufail
 * Date: 10/10/2016
 * Time: 10:13 AM
 */

namespace Repositories;


use Illuminate\Support\Facades\DB;
use LaraModels\Product;
use Nathanmac\Utilities\Parser\Facades\Parser;

class ProductsRepository extends Repository
{

    public function __construct()
    {
        $this->setModel(new Product());
    }


    public function create($product){
        return $this->getModel()->create($product);
    }

    public function searchAmazon($config,$page=1){
        if($page == null){
            $page = 1;
        }
        return $this->amazonProducts($config,$page);
    }

    public function searchInAppProducts($keyword='',$page){
        return $this->getModel()->where('title','like','%'.$keyword.'%')->with('images')->paginate()->all();
    }

    public function generateAmazonSearchUrl($config = [],$page =1){
        $aws_access_key_id = "AKIAJCJIMH2OSDJOMIRQ";
        $aws_secret_key = env('APA_SECRET');
        $endpoint = env('APA_ENDPOINT');
        $uri = "/onca/xml";
        $params = array(
            "Service" => "AWSECommerceService",
            "Operation" => "ItemSearch",
            "AWSAccessKeyId" => env('APA_KEY'),
            "AssociateTag" => env('APA_ASSOCIATE_TAG'),
            "SearchIndex" => (isset($config['category']))?$config['category']:"All",
            "ResponseGroup" => "Images,ItemAttributes,Offers",
            "Keywords" => (isset($config['keyword']))?$config['keyword']:"shoes",
            "ItemPage" => $page
        );
        if(isset($config['sort'])){
            $params['Sort'] = $config['sort'];
        }
        if (!isset($params["Timestamp"])) {
            $params["Timestamp"] = gmdate('Y-m-d\TH:i:s\Z');
        }

        ksort($params);
        $pairs = array();
        foreach ($params as $key => $value) {
            array_push($pairs, rawurlencode($key)."=".rawurlencode($value));
        }
        $canonical_query_string = join("&", $pairs);
        $string_to_sign = "GET\n".$endpoint."\n".$uri."\n".$canonical_query_string;
        $signature = base64_encode(hash_hmac("sha256", $string_to_sign, $aws_secret_key, true));
        return 'http://'.$endpoint.$uri.'?'.$canonical_query_string.'&Signature='.rawurlencode($signature);
    }
    public function curl($url){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public function amazonProducts($configs=['keyword'=>'shoes'], $page = 1){
        $xml = Parser::xml($this->curl($this->generateAmazonSearchUrl($configs,$page)));
        return $xml['Items']['Item'];
    }

    public function generateAmazonItemLookupUrl($itemId){
        $aws_access_key_id = "AKIAJCJIMH2OSDJOMIRQ";
        $aws_secret_key = env('APA_SECRET');
        $endpoint = env('APA_ENDPOINT');
        $uri = "/onca/xml";
        $params = array(
            "Service" => "AWSECommerceService",
            "Operation" => "ItemLookup",
            "AWSAccessKeyId" => env('APA_KEY'),
            "AssociateTag" => env('APA_ASSOCIATE_TAG'),
            "ItemId" => $itemId,
            "IdType" => "ASIN",
            "ResponseGroup" => "Images,ItemAttributes,Offers"
        );
        if (!isset($params["Timestamp"])) {
            $params["Timestamp"] = gmdate('Y-m-d\TH:i:s\Z');
        }
        ksort($params);
        $pairs = array();
        foreach ($params as $key => $value) {
            array_push($pairs, rawurlencode($key)."=".rawurlencode($value));
        }
        $canonical_query_string = join("&", $pairs);
        $string_to_sign = "GET\n".$endpoint."\n".$uri."\n".$canonical_query_string;
        $signature = base64_encode(hash_hmac("sha256", $string_to_sign, $aws_secret_key, true));
        return 'http://'.$endpoint.$uri.'?'.$canonical_query_string.'&Signature='.rawurlencode($signature);
    }

    public function amazonProductLookup($ItemId){
        $xml = Parser::xml($this->curl($this->generateAmazonItemLookupUrl($ItemId)));
        return $xml['Items']['Item'];
    }

    public function inAppProductDetail($itemId){
        return $this->getModel()->with('images')->find($itemId);
    }

    public function inAppProducts($page){
        return $this->getModel()->paginate(10);
    }

    public function bestBuyItemLookup($itemId){
        return json_decode($this->curl("https://api.bestbuy.com/v1/products/".$itemId.".json?&format=json&apiKey=".env('BEST_BUY')));
    }

    public function getBestBuyProducts($config=[], $page = 1){
        $sort = "";
        if($page == null)
            $page = 1;
        $params = "search=gift";
        if(isset($config['keyword'])){
            $params="search=".$config['keyword'];
        }
        if(isset($config['category'])){
            $params.="&type=".$config['category'];
        }
        if(isset($config['sort'])){
            $sort = "&sort=".$config['sort'];
        }
        return json_decode($this->curl("https://api.bestbuy.com/v1/products(".$params.")?format=json&page=".$page."&apiKey=".env('BEST_BUY').$sort));
    }
    

}