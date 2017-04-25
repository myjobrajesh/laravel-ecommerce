<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model {

    protected $table = 'shop_product_reviews';

    public $timestamps = false;
    
    /**
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];
    
    
    /**
     * @param $id
     *
     * @return mixed
     */
    public function deleteReview($id)
    {
        return static::find($id)->delete();
    }

    public static function getReviewsByProduct($productId, $perPage = null) {
        $perPage = ($perPage) ? $perPage : config('app.paging');
        
        $reviews = static::where('product_id', $productId)->orderBy('id', 'DESC')->paginate($perPage);
        
        return $reviews;
    }
}