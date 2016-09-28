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

// Kiosk API Endpoints
$app->get('checkout', 'ApiController@checkout');
$app->get('checkin', 'ApiController@checkin');

// User Endpoints
$app->get('user/checked_out_items', 'UserController@checkedOutItems');

// Store Endpoints
$app->get('store/users', 'StoreController@users');
