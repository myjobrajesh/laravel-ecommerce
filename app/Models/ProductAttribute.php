<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model {

    protected $table = 'shop_product_attribute';

    public $timestamps = false;
    
    
    /* save to attribute
     */
    public static function saveTo($productId, $request) {
        $attrName = $request->get('attr_name');
        $attrValue = $request->get('attr_value');
        $attrPriceVariant = $request->get('attr_priceVariant');
        $attrPrice = $request->get('attr_price');
            if($attrName && $attrValue) {
                $attrArr = [];
                foreach($attrName as $key=>$name) {
                    //get attribute id and value
                    $avEx = explode("_", $attrValue[$key]);
                    
                    $attrArr[] = [
                        'product_id'  =>  $productId,
                        'attribute_id'  =>  $avEx[0],
                        'attribute_name'  =>  $name,
                        'attribute_value'  =>  $avEx[1],
                        'price'  =>  isset($attrPrice[$key]) ? $attrPrice[$key] : 0,
                        'price_variant'  =>  isset($attrPriceVariant[$key]) ? $attrPriceVariant[$key] : 'fixed',
                        'created_at'  =>  date("Y-m-d H:i:s")
                    ];
                }
                //save to product attribute
                $obj = static::insert($attrArr);
            }
       
    }
    
    /* update to attribute
     */
    public static function updateTo($productId, $request) {
        //first check if entry exists if so then delete and save again
        static::where('product_id', $productId)->delete();
        static::saveTo($productId, $request);
        
    }
}