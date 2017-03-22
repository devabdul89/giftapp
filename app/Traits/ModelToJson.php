<?php
/**
 * Created by PhpStorm.
 * User: officeaccount
 * Date: 07/03/2017
 * Time: 12:05 PM
 */

namespace Traits;
use Models\Model;

trait ModelToJson
{
    private function modelsToJson(array $models){
        $array = [];
        foreach ($models as $model /** @var $model Model*/){
            $array[] = $model->toJson();
        }
        return $array;
    }
}