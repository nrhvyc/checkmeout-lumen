<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Store;
use App\Item;
use App\Reservation;

class ItemController extends Controller
{
    use RestControllerTrait;
    const MODEL = 'App\Item';
    protected $validationRules = [];

    public function search(Request $request) {
        $user_id = $request->input('user_id');

        $user = User::where('id', $user_id)->first();

        if (!$request->has('item_name')) {
            return response('Bad Request.', 400);
        }

        $item_name = $request->input('item_name');

        if ($user) {
            $stores = $user->stores;
            $items = [];

            $search_items = Item::where('name', 'LIKE', '%'. $item_name .'%')
                         ->orderBy('name', 'desc')->get();

            foreach($stores as $store) {
                $temp_items = $search_items->where('store_id', $store->id);
                foreach($temp_items as $item) {
                    $curr_item = Reservation::where('item_id', $item->id)
                                            ->orderBy('checkout_time', 'desc')
                                            ->first()
                                            ->toArray();
                    $arr_item = $item->toArray();
                    if ($curr_item && $curr_item['checkin_time'] == NULL) {
                        $arr_item['checked_out'] = 'true';
                    } else {
                        $arr_item['checked_out'] = 'false';
                    }
                    array_push($items, $arr_item);
                }
            }
            $response = [
              'code' => 200,
              'status' => 'Successful',
              'data' => ['items' => $items]
            ];

            return response()->json($response);
        } else {
          $response = [
            'code' => 400,
            'status' => 'Bad Request',
            'data' => [],
            'message' => 'user not found'
          ];

          return response()->json($response);
        }
    }

    public function status(Request $request) {
        $user_id = $request->input('user_id');

        $user = User::where('id', $user_id)->first();

        if ($user) {
            $stores = $user->stores;
            $items = [];

            foreach($stores as $store) {
                $temp_items = $store->items;
                foreach($temp_items as $item) {
                    $curr_item = Reservation::where('item_id', $item->id)
                                            ->orderBy('checkout_time', 'desc')
                                            ->first()
                                            ->toArray();
                    $arr_item = $item->toArray();
                    if ($curr_item && $curr_item['checkin_time'] == NULL) {
                        $arr_item['checked_out'] = 'true';
                    } else {
                        $arr_item['checked_out'] = 'false';
                    }
                    array_push($items, $arr_item);
                }
            }
            $response = [
              'code' => 200,
              'status' => 'Successful',
              'data' => ['items' => $items]
            ];

            return response()->json($response);
        } else {
          $response = [
            'code' => 400,
            'status' => 'Bad Request',
            'data' => [],
            'message' => 'user not found'
          ];

          return response()->json($response);
        }
    }

    /**
     * Update store info
     *
     * @return JSON
     */
    public function add(Request $request) {

        $item = new Item;

        $item->name = $request->input('name');
        $item->tag_id = $request->input('tag_id');
        $item->store_id = $request->input('store_id');
        $item->img_url = $request->input('img_url');

        $item->save();

        $res = new Reservation;

        $res->user_id = NULL;
        $res->item_id = $item->id;
        $res->checkout_time = "2015-12-01 12:00:00";
        $res->checkin_time = "2015-12-01 12:00:00";

        $res->save();

        $status = 'success';

        $response = ['status' => $status];

        return response()->json($response);
    }
}
