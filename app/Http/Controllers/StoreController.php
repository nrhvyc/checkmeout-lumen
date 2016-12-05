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
}
