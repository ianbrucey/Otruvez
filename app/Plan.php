<?php

namespace App;

use App\Search\SearchableTrait;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use SearchableTrait;

    protected $fillable = ['user_id','business_id','stripe_plan_id','stripe_plan_name','price','use_limit_month', 'use_limit_year', 'limit_interval', 'is_app_plan', 'description','featured_photo_path','month_price', 'category','year_price'];

    public function checkIns() {
        return $this->hasMany('App\CheckIn');
    }

    public function business() {
        return $this->belongsTo('App\Business');
    }

    public function photos() {
        return $this->hasMany('App\Photo');
    }

    public function ratings() {
        return $this->hasMany('App\Rating')->avg("rate_number");
    }

//    public function setIdAttribute($value)
//    {
//        $this->attributes['id'] = strtolower($value);
//    }
//
//    public function setUserIdAttribute($value)
//    {
//        $this->attributes['user_id'] = strtolower($value);
//    }
//
//    public function setBusinessIdAttribute($value)
//    {
//        $this->attributes['business_id'] = strtolower($value);
//    }
//
//    public function setStripePlanIdAttribute($value)
//    {
//        $this->attributes['stripe_plan_id'] = strtolower($value);
//    }
//
//    public function setStripePlanNameAttribute($value)
//    {
//        $this->attributes['stripe_plan_name'] = strtolower($value);
//    }
//
//    public function setPriceAttribute($value)
//    {
//        $this->attributes['price'] = strtolower($value);
//    }
//
//    public function setUseLimitAttribute($value)
//    {
//        $this->attributes['use_limit'] = strtolower($value);
//    }
//
//    public function setIsAppPlanAttribute($value)
//    {
//        $this->attributes['is_app_plan'] = strtolower($value);
//    }
//
//    public function setFirstNameAttribute($value)
//    {
//        $this->attributes['description'] = strtolower($value);
//    }
//
//    public function setFirstNameAttribute($value)
//    {
//        $this->attributes['featured_photo'] = strtolower($value);
//    }



}
