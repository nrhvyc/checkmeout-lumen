<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Store;
use App\Item;
use App\Reservation;

class ApiController extends Controller
{
    /**
     * Checkout endpoint to interface with kiosk
     *
     * @return JSON
     */
    public function checkout(Request $request) {
        // Grab Data From Request
        $sid = $request->input('SID');  // Store ID
        $uid = $request->input('UID');  // User ID
        $item_tags = json_decode($request->input('items'));

        // Initialize Status Stats
        $items_saved = [];
        $items_failed = [];

        // Retrieve User
        $user = User::where('card_id', $uid)->first();

        // Retrieve Store
        $store = Store::where('id', $sid)->first();

        // Set Current Time
        $datetime = date("Y-m-d H:i:s");

        // Validate Store Exist
        if ($store) {
            // Validate User Exist For Card ID
            if ($user) {
                foreach ($item_tags as $item_tag) {
                    // Retrieve Item
                    $item = Item::where('tag_id', $item_tag)->first();

                    // Create Reservation If Item Exist
                    if ($item) {
                        // Before Creating Reservation Check If Already
                        // Checked Out
                        $reservation = Reservation::where('user_id', $user->id)
                                                  ->where('item_id', $item->id)
                                                  ->orderBy('checkout_time', 'desc')
                                                  ->first();

                        // Insert Reservation If Item Not Checked Out Currently
                        if (!$reservation || $reservation->checkin_time != NULL) {
                            Reservation::create(['user_id' => $user->id,
                                                 'item_id' => $item->id,
                                                 'checkout_time' => $datetime]);
                            // Add Item To Succeeded Response
                            array_push($items_saved, ['item_tag' => $item_tag,
                                                      'item_name'=> $item->name]);
                        }
                        else {
                            // Add Item To Failed Response
                            array_push($items_failed, ['item_tag' => $item_tag,
                                                       'item_name'=> $item->name,
                                                       'reason' => 'item already checked out']);
                        }
                    }
                    else {
                        // Add Item To Failed Response
                        array_push($items_failed, ['item_tag' => $item_tag,
                                                   'reason' => 'item tag does not match an item']);
                    }
                }
                $status = 'success';
            }
            else {
                $status = 'failed: invalid uid';
            }
        }
        else {
            $status = 'failed: invalid sid';
        }

        $response = ['status' => $status,
                     'items_saved' => $items_saved,
                     'items_failed' => $items_failed];

        return response()->json($response);
    }

    /**
     * Checkin endpoint to interface with kiosk
     *
     * @return JSON
     */
    public function checkin(Request $request) {
        // Grab Data From Request
        $sid = $request->input('SID');  // Store ID
        $uid = $request->input('UID');  // User ID
        $item_tags = json_decode($request->input('items'));

        // Initialize Status Stats
        $items_updated = [];
        $items_failed = [];

        // Retrieve User
        $user = User::where('card_id', $uid)->first();

        // Retrieve Strore
        $store = Store::where('id', $sid)->first();

        // Set Current Time
        $datetime = date("Y-m-d H:i:s");

        // Validate Store Exist
        if ($store) {
            // Validate User Exists for Card ID
            if ($user) {
                foreach ($item_tags as $item_tag) {
                    // Retrieve Item
                    $item = Item::where('tag_id', $item_tag)->first();

                    // Update reservation if Item Exists
                    if ($item) {
                        // Before updating reservation check in time, check
                        // if actually checked out
                        $reservation = Reservation::where('user_id', $user->id)
                                                  ->where('item_id', $item->id)
                                                  ->orderBy('checkout_time', 'desc')
                                                  ->first();

                        // Update reservation check in time if currently
                        // checked Out and reservation exists
                        if ($reservation && $reservation->checkin_time == NULL) {
                            Reservation::where('user_id', $user->id)
                                       ->where('item_id', $item->id)
                                       ->update(['checkin_time' => $datetime]);
                            // Add Item To Succeeded Response
                            array_push($items_updated, ['item_tag' => $item_tag,
                                                        'item_name'=> $item->name]);
                        }
                        else {
                            // Add Item To Failed Response
                            array_push($items_failed, ['item_tag' => $item_tag,
                                                       'item_name'=> $item->name,
                                                       'reason' => 'item already checked in']);
                        }
                    }
                    else {
                        // Add Item To Failed Response
                        array_push($items_failed, ['item_tag' => $item_tag,
                                                   'reason' => 'item tag does not match an item']);
                    }
                }
                $status = 'success';
            }
            else {
                $status = 'failed: invalid uid';
            }
        }
        else {
            $status = 'failed: invalid sid';
        }

        $response = ['status' => $status,
                     'items_updated' => $items_updated,
                     'items_failed' => $items_failed];

        return response()->json($response);
    }

    /**
     * Determine whether item is currently checked out
     *
     * @return Boolean
     */
    private function isCheckedOut() {

    }
}
