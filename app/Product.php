<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    public function features(){
        return $this->hasMany('App\ProductFeature', 'product_id', 'id');
    }

    public function category(){
        return $this->belongsTo('App\Category', 'category_id', 'id');
    }

    public function cartItems(){
        return $this->belongsToMany('App\CartItem', 'cart_items', 'product_id', 'id');
    }

    public function orderItems(){
        return $this->belongsToMany('App\OrderItem', 'order_items', 'product_id', 'id');
    }

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

}
