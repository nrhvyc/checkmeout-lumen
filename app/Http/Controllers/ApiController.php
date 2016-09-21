<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Item;
use App\Reservation;

class ApiController extends Controller
{
    public function checkout(Request $request) {
        $uid = $request->input('UID');
        $item_tags = json_decode($request->input('items'));

        $items_saved = 0;
        $items_failed = 0;
        
        $user_id = User::where('card_id', $uid)->first();
        
        $datetime = date("Y-m-d H:i:s");
        
        // Only if user exist for card id
        if ($user_id) {
            foreach ($item_tags as $item_tag) {
                $item_id = Item::where('tag_id', $item_tag)->first();

                // Create Reservation if item exist
                if ($item_id) {
                    Reservation::create(['user_id' => $user_id,
                                         'item_id' => $item_id,
                                         'checkout_time' => $datetime]);
                    $items_saved++;
                }
                else {
                    $items_failed++;
                }
            }
            $status = 'success';
        }
        else{
            $status = 'failed';
        }

        $response = ['status' => $status,
                     'items_saved' => $items_saved,
                     'items_failed' => $items_failed];

        return response()->json($response);
    }

    public function checkin(Request $request) {
        
    }
}
