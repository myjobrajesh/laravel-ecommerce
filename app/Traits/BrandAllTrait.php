<?php
namespace App\Traits;

use App\Models\Brand;

trait BrandAllTrait {


    public function brandsAll() {
        // Get all the brands from the Brands Table.
        return Brand::all();
    }


}