<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    /**
     * @var string
     */
    protected $table = 'shop_orders';


    public $timestamps = false;
    
    /**
     * An Order can have many products
     *
     * @return $this
     */
    public function orderItems() {
        return $this->belongsToMany('App\Models\Product', 'shop_order_product')->withPivot('qty', 'price', 'reduced_price', 'total', 'total_reduced');
    }

}