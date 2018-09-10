<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Cashier\Billable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, Billable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first',
        'last',
        'business_account',
        'business_account_plan',
        'activation_token',
        'business_id',
        'location_id',
        'subscription_id',
        'email',
        'activated',
        'stripe_id',
        'card_brand',
        'card_last_four',
        'trial_ends_at',
        'password',
        'provider',
        'provider_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function business() {
        return $this->hasOne('App\Business');
    }

    public function paymentAccount() {
        return $this->hasOne('App\PaymentAccount');
    }

    public function subscriptions() {
        return $this->hasMany('App\Subscription');
    }

    public function checkIns() {
        return $this->hasMany('App\CheckIn');
    }

    public function location() {
        return $this->belongsTo('App\Location');
    }

}
