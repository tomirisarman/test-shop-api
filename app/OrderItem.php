<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';
    protected $fillable = ['order_id', 'product_id', 'quantity'];
    public $timestamps = false;

    public function order(){
        return $this->belongsTo('App\Order', 'order_id', 'id');
    }

    public function product(){
        return $this->hasOne('App\Product', 'product_id', 'id');
    }
}
