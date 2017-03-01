<?php

namespace App\Models;

use App\Traits\Authenticatable;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Migrations\Migration;

abstract class Model extends EloquentModel
{

    /**
     * @return Migration
     * */
    public abstract function tableDefinition();
    public function setRawAttributes(array $attributes, $sync = false)
    {
        foreach($attributes as $key=>$value){
            if(isset($this->$key)){
                $this->$key = $value;
            }
        }
        $this->attributes = $attributes;
        if ($sync) {
            $this->syncOriginal();
        }
        return $this;
    }

    public function save(array $options = [])
    {
        foreach($this->fields() as $field){
            if($field != 'id'){
                $this->attributes[$field] = $this->$field;
            }
        }
        parent::save();
        foreach($this->attributes as $key=>$value){
            if(isset($this->$key)){
                $this->$key = $value;
            }
        }
        return true;
    }

    public function fields()
    {
        return $this->tableDefinition()->fields();
    }
}
