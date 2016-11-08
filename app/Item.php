<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'checkout_duration', 'img_url', 'description', 'store_id', 'tag_id', 'type'
    ];

    //Relationships
    public function store() {
        return $this->belongsTo('App\Store');
    }

    public function reservations() {
        return $this->hasMany('App\Reservation');
    }
}
