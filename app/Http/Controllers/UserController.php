<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Store;
use App\Item;
use App\Reservation;

class UserController extends Controller
{
    use RestControllerTrait;
    const MODEL = 'App\User';
    protected $validationRules = [];

    /**
     * Return items for a specific user id that are checked out
     *
     * @return JSON
     */
    public function checkedOutItems(Request $request) {
        // Grab Data From Request
        $user_id = $request->input('user_id');  // User ID

        // Retrieve User
        $user = User::where('id', $user_id)->first();

        // Validate User Exist
        if ($user) {
            // Retrieve Items For User Not Checked In
            $reservations = Reservation::where('user_id', $user->id)
                                ->where('checkin_time', NULL)
                                ->get();
            $items = [];
            foreach($reservations as $reservation) {
                array_push($items, $reservation->item);
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

    /**
     * Return all reservations for a specific user id
     *
     * @return JSON
     */
    public function reservations(Request $request) {
        // Grab Data From Request
        $user_id = $request->input('user_id');  // User ID

        // Retrieve User
        $user = User::where('id', $user_id)->first();

        // Validate User Exist
        if ($user) {
            // Retrieve Reservations For User
            $reservations = $user->reservations;
            $status = 'success';
        }
        else {
            $status = 'failed: user does not exist';
        }

        $response = ['status' => $status,
                     'reservations' => $reservations];

        return response()->json($response);
    }

    /**
     * Return all stores for a specific user id
     *
     * @return JSON
     */
    public function stores(Request $request) {
        // Grab Data From Request
        $user_id = $request->input('user_id');  // User ID

        // Retrieve User
        $user = User::where('id', $user_id)->first();

        // Validate User Exist
        if ($user) {
            // Retrieve Stores For User
            $stores = $user->stores;
            $status = 'success';
        }
        else {
            $status = 'failed: user does not exist';
        }

        $response = ['status' => $status,
                     'stores' => $stores];

        return response()->json($response);
    }

    /**
     * Return all stores owned by a specific user
     *
     * @return JSON
     */
    public function owned_stores(Request $request) {
        // Grab Data From Request
        $user_id = $request->input('user_id');  // User ID

        // Retrieve User
        $user = User::where('id', $user_id)->first();

        // Validate User Exist
        if ($user) {
            // Retrieve Stores For User
            $stores = Store::where('user_id', $user_id)->get();
            if (!$stores) {
                $response = ['status' => $status,
                             'stores' => []];

                return response()->json($response);
            }
            $status = 'success';
        }
        else {
            $status = 'failed: user does not exist';
        }

        $response = ['status' => $status,
                     'stores' => $stores];

        return response()->json($response);
    }
}
