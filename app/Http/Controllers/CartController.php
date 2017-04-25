<?php
namespace App\Http\Controllers;

use App\Models\Cart;
use Validator;
use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Routing\Controller as BaseController;

use App\Traits\BrandAllTrait;
use App\Traits\CategoryTrait;
use App\Traits\SearchTrait;
use App\Traits\CartTrait;

use Illuminate\Http\Request;
use Response;
use App\Traits\AttributeTrait;

class CartController extends Controller {

    use BrandAllTrait, CategoryTrait, SearchTrait, CartTrait, AttributeTrait;


    /**
     * Return the Cart page with the cart items and total
     * 
     * @return mixed
     */
    public function showCart() {

        $user_id = Auth::user()->id;
        
        // Set $cart_books to the member ID, along with the products.
        // ( "products" is coming from the Products() method in the Product.php Model )
        $cart_products = Cart::with(['products', 'measurements', 'attributes'])
                            ->where('user_id', '=', $user_id)->get();

        // Set $cart_products to the total in the Cart for that user_id to check and see if the cart is empty
        $cart_total = Cart::where('user_id', '=', $user_id)->sum('total');

        // Count all the products in a cart  with the currently signed in user
       // $count = Cart::where('user_id', '=', $user_id)->count();
        $count = $cart_products->count();
        // Return the cart with products, and total amount in cart
        return view('shop.cart', compact( 'count'))
            ->with('cart_products', $cart_products)
            ->with('cart_total', $cart_total);
    }


    /**
     * Add Products to the cart
     * 
     * @return mixed
     */
    public function addCart(Request $request) {
         if ($request->wantsJson()) {
            $validatorArr = array(
                    '_token' => 'required',
                    'data' => 'required'
                    );
			$validator = Validator::make($request->all(), $validatorArr);

            if ($validator->fails()) {
                foreach($validator->errors()->getMessages() as $msg) {
                    $msgArr[] = $msg[0];
                }
                $response = array("error" => implode("<br>", $msgArr));
                return Response::json($response);
            }
            
            //validate cart data
            $formData = $request->get('data');
            $measurementData = isset($formData['measurements']) ? array_filter($formData['measurements']) : null;
            
            $attrData = isset($formData['attributes']) ? array_filter($formData['attributes']) : null;
            
            
            $hashedProductId = $formData['product'];
            $qty = $formData['qty'];
            $totalPrice = $formData['totalPrice'];
            
            $productId = \CommonHelper::decode('product', $hashedProductId);
            $loggedInUserId = Auth::user()->id;
 
            // Get the ID of the Products in the cart
            $product = Product::find($productId);
        
            // set total to quantity * the product price
            // $total = $qty * $product->price;
            /*if ($product->reduced_price == 0) {
                $total = $qty * $product->price;
            } else {
                $total = $qty * $product->reduced_price;
            }*/
            $total = $qty * $totalPrice;
    
            // Create the Cart
            $cartObj = Cart::create(
                array (
                    'user_id'    => $loggedInUserId,
                    'product_id' => $productId,
                    'qty'        => $qty,
                    'total'      => $total,
                    'created_at' => date('Y-m-d H:i:s'),
                )
            );

            //save measurements
            if($measurementData) {
                
                $mArr = [];
                foreach($measurementData as $name=>$value) {
                    $mArr1 = [];
                    $mArr1['user_id']    = $loggedInUserId;
                    $mArr1['product_id'] = $productId;
                    $mArr1['created_at'] = date('Y-m-d H:i:s');
                    $mArr1['entity_id'] = $cartObj->id;
                    $mArr1['entity_type'] = 'cart';    
                    $mArr1['name'] = $name;
                    $mArr1['value'] = $value;
                    $mArr[] = $mArr1;
                }
                \App\Models\ProductMeasurement::insert($mArr); 
            }

            //attribute save
            if($attrData) {
                $attrToSave = [];
                $attributes = $this->getProductAttributeArray($productId);// will use for frontend side display
                foreach($attributes as $key=>$attrArr) {
                    if(array_key_exists($key, $attrData)) {
                        foreach($attrArr as $attr => $val) {
                            if($attrData[$key] == $val['value']) {
                                $attrToSave[$key] =  [
                                                      'product_id'  =>  $productId,
                                                      'entity_id'   =>  $cartObj->id,
                                                      'entity_type' =>  'cart',
                                                      'attribute_name'  =>  $key,
                                                      'attribute_value'  =>  $val['value'],
                                                      'price'  =>  $val['price'],
                                                      'price_variant'  =>  $val['price_variant'],
                                                       'created_at' => date('Y-m-d H:i:s')
                                                      ];
                            }
                        }
                    }
                }
                if($attrToSave) {
                    \App\Models\UserProductAttribute::insert($attrToSave); 
                }
            }

            $response = ['redirect' => route('shop.cart')];
            return Response::json($response);
        }

    }


    /**
     * Update the Cart
     * 
     * @return mixed
     */
    public function update() {
        
        // Set $user_id to the currently signed in user ID
        $user_id = Auth::user()->id;

        // Set the $qty to the quantity of products selected
        $qty = Input::get('qty');

        // Set $product_id to the hidden product input field in the update cart from
        $product_id = Input::get('product');

        // Set $cart_id to the hidden cart_id input field in the update cart from
        $cart_id = Input::get('cart_id');
        
        // Find the ID of the products in the Cart
        $product = Product::find($product_id);

        if ($product->reduced_price == 0) {
            $total = $qty * $product->price;
        } else {
            $total = $qty * $product->reduced_price;
        }

        // Select ALL from cart where the user ID = to the current logged in user, product_id = the current product ID being updated, and the cart_id = to the cartId being updated
        $cart = Cart::where('user_id', '=', $user_id)->where('product_id', '=', $product_id)->where('id', '=', $cart_id);

        // Update your cart
        $cart->update(array(
            'user_id'    => $user_id,
            'product_id' => $product_id,
            'qty'        => $qty,
            'total'      => $total
        ));

        return redirect()->route('shop.cart');
    }
    

    /**
     * Delete a product from a users Cart
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id) {
        // Find the Carts table and given ID, and delete the record
        Cart::find($id)->delete();
        
        //delete from measurements
        \App\Models\ProductMeasurement::where('entity_type', 'cart')->where('entity_id', $id)->delete();
        //delete from attrubutes
        \App\Models\UserProductAttribute::where('entity_type', 'cart')->where('entity_id', $id)->delete();
        // Then redirect back
        return redirect()->back();
    }
    
    
}