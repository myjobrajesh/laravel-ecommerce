<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProductPhoto extends Model {

    /**
     * @var string
     * The associated table.
     */
    protected $table = "shop_product_images";

    /**
     * @var array
     */
    protected $fillable = ['filename', 'filepath', 'featured'];


    /**
     * Product photos belong to a Product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product() {
        return $this->belongsTo('App\Models\Product');
    }


    /**
     * Delete a photo
     *
     * @throws \Exception
     */
    public function delete() {

        // Delete path and thumbnail_path of photo
        \File::delete([
            $this->path
        ]);

        parent::delete();
    }


}