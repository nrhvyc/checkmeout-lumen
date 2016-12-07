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

            $status = 'success';
        }
        else {
            $status = 'failed: user does not exist';
        }

        $response = ['status' => $status,
                     'items' => $users];

        return response()->json($response);
    }

    /**
     * Adds a specified user to a store
     *
     * @return
     */
    public function addUser(Request $request) {
        if ($request->has('store_id') && $request->has('user_id')) {
            // Grab data from request
            $store_id = $request->input('store_id');
            $user_id = $request->input('user_id');

            // Retrieve Store
            $store = Store::where('id', $store_id)->first();

            // Retieve User
            $user = User::where('id', $user_id)->first();

            // NEED TO VALIDATE THE USER HAS RIGHTS TO ADD THIS
            if ($user && $store) {
                if (!$store->users()->where('user_id', $user_id)->exists()) {
                    $store->users()->attach($user_id);
                }
                $status = 'success';
            }
        } else {
            $response = [
                'code' => 400,
                'status' => 'Bad Request',
                'data' => [],
                'message' => 'token info not provided'
            ];
            return response()->json($response, $response['code']);
        }

        $response = ['status' => $status];

        return response()->json($response);
    }

    /**
     * Return items for a specific store_id
     *
     * @return JSON
     */
    public function items(Request $request) {
        // Grab Data From Request
        $store_id = $request->input('store_id');  // Store ID

        // Retrieve User
        $store = Store::where('id', $store_id)->first();

        // Validate Store Exist
        if ($store) {
            $temp_items = $store->items;
            $items = [];

            foreach($temp_items as $item) {
                $curr_item = Reservation::where('item_id', $item->id)
                                        ->orderBy('checkout_time', 'desc')
                                        ->first();
                if (!$curr_item) {
                    $status = 'success';
                    $response = ['status' => $status,
                                 'items' => []];

                    return response()->json($response);
                }
                $curr_item = $curr_item->toArray();

                $arr_item = $item->toArray();
                if ($curr_item && $curr_item['checkin_time'] == NULL) {
                    $arr_item['checked_out'] = 'true';
                } else {
                    $arr_item['checked_out'] = 'false';
                }
                array_push($items, $arr_item);
            }
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
