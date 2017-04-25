<?php 

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductPhoto;//TODO ::change two model to one

use App\Models\FileUpload;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Http\Requests\ProductPhotoRequest;

class ProductPhotosController extends Controller {


    /**
     * @param $id
     * @param ProductPhotoRequest $request
     */
    public function store($id, ProductPhotoRequest $request) {
        // Set $product = Product::LocatedAt() in (Product.php Model) = to the id
        // -- Find the product.
        $product = Product::LocatedAt($id);

        // Store the photo from the file instance
        // -- ('photo') is coming from "public/js/dropzone.forms.js" --
      //  $photo = $request->file('photo');

        //TODO :: save and create thumbnail
        $filenameToSave = FileUpload::uploadFileAndThumbnail("shop");
        
        if($filenameToSave) {
            $filename = substr(strrchr($filenameToSave, '/'), 1);
            $obj = \App::make('\App\Models\ProductPhoto');
            $obj->filename = $filename;
            $obj->filepath = $filenameToSave;
            $obj->product_id = $id;
            $obj->save();
		}
        // Create dedicated class to add photos to product, and save the photos.
        //(new AddPhotoToProduct($product, $photo))->save();
    }


    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id) {
        // Find the photo and delete it.
        $obj = ProductPhoto::findOrFail($id);
        //delete files from folder
        FileUpload::removeFilesFromFolder($obj, 'shop');
        $obj->delete();
        // Then return back;
        return back();
    }


    /**
     * Store and update the featured photo for a product
     *
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeFeaturedPhoto($id, Request $request) {
        // Validate featured button
        $this->validate($request, [
            'featured' => 'required|exists:shop_product_images,id'
        ]);

        // Grab the ID of the Image being featured from radio button
        $featured = Input::get('featured');

        // Select from "product_images" where the 'product_id' = the ID in the URL, and update "featured" column to 0
        ProductPhoto::where('product_id', '=', $id)->update(['featured' => 0]);

        // Find the $featured result and update "featured" column to 1
        ProductPhoto::findOrFail($featured)->update(['featured' => 1]);


        // Return redirect back
        return redirect()->back();
    }


}