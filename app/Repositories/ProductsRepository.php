<?php
/**
 * Created by PhpStorm.
 * user: nomantufail
 * Date: 10/10/2016
 * Time: 10:13 AM
 */

namespace Repositories;


use Illuminate\Support\Facades\DB;
use Nathanmac\Utilities\Parser\Facades\Parser;

class ProductsRepository extends Repository
{
    public function __construct()
    {

    }

    public function amazonProducts(){
        // Your AWS Access Key ID, as taken from the AWS Your Account page
        $aws_access_key_id = "AKIAJCJIMH2OSDJOMIRQ";

// Your AWS Secret Key corresponding to the above ID, as taken from the AWS Your Account page
        $aws_secret_key = env('APA_SECRET');

// The region you are interested in
        $endpoint = env('APA_ENDPOINT');

        $uri = "/onca/xml";

        $params = array(
            "Service" => "AWSECommerceService",
            "Operation" => "ItemSearch",
            "AWSAccessKeyId" => "AKIAJCJIMH2OSDJOMIRQ",
            "AssociateTag" => "zeenomlabs-21",
            "SearchIndex" => "Shoes",
            "ResponseGroup" => "Images,ItemAttributes,Offers",
            "Keywords" => "shoe",
            "Brand" => "nike"
        );

// Set current timestamp if not set
        if (!isset($params["Timestamp"])) {
            $params["Timestamp"] = gmdate('Y-m-d\TH:i:s\Z');
        }

// Sort the parameters by key
        ksort($params);

        $pairs = array();

        foreach ($params as $key => $value) {
            array_push($pairs, rawurlencode($key)."=".rawurlencode($value));
        }

// Generate the canonical query
        $canonical_query_string = join("&", $pairs);

// Generate the string to be signed
        $string_to_sign = "GET\n".$endpoint."\n".$uri."\n".$canonical_query_string;

// Generate the signature required by the Product Advertising API
        $signature = base64_encode(hash_hmac("sha256", $string_to_sign, $aws_secret_key, true));

// Generate the signed URL
        $request_url = 'http://'.$endpoint.$uri.'?'.$canonical_query_string.'&Signature='.rawurlencode($signature);

        $ch = curl_init($request_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $response = curl_exec($ch);
        curl_close($ch);
        $xml = Parser::xml($response);
        return $xml['Items']['Item'];
    }

    public function inAppProducts(){
        return [

        ];
    }
}