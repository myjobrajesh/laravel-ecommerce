<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Attribute;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductEditRequest;

use App\Traits\BrandAllTrait;
use App\Traits\CategoryTrait;
use App\Traits\SearchTrait;
use App\Traits\CartTrait;
use App\Traits\AttributeTrait;

\DB::connection()->enableQueryLog();

class ProductsController extends Controller {

    use BrandAllTrait, CategoryTrait, SearchTrait, CartTrait, AttributeTrait;


    /**
     * Show all products
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showProducts() {

        // Get all latest products, and paginate them by 10 products per page
        $product = Product::latest('created_at')->paginate(10);

        // Count all Products in Products Table
        $productCount = Product::all()->count();

        // From Traits/CartTrait.php
        // ( Count how many items in Cart for signed in user )
        $cart_count = $this->countProductsInCart();

        return view('admin.product.show', compact('productCount', 'product', 'cart_count'));
    }


    /**
     * Return the view for add new product
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addProduct() {
        // From Traits/CategoryTrait.php
        // ( This is to populate the parent category drop down in create product page )
        $categories = $this->parentCategory();

        // From Traits/BrandAll.php
        // Get all the Brands
        $brands = $this->brandsAll();

        
        // From Traits/CartTrait.php
        // ( Count how many items in Cart for signed in user )
        $cart_count = $this->countProductsInCart();

        //attrutes
        
        //From AttributeTrait traits
        $attributes = $this->getActiveAttributeArray();
        //print_r($attributes);
        return view('admin.product.add', compact('categories', 'brands', 'cart_count', 'attributes'));
    }


    /**
     * Add a new product into the Database.
     *
     * @param ProductRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addPostProduct(ProductRequest $request) {

        // Check if checkbox is checked or not for featured product
        $featured = Input::has('featured') ? true : false;

        // Replace any "/" with a space.
        $product_name =  str_replace("/", " " ,$request->input('product_name'));

             // Create the product in DB
            $product = Product::create([
                'product_name' => $product_name,
                'product_qty' => $request->input('product_qty'),
                'product_sku' => $request->input('product_sku'),
                'price' => $request->input('price'),
                'reduced_price' => $request->input('reduced_price'),
                //if sub exists then add else parent add
                'cat_id' => ($request->input('cat_id')) ? $request->input('cat_id') : $request->input('category'),
                'brand_id' => $request->input('brand_id'),
                'featured' => $featured,
                'description' => $request->input('description'),
                'product_spec' => $request->input('product_spec'),
            ]);

            // Save the product into the Database.
            $product->save();

            //save to product attr
            \App\Models\ProductAttribute::saveTo($product->id, $request);
            // Flash a success message
            \CommonHelper::flash()->success('Success', 'Product created successfully!');
       // }


        // Redirect back to Show all products page.
        return redirect()->route('admin.shop.product.show');
    }


    /**
     * This method will fire off when a admin chooses a parent category.
     * It will get the option and check all the children of that parent category,
     * and then list them in the sub-category drop-down.
     *
     * @return \Illuminate\Http\Response
     */
    public function categoryAPI() {
        // Get the "option" value from the drop-down.
        $input = Input::get('option');

        // Find the category name associated with the "option" parameter.
        $category = Category::find($input);

        // Find all the children (sub-categories) from the parent category
        // so we can display then in the sub-category drop-down list.
        $subcategory = $category->children();

        // Return a Response, and make a request to get the id and category (name)
        return \Response::make($subcategory->get(['id', 'category']));
    }


    /**
     * Return the view to edit & Update the Products
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editProduct($id) {

        // Find the product ID
        $product = Product::where('id', '=', $id)->find($id);

        
        //$attributeArray = $this->getProductAttributeArray($id);// will use for frontend side display
        //From AttributeTrait traits
        $attributes = $this->getActiveAttributeArray();
        //echo "<pre>";
        $selectAttr = [];
        if($product->attributes) {
            foreach($product->attributes as $val) {
                $selectedAttr[$val->attribute_name][$val->attribute_id] = $val->toArray();
            }    
        }
        $attributeArray = [];
        if($attributes && $selectAttr) {
            foreach($attributes as $k=>$attr) {
               // if(array_key_exists($k, $selectedAttr)) {
                    foreach($attr as $k2=>$val) {
                        $out['value'] =  $val;
                        $out['selected'] = false;
                        $out['price']   =   '';
                        $out['price_variant']   =   '';
                        if(array_key_exists($k2, $selectedAttr[$k])) {
                        //if( $k2 == $selectedAttr[$k][$k2]['attribute_id']) {
                            $out['selected'] = true;
                            $out['price']   =   $selectedAttr[$k][$k2]['price'];
                            $out['price_variant']   =   $selectedAttr[$k][$k2]['price_variant'];
                        }
                        $attributeArray[$k][$k2] = $out;
                    }
                //}
            }    
        }
        
       // echo "<pre>";print_r($attributeArray);
        //print_r($attributes);die;
        
        // If no product exists with that particular ID, then redirect back to Show Products Page.
        if (!$product) {
            return redirect('admincare/shop/products');
        }

        // From Traits/CategoryTrait.php
        // ( This is to populate the parent category drop down in create product page )
        $categories = $this->parentCategory();

        // From Traits/BrandAll.php
        // Get all the Brands
        $brands = $this->BrandsAll();

        // From Traits/CartTrait.php
        // ( Count how many items in Cart for signed in user )
        $cart_count = $this->countProductsInCart();

        // Return view with products and categories
        return view('admin.product.edit', compact('product', 'categories', 'brands', 'cart_count', 'attributeArray', 'attributes'));

    }


    /**
     * Update a Product
     *
     * @param $id
     * @param ProductEditRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProduct($id, ProductEditRequest $request) {

        // Check if checkbox is checked or not for featured product
        $featured = Input::has('featured') ? true : false;

        // Find the Products ID from URL in route
        $product = Product::findOrFail($id);


        /*if (Auth::user()->id == 2) {
            // If user is a test user (id = 2),display message saying you cant delete if your a test user
            \CommonHelper::flash()->error('Error', 'Cannot edit Product because you are signed in as a test user.');
        } else {
          */
            // Update product
            $product->update(array(
                'product_name' => $request->input('product_name'),
                'product_qty' => $request->input('product_qty'),
                'product_sku' => $request->input('product_sku'),
                'price' => $request->input('price'),
                'reduced_price' => $request->input('reduced_price'),
                'cat_id' => $request->input('cat_id'),
                'brand_id' => $request->input('brand_id'),
                'featured' => $featured,
                'description' => $request->input('description'),
                'product_spec' => $request->input('product_spec'),
            ));


            // Update the product with all the validation rules
            $product->update($request->all());

            //save to product attr
            \App\Models\ProductAttribute::updateTo($id, $request);
            
            // Flash a success message
            \CommonHelper::flash()->success('Success', 'Product updated successfully!');
        //}

        // Redirect back to Show all categories page.
        return redirect()->route('admin.shop.product.show');
    }


    /**
     * Delete a Product
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteProduct($id) {

        /*if (Auth::user()->id == 2) {
            // If user is a test user (id = 2),display message saying you cant delete if your a test user
            \CommonHelper::flash()->error('Error', 'Cannot delete Product because you are signed in as a test user.');
        } else {
          */
            // Find the product id and delete it from DB.
            Product::findOrFail($id)->delete();
        //}

        // Then redirect back.
        return redirect()->back();
    }


    /**
     * Display the form for uploading images for each Product
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function displayImageUploadPage($id) {

        // Get the product ID that matches the URL product ID.
        $product = Product::where('id', '=', $id)->get();

        // From Traits/CartTrait.php
        // ( Count how many items in Cart for signed in user )
        $cart_count = $this->countProductsInCart();

        return view('admin.product.upload', compact('product', 'cart_count'));
    }


    /**
     * Show a Product in detail
     *
     * @param $product_name
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($product_name) {

        // Find the product by the product name in URL
        $product = Product::ProductLocatedAt($product_name);

        // From Traits/SearchTrait.php
        // Enables capabilities search to be preformed on this view )
        //$search = $this->search();

        // From Traits/CategoryTrait.php
        // ( Show Categories in side-nav )
        //$categories = $this->categoryAll();

        // Get brands to display left nav-bar
        //$brands = $this->BrandsAll();

        // From Traits/CartTrait.php
        // ( Count how many items in Cart for signed in user )
        $cart_count = $this->countProductsInCart();


       /* $similar_product = Product::where('id', '!=', $product->id)
            ->where(function ($query) use ($product) {
                $query->where('brand_id', '=', $product->brand_id)
                    ->orWhere('cat_id', '=', $product->cat_id);
            })->get();
        */
        $productHashId = \CommonHelper::encode('product', $product->id);

        $attributes = $this->getProductAttributeArray($product->id);// will use for frontend side display
        //print_r($attributes);die;
        $rating = \App\Models\ProductRating::getAverageRating($product->id);
        $totalReview = $product->reviews->count();
        
        //get rating group by star
        $ratingByStar = \App\Models\ProductRating::getRatingByStar($product->id);
        
        $reviewsObj = \App\Models\ProductReview::getReviewsByProduct($product->id);
        //$reviews = '';
       // if($reviewsObj->total()) {
            $reviews = View('shop.partials.reviewlist')->with(["reviews"=>$reviewsObj])->render();    
        //}
        
        //echo $reviews;die;
        //print_r($ratingByStar->toArray());die;
        
        return view('shop.product', compact('product', 'search', 'brands', 'categories', 'similar_product', 'cart_count', 'productHashId', 'rating', 'attributes', 'totalReview', 'ratingByStar', 'reviews'));
        //return view('shop.show_product', compact('product', 'search', 'brands', 'categories', 'similar_product', 'cart_count'));
    }


}