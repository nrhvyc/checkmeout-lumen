<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Store;
use App\Item;
use App\Reservation;

class StoreController extends Controller
{
    use RestControllerTrait;
    const MODEL = 'App\Store';
    protected $validationRules = [];

    /**
     * Return users for a specific store id
     *
     * @return JSON
     */
    public function users(Request $request) {
        // Grab Data From Request
        $store_id = $request->input('store_id');  // User ID
        
        // Retrieve Store
        $store = Store::where('id', $store_id)->first();
        
        // Validate Store Exist
        if ($store) {
            $users = $store->users;
            dd($users);

            $status = 'success';
        }
        else {
            $status = 'failed: user does not exist';
        }

        $response = ['status' => $status,
                     'items' => $items];

        return response()->json($response);
    }
}