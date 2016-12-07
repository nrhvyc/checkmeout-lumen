<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'name', 'img_url', 'location'
    ];

    //Relationships
    public function items() {
        return $this->hasMany('App\Item');
    }

    public function users() {
        return $this->belongsToMany('App\User');
    }
}
