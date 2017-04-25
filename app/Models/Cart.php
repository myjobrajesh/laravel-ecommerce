<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model {

    /**
     * @var string
     */
    protected $table = 'shop_carts';


    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'product_id', 'qty', 'total', 'created_at'
    ];

    /**
     * A Product belongs to a Cart
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Products() {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }


    public function productList() {
        return $this->hasMany('App\Models\Product', 'id', 'product_id');
    }
    
    /**
     * A Cart belongs to a User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function User() {
        return $this->belongsTo('App\Models\Entities\User', 'user_id');
    }

    /**
     * A Cart has many measurements
     *
     */
    public function measurements() {
        return $this->hasMany('App\Models\ProductMeasurement', 'entity_id', 'id')->where('entity_type', 'cart');
    
    }

    /**
     * A Cart has many attributes
     *
     */
    public function attributes() {
        return $this->hasMany('App\Models\UserProductAttribute', 'entity_id')->where('entity_type', 'cart');
    }
}