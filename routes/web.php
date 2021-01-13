<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */
Route::get('/pizzamart/{page}/{params?}/{x?}', 'PageController@home_page')->where('any', '*');

Route::get('/',function ()
{
    return view('app');
});

Route::get('/pizzamart', function () {
    return redirect("/");
});

Route::group(['middleware' => 'guest:user'], function(){
    Route::prefix('auth')->group(function () {
        Route::get('myOrders/{id}', 'UserController@getHistory');
        Route::get('init' , 'UserController@init');
        Route::post('login' , 'UserController@login');
        Route::post('register' , 'UserController@register');
        Route::post('logout' , 'UserController@logout');
        Route::post('registerStore', 'UserController@registerStore');
        Route::get('getMyDetails/{id}', 'UserController@getMyDetails');
        Route::get('viewStores','UserController@viewStores');
        Route::get('myProfile', 'UserController@myProfile');
        Route::post('updateProfile/{id?}', 'UserController@updateProfile');
    });
});

Route::prefix('product')->group(function(){
    Route::get('getAll', 'ProductsController@getAllProducts');
    Auth::routes();
    Route::post('addProduct', 'ProductsController@addProduct');
    Route::post('editProduct/{id}', 'ProductsController@editProduct');
    Route::post('deleteProduct/{id}', 'ProductsController@deleteProduct');
    Route::get('getMyProducts/{id}', 'ProductsController@getMyProducts');
    Route::get('getStoreProducts/{id}','ProductsController@getStoreProducts');
  
    
});

Route::prefix('transaction')->group(function(){
    Auth::routes();
    Route::get('summary', 'TransactionController@summary');
    Route::post('order', 'TransactionController@order');
    Route::get('viewOrders/{type}/{id}', 'TransactionController@handleTransactionRequest');
    Route::post('resolveOrder', 'TransactionController@updateOrder');
    Route::get('order_detail/{id?}','TransactionController@viewOrderDetail');
});





