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

function rest($path, $controller)
{
    global $app;
    
    $app->get($path, $controller.'@index');
    $app->get($path.'/show/{id}', $controller.'@show');
    $app->get($path + '/save', $controller.'@save');
    $app->put($path.'/update/{id}', $controller.'@update');
    $app->delete($path.'/delete/{id}', $controller.'@delete');
}

$app->get('/', function () use ($app) {
    return $app->version();
});

// Kiosk API Endpoint Routes
$app->get('checkout', 'ApiController@checkout');
$app->get('checkin', 'ApiController@checkin');

// User Endpoint Routes
$app->get('user/checked_out_items', 'UserController@checkedOutItems');
rest('/user', 'UserController');

// Store Endpoint Routes
$app->get('store/users', 'StoreController@users');
rest('/store', 'StoreController');

// Reservation Endpoint Routes
rest('/reservation', 'ReservationController');

// Item Endpoint Routes
rest('/item', 'ItemController');

