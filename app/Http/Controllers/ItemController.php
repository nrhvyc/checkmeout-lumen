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
        /* jk not gonna use this right now
        // Because of CheckGoogleOAuth Middleware id_token exist and is valid.
        // This is hacky but will have to work for now.
        $user = User::where('id_token', $request->input('id_token'));
        */
        /*
        if (!$request->has('user_id')) {
            return response('Bad request.', 400);
        }

        // Get the user
        $user_id = $request->input('user_id');
        $user = User::where('id', $user_id)->first();
        */
        if (!$request->has('item_name')) {
            return response('Bad Request.', 400);
        }

        $item_name = $request->input('item_name');
        
        $items = Item::where('name', 'LIKE', '%'. $item_name .'%')
            ->orderBy('name', 'desc')->get();
        
        foreach ($items as $item) {
            $item = $item->store;
        }
        
        $response = [
          'code' => 200,
          'status' => 'Successful',
          'data' => ['items' => $items]
        ];

        return response()->json($response);

    }
}
