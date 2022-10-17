<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductFeature extends Model
{
    protected $table = 'product_features';

    public function product(){
        return $this->belongsTo('App\Product', 'product_id', 'id');
    }
}
