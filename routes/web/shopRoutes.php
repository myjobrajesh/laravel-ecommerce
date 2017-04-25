<?php

Route::group(['middleware' => ['web', 'auth'], 'prefix'=>'shop'], function () {

    /** Get the Home Page **/
    Route::get('/', ['as'=>'shop', "uses"=> 'ShopController@index']);

    /** Display All Products for paging, ajax call **/
    Route::get('/products','ShopController@getProducts');

    /** Display Products by category Route, not used **/
    Route::get('/category/{id}','ShopController@displayProducts');

    /** Display Products by Brand Route, not used **/
    Route::get('/brand/{id}','ShopController@displayProductsByBrand');

    /** Route to post search results, not used **/
    Route::post('/queries', [
        'uses' => '\App\Http\Controllers\QueryController@search',
        'as'   => 'shop.queries.search',
    ]);

    /** Route to Products show page **/
    Route::get('/product/{product_name}', [
        'uses' => '\App\Http\Controllers\ProductsController@show',
        'as'   => 'shop.show.product',
    ]);

    /************************************** Order By Routes for Products By Category ***********************************/

    /** Route to sort products by price lowest */
    Route::get('/category/{id}/price/lowest', [
        'uses' => '\App\Http\Controllers\OrderByController@productsPriceLowest',
        'as'   => 'shop.category.lowest',
    ]);

    /**Route to sort products by price highest */
    Route::get('/category/{id}/price/highest', [
        'uses' => '\App\Http\Controllers\OrderByController@productsPriceHighest',
        'as'   => 'shop.category.highest',
    ]);


    /** Route to sort products by alphabetical A-Z */
    Route::get('/category/{id}/alpha/highest', [
        'uses' => '\App\Http\Controllers\OrderByController@productsAlphaHighest',
        'as'   => 'shop.category.alpha.highest',
    ]);

    /**Route to sort products by alphabetical  Z-A */
    Route::get('/category/{id}/alpha/lowest', [
        'uses' => '\App\Http\Controllers\OrderByController@productsAlphaLowest',
        'as'   => 'shop.category.alpha.lowest',
    ]);

    /**Route to sort products by alphabetical  Z-A */
    Route::get('/category/{id}/newest', [
        'uses' => '\App\Http\Controllers\OrderByController@productsNewest',
        'as'   => 'shop.category.newest',
    ]);


    /************************************** Order By Routes for Products By Brand ***********************************/

    /** Route to sort products by price lowest */
    Route::get('/brand/{id}/price/lowest', [
        'uses' => '\App\Http\Controllers\OrderByController@productsPriceLowestBrand',
        'as'   => 'shop.brand.lowest',
    ]);

    /**Route to sort products by price highest */
    Route::get('/brand/{id}/price/highest', [
        'uses' => '\App\Http\Controllers\OrderByController@productsPriceHighestBrand',
        'as'   => 'shop.brand.highest',
    ]);


    /** Route to sort products by alphabetical A-Z */
    Route::get('/brand/{id}/alpha/highest', [
        'uses' => '\App\Http\Controllers\OrderByController@productsAlphaHighestBrand',
        'as'   => 'shop.brand.alpha.highest',
    ]);

    /**Route to sort products by alphabetical  Z-A */
    Route::get('/brand/{id}/alpha/lowest', [
        'uses' => '\App\Http\Controllers\OrderByController@productsAlphaLowestBrand',
        'as'   => 'shop.brand.alpha.lowest',
    ]);

    /**Route to sort products by alphabetical  Z-A */
    Route::get('/brand/{id}/newest', [
        'uses' => '\App\Http\Controllers\OrderByController@productsNewestBrand',
        'as'   => 'shop.brand.newest',
    ]);


    /**************************************** Cart Routes *********************************************/
    
    
    /** Get the view for Cart Page **/
    Route::get('/cart', array(
        'before' => 'auth.basic',
        'as'     => 'shop.cart',
        'uses'   => 'CartController@showCart'
    ));

    /** Add items in the cart **/
    Route::post('/cart/add', array(
        'before' => 'auth.basic',
        'uses'   => 'CartController@addCart'
    ));

    /** Update items in the cart **/
    Route::post('/cart/update', [
        'uses' => 'CartController@update'
    ]);

    /** Delete items in the cart **/
    Route::get('/cart/delete/{id}', array(
        'before' => 'auth.basic',
        'as'     => 'shop.delete_book_from_cart',
        'uses'   => 'CartController@delete'
    ));


    /** Ask a Question **/
    Route::post('/askquestion', array(
        'as' => 'shop.askquestion',
        'uses'   => 'ShopController@askAQuestion'
    ));

    
    /**************************************** Order Routes *********************************************/


    /** Get thew checkout view **/
    Route::get('/checkout', [
        'uses' => 'CheckoutController@index',
        'as'   => 'shop.checkout'
    ]);

    /* save shipping and billing info
     */
    Route::post('saveaddress', [
        'uses' => 'CheckoutController@saveAddress',
        'as'   => 'shop.saveaddress'
    ]);
    
    /* save shipping method
     */
    Route::post('saveshipping', [
        'uses' => 'CheckoutController@saveShipping',
        'as'   => 'shop.saveshipping'
    ]);
    
    /* save payment method
     */
    Route::post('savepayment', [
        'uses' => 'CheckoutController@savePayment',
        'as'   => 'shop.savepayment'
    ]);
    
    /* save order
     */
    /*Route::post('saveorder', [
        'uses' => 'CheckoutController@saveOrder',
        'as'   => 'shop.saveorder'
    ]);
*/
    /** Post an Order **/
    Route::post('/order',
        array(
            'as'     => 'shop.order',
            'uses'   => 'CheckoutController@saveOrder'
        ));

    Route::get('/ordersuccess',
        array(
            'as'     => 'shop.ordersuccess',
            'uses'   => 'CheckoutController@successOrder'
        ));


    /******************************************* User Profile Routes below ************************************************/


    /** Resource route for Profile **/
    Route::resource('/profile', 'ShopController@myAccount');
    
});



