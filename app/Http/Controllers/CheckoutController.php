<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use Validator;
use App\Models\Product;
use App\Models\UserProductAttribute;
use App\Models\ProductMeasurement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

use App\Traits\CartTrait;


class CheckoutController extends Controller {

    
    use CartTrait;

    protected $paymentMethods;
    protected $shippingMethods;
    
    public function __construct() {
        //get checkout steps, TODO :: will be configurable
        $paymentMethods = ['cod'=>
                           ['name'=>'cod',
                            'label'=>'Cash On Delivery',
                            'price'=>''
                            ]
                           /*['name'=>'paypal',
                            'label'=>'Paypal',
                            'price'=>'',
                            'form'=>true
                            ],
                           ['name'=>'ccavenue',
                            'label'=>'CCAvenue',
                            'price'=>''
                            ],
                            */
                           ];
        $shippingMethods = [
                           /*['name'=>'free',
                            'label'=>'Cash On Delivery',
                            'price'=>'11'
                            ],
                           ['name'=>'free2',
                            'label'=>'Cash2 On Delivery',
                            'price'=>'112'
                            ],
                            */
                            ];
        $this->paymentMethods = $paymentMethods;
        $this->shippingMethods = $shippingMethods;
    }
    /**
     * Show products in Order view
     * checkout page
     * @return mixed
     */
    public function index(Request $request) {

        // Set $cart_books to the member ID, along with the products.
        $user_id = Auth::user()->id;
        $cart_products = Cart::with('products')->where('user_id', '=', $user_id)->get();
        
        $count = $cart_products->count();
        if (!$count) {
            return redirect()->route('shop.cart');
        }
        // Set $cart_products to the total in the Cart for that user_id to check and see if the cart is empty
        $cart_total = Cart::with('products')->where('user_id', '=', $user_id)->sum('total');

        
        
        $checkoutSteps = ['address' =>  ['shipping', 'billing'],
                          'shippingMethods' => $this->shippingMethods,
                           'paymentMethods'=> $this->paymentMethods,
                           'confirmOrder' =>  []
                          ];
        
        $sess = $request->session()->get('checkout');
        $sessAddress = (isset($sess['address'])) ? $sess['address'] : [];
        //print_r($sess);
        $sess['cart'] = ['total'=> $cart_total];
        $request->session()->put('checkout', $sess);
        
        //print_r($checkoutSteps);die;
        return view('shop.checkout', compact('count', 'checkoutSteps'))
            ->with('cart_products', $cart_products)
            ->with('cart_total', $cart_total)
            ->with('address', $sessAddress);
    }


    /* save shipping and billing address
     *
     */
    public function saveAddress(Request $request) {
        //print_r($request->all());
        //save into session only
        $address = $request->all();
        if($sess = $request->session()->get('checkout')) {
            $sess['address'] = $address;
        } else {
            $sess['address'] = $address;
        }
        $request->session()->put('checkout', $sess);
    }
    
    /* save shipping method
     *
     */
    public function saveShipping(Request $request) {
        //save into session only
        $data = $request->all();
        $sess = $request->session()->get('checkout');
        $sess['shipping'] = $request->all();
        $request->session()->put('checkout', $sess);
    }
    
    /* save payment method
     *
     */
    public function savePayment(Request $request) {
        //print_r($request->all());
        //save into session only
        $data = $request->all();
        $pm = $request->get("payment_method");
        $pmObj = $this->paymentMethods[$pm];
        //calculate price
        
        
        $sess = $request->session()->get('checkout');
        $sess['payment'] = $pmObj;
        $request->session()->put('checkout', $sess);
        
        //$price = ($pmObj['price'] ? $pmObj['price'] : 0); 
        
        $sess['order'] = ['grandTotal'  =>  \CommonHelper::numberFormat($this->calculateGrandTotal())];
        
        
        //if payment have redirect then redirect//TODO
        return json_encode($sess);
    }
    
    protected function calculateGrandTotal() {
        $sess = session()->get('checkout');
        $pm = $sess['payment']['name'];
        $pmObj = $this->paymentMethods[$pm];
        $price = ($pmObj['price'] ? $pmObj['price'] : 0); 
        $grandTotal = $sess['cart']['total']+$price;
        return $grandTotal;
    }
    /* save order
     *
     */
    public function saveOrder(Request $request) {
        
        $sess = $request->session()->get('checkout');
        //echo "<pre>";
       // print_r($sess);
        //die;
        //save to database
        $address = $sess['address'];
        $payment = $sess['payment'];
        
        $userId = auth()->user()->id;
        
        $cart_products = Cart::with('products')->where('user_id', '=', $userId)->get(); 
        
        
        try {
            $orderData = array(
                    'user_id'               => $userId,
                    'first_name'            => $address['first_name'],
                    'last_name'             => $address['last_name'],
                    'address'               => $address['address'],
                    'address2'              => $address['address2'],
                    'city'                  => $address['city'],
                    'state'                 => $address['state'],
                    'zip'                   => $address['zip'],
                    'total'                 => $this->calculateGrandTotal(),
                    'country'               => $address['country'],
                    'isBillingSame'         =>  isset($address['chk_billing_same']) ? $address['chk_billing_same'] : 0,
                    'billing_first_name'    =>  $address['billing_first_name'],
                    'billing_last_name'     =>  $address['billing_last_name'],
                    'billing_address'       =>  $address['billing_address'],
                    'billing_address2'      =>  $address['billing_address2'],
                    'billing_city'          =>  $address['billing_city'],
                    'billing_state'         =>  $address['billing_state'],
                    'billing_country'       =>  $address['billing_country'],
                    'billing_zip'           =>  $address['billing_zip'],
                    'created_at'            => date("Y-m-d H:i:s")
                );
            //1. save to orders
          //  print_r($orderData);die;
            $order = app()->make("\App\Models\Order");
            foreach($orderData as $field=>$value) {
                $order->$field = $value;
            }
            $order->save();
            
          
            //2. save to order_product, Attach all cart items to the pivot table with their fields
            foreach ($cart_products as $order_products) {
                $order->orderItems()->attach($order_products->product_id, array(
                    'qty'    => $order_products->qty,
                    'price'  => $order_products->products->price,
                    'reduced_price'  => $order_products->products->reduced_price,
                    'total'  => $order_products->products->price * $order_products->qty,
                    'total_reduced'  => $order_products->products->reduced_price * $order_products->qty,
                    'created_at'            => date("Y-m-d H:i:s")
                ));
            
                $cartId = $order_products->id;
                $orderId = $order->id;
                $updateData = ['entity_id'  => $orderId, "entity_type"   =>  "order", "updated_at"   =>  date("Y-m-d H:i:s")];
             
                //3. update entity_type='order', entity_id = order_id from user_product_attribute
                UserProductAttribute::where("entity_id", $cartId)
                                    ->where("entity_type", 'cart')->update($updateData);
                                    
                //4. update entity_type='order', entity_id = order_id from user_product_measurement
                ProductMeasurement::where("entity_id", $cartId)
                                    ->where("entity_type", 'cart')->update($updateData);
                
                //5. decrement qty from product
                \DB::table('shop_products')->decrement('product_qty', $order_products->qty);

            }
            //var_dump($order);die;  
            //6 send order email to customer and admin. //TODO
              //app()->make('\App\Mailers\AppMailers')->orderEmail(auth()->user(), $cart_products);
            
            //7. Delete all the items in the cart after transaction successful
            Cart::where('user_id', '=', $userId)->delete();
            
            //8. remove session - based on payment rediection
            $request->session()->pull('checkout');
            $request->session()->put('orderId', $orderId);
        } catch (Exception $e) {
            $e->getMessage();    
        }
        
        //if payment selection and redirect then redirect to...
        
        
         // Then return redirect back with success message
        //\CommonHelper::flash()->success('successMsg', 'Your order was processed successfully.');

        return redirect()->route('shop.ordersuccess');
    }
    
    /* display success message after order places
     */
    public function successOrder(){
        
        return view("shop.orderplaced");
    }
    /**
     * Make the order when user enters all credentials, NOT USED
     * 
     * @param Request $request
     * @return mixed
     */
    public function postOrder(Request $request) {

        // Validate each form field
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|max:30|min:2',
            'last_name'  => 'required|max:30|min:2',
            'address'    => 'required|max:50|min:4',
            'address_2'  => 'max:50|min:4',
            'city'       => 'required|max:50|min:3',
            'state'      => 'required|',
            'zip'        => 'required|max:11|min:4',
            'full_name'  => 'required|max:30|min:2',
        ]);


        // If error occurs, display it
        if ($validator->fails()) {
            return redirect('/shop/checkout')
                ->withErrors($validator)
                ->withInput();
        }

        // Set your secret key: remember to change this to your live secret key in production
    //    Stripe::setApiKey('YOUR STRIPE SECRET KEY');

        // Set Inputs to the the form fields so we can store them in DB
        $first_name = Input::get('first_name');
        $last_name = Input::get('last_name');
        $address = Input::get('address');
        $address_2 = Input::get('address_2');
        $country = Input::get('country');
        $city = Input::get('city');
        $state = Input::get('state');
        $zip = Input::get('zip');
        

        // Set $user_id to the currently authenticated user
        $user_id = Auth::user()->id;

        // Set $cart_products to the Cart Model with its products where
        // the user_id = to the current signed in user ID
        $cart_products = Cart::with('products')->where('user_id', '=', $user_id)->get();

        // Set $cart_total to the Cart Model alond with all its products, and
        // where the user_id = the current signed in user ID, and
        // also get the sum of the total field.
        $cart_total = Cart::with('products')->where('user_id', '=', $user_id)->sum('total');

        //  Get the total, and set the charge amount
        $charge_amount = number_format($cart_total, 2) * 100;

        
        // Create the charge on Stripe's servers - this will charge the user's card
        /*try {
            $charge = \Stripe\Charge::create(array(
                'source' => $request->input('stripeToken'),
                'amount' => $charge_amount, // amount in cents, again
                'currency' => 'usd',
            ));

        } catch(\Stripe\Error\Card $e) {
            // The card has been declined
            echo $e;
        }
        */

        // Create the order in DB, and assign each variable to the correct form fields
        $order = Order::create (
            array(
                'user_id'    => $user_id,
                'first_name' => $first_name,
                'last_name'  => $last_name,
                'address'    => $address,
                'address_2'  => $address_2,
                'city'       => $city,
                'state'      => $state,
                'zip'        => $zip,
                'total'      => $cart_total,
                'country'    => $country,
            ));

        // Attach all cart items to the pivot table with their fields
        foreach ($cart_products as $order_products) {
            $order->orderItems()->attach($order_products->product_id, array(
                'qty'    => $order_products->qty,
                'price'  => $order_products->products->price,
                'reduced_price'  => $order_products->products->reduced_price,
                'total'  => $order_products->products->price * $order_products->qty,
                'total_reduced'  => $order_products->products->reduced_price * $order_products->qty,
            ));
        }


        // Decrement the product quantity in the products table by how many a user bought of a certain product.
        \DB::table('shop_products')->decrement('product_qty', $order_products->qty);

        
        // Delete all the items in the cart after transaction successful
        Cart::where('user_id', '=', $user_id)->delete();
        //TODO::
        //update measuremt item if exists to entity_type to order and entiry_id to order id
        //update attributes item if exists to entity_type to order and entiry_id to order id
        
        // Then return redirect back with success message
        \CommonHelper::flash()->success('Success', 'Your order was processed successfully.');

        return redirect()->route('shop.cart');

    }
    

}