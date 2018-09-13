<?php

namespace App\Http\Controllers;

use App\Business;
use App\Review;
use App\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    private function create(Request $request, $businessId)
    {
        $this->validate($request,[
            'body' => 'required|'.ALPHANUMERIC_DASH_SPACE_DOT_REGEX
        ]);
        $body           = $request->get('body');
        $userId         = Auth::id();
        $hasReview      = (new Review())->where('business_id', $businessId)->where('user_id', $userId)->first();
        $reviewerHasSubscribed = (new Subscription())->where('business_id',$businessId)->where('user_id', $userId)->first();
        if($hasReview || !$reviewerHasSubscribed) {
            return 0;
        } else {
            $review     = new Review();
            $review->name           = authedUserFullName();
            $review->body           = $body;
            $review->user_id        = Auth::id();
            $review->business_id    = $businessId;
            $review->save();

            return 1;
        }

    }

    private function delete(Request $request, $reviewId)
    {
        $review = Review::find($reviewId);

        if($review) {
            $review->delete();
            return 1;
        } else {
            return 0;
        }
    }

    public function addReview(Request $request, $businessId) {
        if(!$this->create($request, $businessId)) {
            return redirect()->back()->with('errorMessage', "You're not authorized to make this request.");
        } else {
            return redirect()->back()->with('successMessage', 'Your review was posted successfully');
        }
    }

    public function deleteReview(Request $request, $reviewId) {
        if(!$this->delete($request, $reviewId)) {
            return back()->with('errorMessage', 'There was a problem with your request. Please try again later');
        } else {
            return back()->with('successMessage', 'Your review was deleted successfully');
        }
    }
}
