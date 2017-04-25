<?php
namespace App\Traits;

use App\Models\Attribute;
use App\Models\ProductAttribute;

trait AttributeTrait {


    /* get attibures by key values pair
     */
    public function getActiveAttributeArray() {
        $attributesObj = Attribute::where('status', 'active')->get();
        $attributes = null;
        if($attributesObj) {
            foreach($attributesObj as $attr) {
                //$attributes[$attr->name][] = $attr->value;
                $attributes[$attr->name][$attr->id] = $attr->value;
            }
           
        }
        return $attributes;
    }

    /* get product attibures by key values pair for front end view
     */
    public function getProductAttributeArray($productId) {
        $attributesObj = ProductAttribute::where('product_id', $productId)->get();
        $attributes = null;
        if($attributesObj) {
            foreach($attributesObj as $attr) {
                $attributes[$attr->attribute_name][$attr->attribute_id] = [
                                                        'value'  =>  $attr->attribute_value,
                                                        'price'  =>  $attr->price,
                                                        'price_variant'  =>  $attr->price_variant
                                                       ];
            }
        }
        return $attributes;
    }

    

}