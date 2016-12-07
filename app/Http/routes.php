<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return $app->version();
});

// Kiosk API Endpoint Routes
$app->group(['prefix'     => 'kiosk',
             'namespace'  => 'App\Http\Controllers'], function () use ($app) {
    $app->get('checkout', 'ApiController@checkout');
    $app->get('checkin', 'ApiController@checkin');

    $app->get('reservation', 'ReservationController@index');
    $app->get('item', 'ItemController@index');
    $app->get('users', 'ApiController@users');
});


// Used by frontend app to login with passed email and google token id
$app->group(['middleware' => 'cors',
             'namespace'  => 'App\Http\Controllers'], function () use ($app) {
    $app->get('login', 'ApiController@login');
});
// For use with the web app
$app->group(['middleware' => ['google_oauth', 'cors'],
             'namespace'  => 'App\Http\Controllers'], function () use ($app) {

    function rest($path, $controller)
    {
        global $app;

        $app->get($path, $controller.'@index');
        $app->get($path.'/show/{id}', $controller.'@show');
        $app->get($path + '/save', $controller.'@save');
        $app->put($path.'/update/{id}', $controller.'@update');
        $app->delete($path.'/delete/{id}', $controller.'@delete');
    }

    // User Endpoint Routes
    $app->get('user/checked_out_items', 'UserController@checkedOutItems');
    $app->get('user/stores', 'UserController@stores');
    $app->get('user/owned_stores', 'UserController@owned_stores');
    rest('/user', 'UserController');

    // Store Endpoint Routes
    $app->get('store/users', 'StoreController@users');
    $app->get('store/add_user', 'StoreController@addUser');
    $app->get('user/checked_out_items', 'UserController@checkedOutItems');
    $app->get('user/reservations', 'UserController@reservations');
    rest('/store', 'StoreController');

    // Reservation Endpoint Routes
    rest('/reservation', 'ReservationController');

    // Item Endpoint Routes

    rest('/item', 'ItemController');
    $app->get('item/search', 'ItemController@search');
    $app->get('item/status', 'ItemController@status');

});
