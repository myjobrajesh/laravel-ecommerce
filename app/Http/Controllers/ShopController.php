<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

use App\Traits\BrandAllTrait;
use App\Traits\CategoryTrait;
use App\Traits\SearchTrait;
use App\Traits\CartTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use DB;
use Exception;
use Response;

DB::connection()->enableQueryLog();

class ShopController extends Controller {

    use BrandAllTrait, CategoryTrait, SearchTrait, CartTrait;

    /* send email to admin with question
     */
    public function askAQuestion(Request $request) {
        if ($request->wantsJson()) {
            $response = array();
            $validatorArr = array(
                    '_token' => 'required',
                    'pId'    => 'required',
                    'question'  =>  'required',
                    );
			$validator = Validator::make($request->all(), $validatorArr);

            if ($validator->fails()) {
                foreach($validator->errors()->getMessages() as $msg) {
                    $msgArr[] = $msg[0];
                }
                $response = array("error" => implode("<br>", $msgArr));
                return Response::json($response);
            }
            $userObj = Auth::user();
            $question =  $request->get('question');
            $hashedProductId = $request->get('pId');
            $productId = \CommonHelper::decode('product', $hashedProductId);
            $product = Product::find($productId);   
            //send email
            try {
                app()->make('\App\Mailers\AppMailers')->askAQuestion($product, $userObj, $question);
                $response = ['success'=>true];
            } catch(Exception $e) {
                $response = ['error'=>true];
            }
            return Response::json($response);
        }
       
       //send eamail
    }
    /**
     * Display things for main index home page.
     *
     * @return $this
     */
    public function index() {

        // From Traits/CategoryTrait.php
        // ( Show Categories in side-nav )
       // $categories = $this->categoryAll();

        // From Traits/BrandAll.php
        // Get all the Brands
        //$brands = $this->brandsAll();

        // From Traits/SearchTrait.php
        // ( Enables capabilities search to be preformed on this view )
        //$search = $this->search();

        // From Traits/CartTrait.php
        // ( Count how many items in Cart for signed in user )
        $cart_count = $this->countProductsInCart();

        // Select all products where featured = 1,
        // order by random rows, and only take 4 rows from table so we can display them on the homepage.
        //$products = Product::where('featured', '=', 1)->orderByRaw('RAND()')->take(4)->get();

        $options = [];
        $products = Product::getProducts($options);
        //print_r((\DB::getQueryLog()));die;
        //$rand_brands = Brand::orderByRaw('RAND()')->take(6)->get();
        //echo "<pre>";
        //print_r($products->toArray());die;
        // Select all products with the newest one first, and where featured = 0,
        // order by random rows, and only take 8 rows from table so we can display them on the New Product section in the homepage.
        $new = Product::orderBy('created_at', 'desc')->where('featured', '=', 0)->orderByRaw('RAND()')->take(4)->get();
        
        //return view('shop.index', compact('products', 'brands', 'search', 'new', 'cart_count', 'rand_brands'))->with('categories', $categories);
        //return view('shop.shop', compact('products', 'brands', 'search', 'new', 'cart_count', 'rand_brands'))->with('categories', $categories);
        return view('shop.shop', compact('products', 'new', 'cart_count'));;
    }


    /* get all products by paging, ajax call
     */
    public function getProducts(Request $request, $loadingFrom = null) {
        if ($request->wantsJson()) {
            $response = array();
            $validator = Validator::make($request->all(), array(
                    '_token' => 'required'
                    )
            );
            
            if ($validator->fails()) {
                foreach($validator->errors()->getMessages() as $msg) {
                    $msgArr[] = $msg[0];
                }
                $response = array("error" => implode("<br>", $msgArr));
            } else {
                $noBlogCls = "col-sm-4 col-md-4";
                //$userId = \CommonHelper::decode('user', $hashedUserId);
                $page = ($request->get("page")) ? $request->get("page") : 1;
                $pageType = ($request->get('pageType')) ? $request->get('pageType') : $loadingFrom;
                $category = $request->get('category');
				
			    $options = array("category"=>$category, 'status'=>'active', 'loggedInUserId'=>Auth::user()->id);
                
                $products = Product::getProducts($options);
                //print_r(last(\DB::getQueryLog()));die;
                //get all shared post by logged in userid
				$loggedInUserId = Auth::user()->id;
                $view = View('shop.productlist');
                $view->page = $page;
                //$view->noBlogCls = $noBlogCls;
                $view->products = $products;
                $view->pageType = $pageType;
                $view->viewType = $request->route()->getPath();
				
                return $view;
            }
            return $response;
       }
    }

    
    /**
     * Display Products by Category.
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function displayProducts($id) {

        // Get the Category ID , so we can display the category name under each list view
        $categories = Category::where('id', '=', $id)->get();

        $categories_find = Category::where('id', '=', $id)->find($id);

        // If no category exists with that particular ID, then redirect back to Home page.
        if (!$categories_find) {
            return redirect('/shop');
        }

        // From Traits/CategoryTrait.php
        // ( Show Categories in side-nav )
        $category = $this->categoryAll();

        // From Traits/BrandAll.php
        // Get all the Brands
        $brands = $this->brandsAll();

        // From Traits/SearchTrait.php
        // ( Enables capabilities search to be preformed on this view )
        $search = $this->search();

        // Get the Products under the Category ID
        $products = Product::where('cat_id','=', $id)->get();

        // Count the products under a certain category
        $count = $products->count();

        // From Traits/CartTrait.php
        // ( Count how many items in Cart for signed in user )
        $cart_count = $this->countProductsInCart();

        
        return view('shop.category.show', compact('products', 'categories','brands', 'category', 'search', 'cart_count'))->with('count', $count);
    }


    /** Display Products by Brand
     *
     * @param $id
     * @return $this
     * NOT USED
     */
    public function displayProductsByBrand($id) {

        // Get the Brand ID , so we can display the brand name under each list view
        $brands = Brand::where('id', '=', $id)->get();

        $brands_find = Brand::where('id', '=', $id)->find($id);

        // If no brand exists with that particular ID, then redirect back to Home page.
        if (!$brands_find) {
            return redirect('/');
        }

        // From Traits/CategoryTrait.php
        // ( Show Categories in side-nav )
        $category = $this->categoryAll();

        // From Traits/BrandAll.php
        // Get all the Brands
        $brand = $this->brandsAll();

        // From Traits/SearchTrait.php
        // ( Enables capabilities search to be preformed on this view )
        $search = $this->search();

        // Get the Products under the Brand ID
        $products = Product::where('brand_id', '=', $id)->get();

        // Count the products under a certain brand
        $count = $products->count();

        // From Traits/CartTrait.php
        // ( Count how many items in Cart for signed in user )
        $cart_count = $this->countProductsInCart();

        
        return view('shop.brand.show', compact('products', 'brands', 'brand', 'category', 'search', 'cart_count'))->with('count', $count);
    }

    
     /**
     * Display Profile contents
     *
     * @return mixed, NOT USED
     */
    public function myAccount() {

        // From Traits/CategoryTrait.php
        // ( Show Categories in side-nav )
        $categories = $this->categoryAll();

        // From Traits/BrandAll.php
        // Get all the Brands
        $brands = $this->brandsAll();

        // From Traits/SearchTrait.php
        // ( Enables capabilities search to be preformed on this view )
        $search = $this->search();

        // From Traits/CartTrait.php
        // ( Count how many items in Cart for signed in user )
        $cart_count = $this->countProductsInCart();

        // Get the currently authenticated user
        $username = \Auth::user();

        // Set user_id to the currently authenticated user ID
        $user_id = $username->id;

        // Select all from "Orders" where the user_id = the ID og the signed in user to get all their Orders
        $orders = Order::where('user_id', '=', $user_id)->get();

        return view('shop.profile.index', compact('categories', 'brands', 'search', 'cart_count', 'username', 'orders'));
    }
}