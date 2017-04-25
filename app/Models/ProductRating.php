<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class ProductRating extends Model {

    protected $table = 'shop_product_ratings';

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
    public function deleteRating($id)
    {
        return static::find($id)->delete();
    }

    public static function getAverageRating($productId) {
        $res = self::select(DB::raw('ROUND(avg(rating),2) as average, count(id) as total, ROUND(sum(rating),2) as sum'))->where('product_id', $productId)->first();
        return $res;
    }
    
    /* get ratings for star wise display
     */
    public static function getRatingByStar($productId) {
        $ratingByStar = self::where('product_id', $productId)->groupBy(\DB::raw('FLOOR(rating)'))->get([\DB::raw('FLOOR(rating) as rating'), \DB::raw('count(id) as total')]);
        return $ratingByStar;
    
    }
    
}