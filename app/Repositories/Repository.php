<?php
/**
 * Created by PhpStorm.
 * user: nomantufail
 * Date: 10/10/2016
 * Time: 10:13 AM
 */

namespace App\Repositories;


use App\Models\Model;
use Illuminate\Support\Facades\DB;

class Repository
{
    protected $model = null;
    public function setModel($model)
    {
        $this->model = clone($model);
        return $this;
    }

    public function getModel()
    {
        return clone($this->model);
    }

    public function all()
    {
        return $this->getModel()->all();
    }

    /**
     * @param Model $model
     * @return mixed
     */
    public function store($model)
    {
        $model->save();
        return $model;
    }

    public function insertMultiple($records = [])
    {
        return DB::table($this->getModel()->getTable())->insert($records);
    }

    public function deleteById($id)
    {
        return $this->getModel()->destroy($id);
    }

    public function findById($id)
    {
        return $this->getModel()->find($id);
    }

    public function updateWhere($where, $attrs)
    {
        $query = $this->getModel();
        foreach($where as $key => $value){
            $query = $query->where($key,'=',$value);
        }
        return $query->update($attrs);
    }
}