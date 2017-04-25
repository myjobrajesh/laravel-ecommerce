<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model {

    protected $table = 'shop_brands';

    protected $fillable = [
        'brand_name',
    ];


    /**
     * One Brand can have Many Products
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productBrand() {
        return $this->hasMany('App\Models\Product', 'brand_id');
    }



}