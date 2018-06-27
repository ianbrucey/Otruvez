<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Business extends Model
{

    protected $fillable = [
        'user_id',
        'name',
        'industry',
        'description',
        'address',
        'city',
        'state',
        'zip',
        'lat',
        'lng',
        'email',
        'phone',
        'active',
        'photo_logo',
        'logo_path',
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday',
        'saturday',
        'sunday',
        'theme_color',
    ];

    public function user() {
        return $this->hasOne('App\User');
    }

    public function subscriptions() {

        return $this->hasMany('App\Subscription');
    }

    public function plans() {

        return $this->hasMany('App\Plan')->with(['photos' => function($query) {
                        $query->where('user_id', Auth::id());
                    }])->get();
    }

    public function plansDescending() {

        return $this->hasMany('App\Plan')->orderBy('id','desc');
    }

    public function checkIns() {
        return $this->hasMany('App\CheckIn');
    }

}

