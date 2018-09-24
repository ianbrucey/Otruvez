<?php

namespace App\Http\Controllers;

use App\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    private function create(Request $request, $planId)
    {
        $rating = (new Rating())->where('user_id', Auth::id())->where('plan_id', $planId)->first();
        if(!$rating)
        {
            $rating = new Rating();
            $rating->rate_number = $request->get('rate_number');
            $rating->plan_id     = $planId;
            $rating->user_id     = Auth::id();
            $rating->save();

            return 1;
        } else {
            $rating->rate_number = $request->get('rate_number');
            $rating->save();
            return 0;
        }
    }

    public function rateService(Request $request, $planId)
    {
        $this->validate($request, [
            'rate_number' => 'required|integer'
        ]);
        if($this->create($request, $planId)) {
            return redirect()->back()->with("successMessage","You gave them " . $request->rate_number . " stars. Thanks for your input");
        } else {
            return redirect()->back()->with("successMessage", "You changed your rating to " . $request->rate_number . " stars. Thanks for your input");
        }
    }

}
