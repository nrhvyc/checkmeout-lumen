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
            $items = Reservation::where('user_id', $user->id)
                                ->where('checkin_time', NULL)
                                ->get();

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
            // Retrieve Items For User Not Checked In
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
}
