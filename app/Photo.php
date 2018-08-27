<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $fillable = [
        'plan_id','user_id','type','path', 'updated_at', 'created_at'
    ];

    public function plan()
    {
        return $this->belongsTo('App\Plan');
    }
}
