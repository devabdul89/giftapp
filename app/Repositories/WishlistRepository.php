<?php
/**
 * Created by PhpStorm.
 * user: nomantufail
 * Date: 10/10/2016
 * Time: 10:13 AM
 */

namespace Repositories;

use LaraModels\User;

class WishlistRepository extends \Repositories\Repository
{
    public function __construct()
    {
        $this->setModel(new \LaraModels\Wishlist());
    }

    public function add(array $data){
        return $this->getModel()->create($data);
    }

    public function removeProduct($userId, $productId){
        return $this->getModel()->where(['user_id'=>$userId,'product_id'=>$productId])->delete();
    }

    public function getByUser($userId){
        return $this->getModel()->with('product')->get();
    }
}