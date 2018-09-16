<?php

namespace App\Http\Controllers;

use App\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LocationController extends Controller
{
    /**
     * NOT USING THIS, USING GOOGLE API
     */
    public function getLocations(Request $request) {
        $queryString = $request->location."%";
        $locations = Location::where('city', 'like',"$queryString")
            ->orWhere('zip', 'like',"$queryString")
            ->limit(15)
            ->get();
        return view('partials.location.location-list')->with('locations', $locations)->render();
    }
}
