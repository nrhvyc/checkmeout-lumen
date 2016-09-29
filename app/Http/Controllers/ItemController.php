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
}
