<?php
Route::group(['prefix' => 'admincare', 'middleware' => ['auth', 'App\Http\Middleware\AdminMiddleware']], function()
{
    Route::get('/', 'AdminController@index');
    Route::get('/dashboard', 'AdminController@index');
    //Route::get('/stats', 'AdminController@getStats');
    
    //Route::get('/temp', 'AdminController@getTemp');

    Route::group(['prefix' => '/shop'], function() {
        
     /** Show the shop Admin Dashboard **/
    
        Route::get('/dashboard', [
            'uses' => '\App\Http\Controllers\AdminController@shopIndex',
            'as'   => 'admin.pages.shop.index'
        ]);
    
        /** Show the Admin Categories **/
        Route::get('/categories', [
            'uses' => '\App\Http\Controllers\CategoriesController@showCategories',
            'as'   => 'admin.shop.category.show'
        ]);
    
        /** Show the Admin Add Categories Page **/
        Route::get('/categories/add', [
            'uses' => '\App\Http\Controllers\CategoriesController@addCategories',
            'as'   => 'admin.shop.category.add'
        ]);
    
        /** Post the Category Route **/
        Route::post('/categories/add', [
            'uses' => '\App\Http\Controllers\CategoriesController@addPostCategories',
            'as'   => 'admin.shop.category.post'
        ]);
    
        /** Show the Admin Edit Categories Page **/
        Route::get('/categories/edit/{id}', [
            'uses' => '\App\Http\Controllers\CategoriesController@editCategories',
            'as'   => 'admin.shop.category.edit'
        ]);
    
        /** Show the Admin Update Categories Page **/
        Route::post('/categories/update/{id}', [
            'uses' => '\App\Http\Controllers\CategoriesController@updateCategories',
            'as'   => 'admin.shop.category.update'
        ]);
    
        /** Delete a category **/
        Route::delete('/categories/delete/{id}', [
            'uses' => '\App\Http\Controllers\CategoriesController@deleteCategories',
            'as'   => 'admin.shop.category.delete'
        ]);
    
    
        /****************************************Sub-Category Routes below ***********************************************/
    
    
        /** Show the Admin Add Sub-Categories Page **/
        Route::get('/categories/addsub/{id}', [
            'uses' => '\App\Http\Controllers\CategoriesController@addSubCategories',
            'as'   => 'admin.shop.category.addsub'
        ]);
    
        /** Post the Sub-Category Route **/
        Route::post('/categories/postsub/{id}', [
            'uses' => '\App\Http\Controllers\CategoriesController@addPostSubCategories',
            'as'   => 'admin.shop.category.postsub'
        ]);
    
        /** Show the Admin Edit Categories Page **/
        Route::get('/categories/editsub/{id}', [
            'uses' => '\App\Http\Controllers\CategoriesController@editSubCategories',
            'as'   => 'admin.shop.category.editsub'
        ]);
    
        /** Post the Sub-Category update Route**/
        Route::post('/categories/updatesub/{id}', [
            'uses' => '\App\Http\Controllers\CategoriesController@updateSubCategories',
            'as'   => 'admin.shop.category.updatesub',
            'middleware' => ['auth'],
        ]);
    
    
        /** Delete a sub-category **/
        Route::delete('/categories/deletesub/{id}', [
            'uses' => '\App\Http\Controllers\CategoriesController@deleteSubCategories',
            'as'   => 'admin.shop.category.deletesub'
        ]);
    
    
        /** Get all the products under a sub-category route **/
        Route::get('/categories/products/cat/{id}', [
            'uses' => '\App\Http\Controllers\CategoriesController@getProductsForSubCategory',
            'as'   => 'admin.shop.category.products'
        ]);
    
        /** Route for the sub-category drop-down */
        Route::get('/api/dropdown', 'ProductsController@categoryAPI');
    
    
        /******************************************* Products Routes below ************************************************/
    
    
        /** Show the Admin Products Page **/
        Route::get('/products', [
            'uses' => '\App\Http\Controllers\ProductsController@showProducts',
            'as'   => 'admin.shop.product.show'
        ]);
    
        /** Show the Admin Add product Page **/
        Route::get('/product/add', [
            'uses' => '\App\Http\Controllers\ProductsController@addProduct',
            'as'   => 'admin.shop.product.add'
        ]);
    
    
        /** Post the Add Product Route **/
        Route::post('/product/add', [
            'uses' => '\App\Http\Controllers\ProductsController@addPostProduct',
            'as'   => 'admin.shop.product.post'
        ]);
    
    
        /** Get the Edit product Page **/
        Route::get('/product/edit/{id}', [
            'uses' => '\App\Http\Controllers\ProductsController@editProduct',
            'as'   => 'admin.shop.product.edit'
        ]);
    
        /** Post the Admin Update Product Route **/
        Route::post('/product/update/{id}', [
            'uses' => '\App\Http\Controllers\ProductsController@updateProduct',
            'as'   => 'admin.shop.product.update'
        ]);
    
        /** Delete a product **/
        Route::delete('/product/delete/{id}', [
            'uses' => '\App\Http\Controllers\ProductsController@deleteProduct',
            'as'   => 'admin.shop.product.delete'
        ]);
    
        /** Get the Admin Upload Images Page **/
        Route::get('/products/{id}', [
            'uses' => '\App\Http\Controllers\ProductsController@displayImageUploadPage',
            'as'   => 'admin.shop.product.upload'
        ]);
    
        /** Post a photo to a Product **/
        Route::post('/products/{id}/photo', 'ProductPhotosController@store');
    
        /** Delete Product photos **/
        Route::delete('/products/photos/{id}', 'ProductPhotosController@destroy');
    
        /** Post the Product Add Featured Image Route **/
        Route::post('/products/add/featured/{id}', 'ProductPhotosController@storeFeaturedPhoto');
    
        /*********************** attributes *********************************************/
        
        /** Show the Admin attributes Page **/
        Route::get('/attributes', [
            'uses' => '\App\Http\Controllers\AttributeController@showAttributes',
            'as'   => 'admin.shop.attribute.show'
        ]);
    
        /** Show the Admin Add attribute Page **/
        Route::get('/attribute/add', [
            'uses' => '\App\Http\Controllers\AttributeController@addAttribute',
            'as'   => 'admin.shop.attribute.add'
        ]);
    
    
        /** Post the Add attribute Route **/
        Route::post('/attribute', [
            'uses' => '\App\Http\Controllers\AttributeController@addPostAttribute',
            'as'   => 'admin.shop.attribute.post'
        ]);

        /** Get the Edit attribute Page **/
        Route::get('/attribute/edit/{id}', [
            'uses' => '\App\Http\Controllers\AttributeController@editAttribute',
            'as'   => 'admin.shop.attribute.edit'
        ]);

         /** Post the Admin Update attribute Route **/
        Route::post('/attribute/update/{id}', [
            'uses' => '\App\Http\Controllers\AttributeController@updateAttribute',
            'as'   => 'admin.shop.attribute.update'
        ]);
    
        /** Delete a product **/
        Route::delete('/attribute/delete/{id}', [
            'uses' => '\App\Http\Controllers\AttributeController@deleteAttribute',
            'as'   => 'admin.shop.attribute.delete'
        ]);
        
        /******************************************* Brands Routes below ************************************************/
    
        
        /** Resource route for Admin Brand Actions **/
        Route::resource('/brands', 'BrandsController');
    
        /** Delete a Brand **/
        Route::delete('/brands/delete/{id}', [
            'uses' => '\App\Http\Controllers\BrandsController@delete',
            'as'   => 'admin.shop.brand.delete'
        ]);
    
        /** Edit a Brand **/
        Route::get('/brands/edit/{id}', [
            'uses' => '\App\Http\Controllers\BrandsController@edit',
            'as'   => 'admin.shop.brand.edit'
        ]);
        
        /** Get all the products under a brand route **/
        Route::get('/brands/products/brand/{id}', [
            'uses' => '\App\Http\Controllers\BrandsController@getProductsForBrand',
            'as'   => 'admin.shop.brand.products'
        ]);
    
    
        /** Delete a user **/
        Route::delete('/dashboard/delete/{id}', [
            'uses' => '\App\Http\Controllers\AdminController@delete',
            'as'   => 'admin.shop.delete'
        ]);
    
        /** Delete a cart session **/
        Route::delete('/dashboard/cart/delete/{id}', [
            'uses' => '\App\Http\Controllers\AdminController@deleteCart',
            'as'   => 'admin.shop.cart.delete'
        ]);
    
    
        /** Update quantity from prducts in Admin dashboard **/
        Route::post('/update', [
            'uses' => 'AdminController@update'
        ]);
    });
    
});