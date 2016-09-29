<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Store;
use App\Item;
use App\Reservation;

class ReservationController extends Controller
{
    use RestControllerTrait;
    const MODEL = 'App\Reservation';
    protected $validationRules = [];
}
