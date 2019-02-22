<?php

namespace App\Http\Controllers;

use App\Location;
use App\Notification;
use App\Photo;
use App\PhotoClient\AWSPhoto;
use App\Plan;
use App\Rating;
use App\Review;
use App\S3FolderTypes;
use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use Dompdf\Exception;
use Elasticsearch\Client;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use App\Business;
use App\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Stripe\Stripe;
use Stripe\Subscription;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class BusinessController extends Controller
{
    public function __construct(Client $esClient)
    {
        $this->middleware('auth')->except(['getStore','viewStore','viewService', 'contact', 'about']);
        $this->photoClient = new AWSPhoto();
        $this->es = $esClient;
    }
    private $es;
    private $failMessage = "The business you requested does not exist";
    private $businessPhotoPath = 'public/images/business';
    private $businessLogoPath = 'public/images/business/logos';
    private $photoClient;
    private $validationRules = [
        'name'            => 'required|'.ALPHANUMERIC_DASH_SPACE_REGEX,
        'business_handle' => 'required|'.HANDLE, // needs to be digits and underscores only also no spaces
        'email'           => 'required|email',
        'phone'           => 'nullable|numeric',
        'description'     => 'required',
        'redirect_to'     => 'nullable|url',

    ];

    private $updateValidationRules = [
        'email'           => 'required|email',
        'phone'           => 'nullable|numeric',
        'description'     => 'required',
        'redirect_to'     => 'nullable|url',
    ];

    public function index()
    {
        $business = Business::where('user_id', Auth::id())->first();
        if(!$business) {
            return redirect('/business/manageBusiness');
        } else {
            $subs = \App\Subscription::where('business_id', $business->id)->get();
            $projectedMonthlyIncome = $this->calulateMonthlyIncome($subs);
            $data = [
              'businessId'            => $business->id,
              'business_handle'       => $business->business_handle,
              'subscriptionCount'     => $subs->count(),
              'name'                  => ucfirst(Auth::user()->first),
              'projectedMonthlyIncome'=> formatPrice($projectedMonthlyIncome)
            ];

            return view('business.business-home')->with('data', $data);
        }

    }

    public function viewStore(Request $request, $id) {
        $business   = Business::find($id);
        noEntityAbort($business, 404);
        $owner      = Auth::check()  ? $business->user->id == Auth::id() : false;
        $hasPhoto   = !empty($business->photo_path);
        $haslogo    = !empty($business->logo_path);
        $guest              = !Auth::check();
        return view('themes.base-theme.store-front')
            ->with('business',$business)
            ->with('hasPhoto',$hasPhoto)
            ->with('haslogo',$haslogo)
            ->with('showCarousel',false)
            ->with('photoActive',0)
            ->with('guest',$guest)
            ->with('active',"home")
            ->with('owner',$owner);
    }

    public function getStore(Request $request, $businessHandle) {
        $business = Business::where('business_handle', $businessHandle)->first();
        if($business) {
            return $this->viewStore($request, $business->id);
        } else {
            abort(404,"The store you're looking for does not exist");
        }
    }

    public function about(Request $request, $id) {
        $business   = Business::find($id);
        noEntityAbort($business, 404);
        $owner      = Auth::check()  ? $business->user->id == Auth::id() : false;
        $hasPhoto   = !empty($business->photo_path);
        $haslogo    = !empty($business->logo_path);
        $guest              = !Auth::check();
        return view('themes.base-theme.about')
            ->with('business',$business)
            ->with('hasPhoto',$hasPhoto)
            ->with('haslogo',$haslogo)
            ->with('guest',$guest)
            ->with('active',"about")
            ->with('owner',$owner);
    }

    public function contact(Request $request, $id) {

        $business   = Business::find($id);
        noEntityAbort($business, 404);
        $owner      = Auth::check() ? $business->user->id == Auth::id() : false;
        $hasPhoto   = !empty($business->photo_path);
        $haslogo    = !empty($business->logo_path);
        $guest              = !Auth::check();
        return view('themes.base-theme.contact')
            ->with('business',$business)
            ->with('hasPhoto',$hasPhoto)
            ->with('haslogo',$haslogo)
            ->with('guest',$guest)
            ->with('active',"contact")
            ->with('owner',$owner);
    }


    /**
     * @param Request $request
     * @param $planId
     * @return mixed
     * todo: this should
     */
    public function viewService(Request $request,$planId) {
        $plan               = Plan::find($planId);
        noEntityAbort($plan, 404);
        $business           = $plan->business;
        noEntityAbort($business, 404);
        $hasPhoto           = !empty($business->photo_path);
        $haslogo            = !empty($business->logo_path);
        $alreadySubscribed  = Auth::check() ? (new \App\Subscription())->where('user_id', Auth::id())->where('plan_id', $planId)->exists() : false;
        $owner              = Auth::check() ? $business->user_id == Auth::id() : false;
        $publicStripeKey    = getPublicStripeKey();
        $rating             = (new Rating())->where('plan_id', $planId)->avg('rate_number');
        $reviews            = (new Review())->where('business_id', $business->id)->orderBy('id','desc')->get();
        $canReview          = Auth::check() ? !(new Review())->where('business_id', $business->id)->where('user_id', Auth::id())->first() && (new \App\Subscription())->where('plan_id', $planId)->where('user_id', Auth::id())->first() : false;
        $guest              = !Auth::check();
        return view('themes.base-theme.service')
            ->with('hasPhoto',$hasPhoto)
            ->with('haslogo',$haslogo)
            ->with('business',$business)
            ->with('canReview',$canReview)
            ->with('reviews',$reviews)
            ->with('rating',$rating)
            ->with('guest',$guest)
            ->with('alreadySubscribed',$alreadySubscribed)
            ->with('active','')
            ->with('publicStripeKey',$publicStripeKey)
            ->with('plan',$plan)->with('owner',$owner);
    }

    public function signup()
    {
        /** @var Builder $allBusinessPlans */
        $businessAccountPlans = DB::table('plans')->where('is_app_plan', "1")->get();
        $publicStripeKey = getPublicStripeKey();

        return view('business.signup')->with('plans', $businessAccountPlans->toArray())->with('publicStripeKey',$publicStripeKey);
    }

    public function manageBusiness(Request $request = null)
    {
        $days = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
        $business = getAuthedBusiness();
        return view('business.manage-business')
            ->with('business', $business)
            ->with('days', $days);
    }

    public function updateBusinessPhoto(Request $request, $businessId) // request doesn't need id
    {
        if(!empty($request)) {
            $business = getAuthedBusiness();
            noEntityAbort($business, 404);
            if($business->photo_path) {
                $this->photoClient->unlink($business->photo_path);
            }
            $file = $request->file('file');
            $path = $this->photoClient->store($file, S3FolderTypes::BUSINESS_PHOTO);
            $business->photo_path = $path;
            $business->save();

            return redirect('/business/manageBusiness')->with('successMessage', "Photo uploaded successfully");
        } else {
            return redirect('/business/manageBusiness')->with('errorMessage', "request is empty");
        }
    }

    public function updateBusinessLogo(Request $request, $businessId)
    {
        try {
            if (!empty($request)) {
                $business = getAuthedBusiness();
                noEntityAbort($business, 403);
                if ($business->logo_path) {
                    $this->photoClient->unlink($business->logo_path);
                }
                $file = $request->file('file');
                $path = $this->photoClient->store($file, S3FolderTypes::BUSINESS_PHOTO);
                $business->logo_path = $path;
                $business->save();

                return 1;
            } else {
                return 0;
            }
        } catch (Exception $e) {
            logException($e);
            return 0;
        }
    }

    public function deleteBusinessPhoto(Request $request, $businessId)
    {
        if(!empty($request)) {
            $business = getAuthedBusiness();
            noEntityAbort($business, 403);
            $this->photoClient->unlink($business->photo_path);
            $business->photo_path = null;
            $business->save();

            return redirect('/business/manageBusiness')->with('infoMessage', "Photo deleted successfully");
        } else {
            return redirect('/business/manageBusiness')->with('errorMessage', "request is empty");
        }
    }

    public function deleteBusinessLogo(Request $request, $businessId)
    {
        if(!empty($request)) {
            $business = getAuthedBusiness();
            noEntityAbort($business, 403);
            $this->photoClient->unlink($business->logo_path);
            $business->logo_path = null;
            $business->save();

            return redirect('/business/manageBusiness')->with('infoMessage', "Logo deleted successfully");
        } else {
            return redirect('/business/manageBusiness')->with('errorMessage', "request is empty");
        }
    }

    public function storePhoto(Request $request)
    {
        $path = $request->file('avatar')->store('avatars');

        return $path;
    }

    public function updateApiKey(Request $request, $businessId)
    {
        $business = getAuthedBusiness();
        noEntityAbort($business, 403);
        $business->api_key = $this->generateApiKey($business->id);
        $business->save();
        return redirect()->back()->with('successMessage',"Your API key was updated successfully");
    }


    public function createBusiness(Request $request) // maybe an are you sure button? some info will not be editable for customer protection
    {
        $this->validate($request,$this->validationRules);

        /** @var User $user */
        $user = Auth::user();
            try {

                $request = $this->formatRedirectToField($request);
                $newBusiness = new Business($request->all());
                $newBusiness->user_id = Auth::id();
                $newBusiness->api_key  = $this->generateApiKey($newBusiness->id);
                $newBusiness->active = "1";
                $newBusiness->save();
                $user->business_id = $newBusiness->id;
                $user->save();
                $notification = new Notification();
                $notification->sendBusinessWelcomeNotification($user, $newBusiness);
                $message = "Business creation was successful";

                // send email to business
            } catch (Exception $e) {
                $message = $e->getMessage();
            }

        return redirect('/business')->with('successMessage', $message);
    }

    public function updateBusiness(Request $request, $id)
    {
        $this->validate($request,$this->updateValidationRules);
        $updatePlans = false;
        /** @var User $user */
        $user = Auth::user();
        $business = getAuthedBusiness();
//        noEntityAbort($business, 403);
        if($user->business_account == 1)
        {
            $request = $this->formatRedirectToField($request);
            if($request->get('lat') != $business->lat) {
                $updatePlans = true;
            }
            $business->update($request->all());
            $subscriptions = \App\Subscription::where('business_id', $id)->get();
            $plans = \App\Plan::where('business_id', $id)->get();
            $notification         = new Notification();
            foreach($subscriptions as $subscription)
            {
                $notification->sendNotifyBusinessModificationNotification($business, $subscription);
            }

            if ($updatePlans) {
                foreach ($plans as $plan) {
                    (new PlanController($this->es))->updateEsIndex($plan, $this->es);
                }
            }

            return redirect('/business/manageBusiness')->with('successMessage','Business details updated successfully');
        }

        return redirect('/business/manageBusiness')->with('warningMessage','Business does not exist or is inactive');
    }

    public function showBusinessNotificationView(){
        $business = getAuthedBusiness();
        noEntityAbort($business, 403);
        $notifications = (new Notification())->getBusinessNotifications($business->id);
        // maybe also get common
        return view('business.business-notifications')->with('notifications', $notifications);
    }

    public function showSubscribers(){
        $business = getAuthedBusiness();
        noEntityAbort($business, 403);
        $subscribers = DB::table('users')->join('subscriptions','users.id','=','subscriptions.user_id')->whereIn('users.id', (new \App\Subscription())->where('business_id', $business->id)->pluck('user_id'))->get();
        // maybe also get common
//        var_dump($subscribers); die();
        return view('business.active-subscribers')->with('subscribers', $subscribers);
    }


    public function showNotifyCustomersView(){
        $business = getAuthedBusiness();
        noEntityAbort($business, 403);
        if(!$business) {
            return redirect('/business')->with('warningMessage', "You are not authorized to make this request");
        }
        // maybe also get common
        return view('business.notify-customers')->with('business', $business);
    }

    public function notifyCustomers(Request $request){
        $this->validate($request,[
            'subject'   => 'required',
            'body'      => 'required'
        ]);
        $business = getAuthedBusiness();
        noEntityAbort($business, 403);
        if(!$business) {
            return redirect('/business')->with('warningMessage', "You are not authorized to make this request");
        }
        $data = [];
        $data['body']       = $request->get('body');
        $data['subject']    = $request->get('subject');
        // ToDo: this will later need to be revised with a proper groupBy(). Could not use groupby because of SQL setting
        $subscriptions      = \App\Subscription::where('business_id', $business->id)->get();
        $notification       = new Notification();
        if(count($subscriptions)) {
            foreach ($subscriptions as $subscription) {
                $notification->sendMessageToCustomersNotification($business, $subscription, $data);
            }
        } else {
            return redirect('/business')->with('infoMessage', "You do not have any subscribers yet");
        }
        return redirect('/business')->with('successMessage', "Your message was sent successfully");
    }

    public function deleteBusiness(Request $request, $businessId, $userDelete = null)
    {
        $this->validate($request, [
            'email' => 'required|email'
        ]);

        setStripeApiKey('secret');
        $user = (new User())->find(Auth::id());

        if($user->email !== $request->email) {
            return redirect()->back()->with("errorMessage","Not authorized to make this request");
        }

        $business = getAuthedBusiness();
        noEntityAbort($business, 403);


        if(!$business) {
            return redirect()->back()->with("errorMessage","Business doesn't exist");
        }

        $sentToUser = [];
        $subs = (new \App\Subscription())->where('business_id', $businessId)->get(); // delete all the subscriptions
        $notification         = new Notification();
        $data = [];
        if(count($subs) > 0) {
            foreach($subs as $sub)
            {
//                try {
//                    $data['refundStatus'] = \App\Subscription::getRefundStatusAndAmount($sub);
//                    Subscription::retrieve($sub->stripe_id)->cancel();
//                } catch (Exception $e) {
//                    logger('subscription cancellation failed');
//                }
                if(!isset($sentToUser[$sub->user_id])) // only send out one broad email
                {
                    $sentToUser[$sub->user_id] = 1;
                    // send email
                }

                try {
                    $notification->sendNotifyBusinessDeletionNotification($business, $sub, $data);
                } catch (\Exception $e) {
                    logException($e);
                }
                $sub->delete(); // delete all photos assoc with plans
            }
        }

        $plans = $business->plans();

        if(count($plans)) {
            foreach($plans as $plan)
            {

                $photos = (new Photo())->where('plan_id', $plan->id)->get();
                if($photos)
                {
                    foreach($photos as $photo)
                    {
                        try {
                            $this->photoClient->unlink($photo->path);
                        } catch (Exception $e) {
                            logger('plan photo deletion failed');
                        }
                        $photo->delete(); // delete all photos assoc with plans
                    }
                }
                $planId = $plan->id;
                try {
                    $plan->delete(); // delete plan
                } catch (Exception $e) {
                    logger("Could not delete plan: $planId");
                }
            }

        }

        try {
            if ($business->photo_path) {
                $this->photoClient->unlink($business->photo_path);
            }
            if ($business->logo_path) {
                $this->photoClient->unlink($business->logo_path);
            }
        } catch (Exception $e) {
            logger('business photos deletion failed');
        }
        // delete the business subscription first
//        $localSubscription = (new \App\Subscription())->find($user->subscription_id);
//        try {
//            Subscription::retrieve($localSubscription->stripe_id)->cancel();
        try {
            $business->delete(); //  delete business
        } catch (Exception $e) {
            logger("Could not delete business $businessId");
        }
//        } catch (Exception $e) {
//            if ($userDelete) {
//                return redirect('/account/delete')->with('warningMessage', "Please try again, business was not deleted");
//            } else {
//                return redirect('/business')->with('warningMessage', "Please try again, business was not deleted");
//            }
//        }
//        $user->business_account = "0";
//        $user->business_account_plan = null;
        $user->business_id = null;
        $user->subscription_id = null;
        $user->save();

        if($userDelete) {
            return true;
        } else {
            return redirect('/account')->with('successMessage',"Your business was deleted successfully");
        }

    }

    public function deactivateBusiness($id)
    {
        $business = getAuthedBusiness();
        if ($business)
        {
            $business->active = "0";
            $business->save();
            return $business;
        } else {
            abort(403);
        }

    }

    public function activateBusiness($id)
    {
        $business = $this->findBusiness($id);
        if ($business)
        {
            $business->active = "1";
            $business->save();
            return $business;
        } else {
            abort(403);
        }

    }

    public function suspendBusiness($id)
    {
        $business = $this->findBusiness($id);
        if ($business)
        {
            $business->active = "2";
            $business->save();
            return $business;
        } else {
            abort(403);
        }

    }

    private function getBusinessAccountStatsQuery(){
        $userId = Auth::id();
        $query = DB::raw("SELECT 
                                (
                                SELECT COUNT(s.id) FROM subscriptions s join businesses b  WHERE b.user_id = $userId and b.id = s.business_id
                                ) AS subCount
                                
                                FROM businesses;
                                ");

        return $query;

    }

    public function calulateMonthlyIncome($subs)
    {
        $income = 0;

        if(count($subs)) {
            foreach ($subs as $sub)
            {
                if($sub->o_interval == 'year')
                {
                    $income += ($sub->price/12);
                } else {
                    $income += $sub->price;
                }
            }
        }

        return subtractStripeFees($income);
    }

    public function showCheckinView() {

        $business = getAuthedBusiness();
        noEntityAbort($business, 403);
        $checkins = \App\Subscription::where('business_id',$business->id)->where('is_checking_in', 1)->get();
        return view('business.checkins')->with('checkins', $checkins);
    }

    public function businessNotificationView(){
        $business = getAuthedBusiness();
        noEntityAbort($business, 403);
        $businessEmail = (new Business())->where('id', $business->id)->value('email');
        $notifications = (new Notification())->getNotifications('business', $businessEmail, $business->id);
        return view('business.business-notifications')->with('notifications', $notifications);
    }

    public function showCancelAccountView() {
        $business = getAuthedBusiness();
        noEntityAbort($business, 403);
        return view('business.cancel-account')->with('businessId', $business->id);
    }

    public function updateRedirectTo(Request $request) {
        try {
            $request = $this->formatRedirectToField($request);

            $this->validate($request,[
                'redirect_to' => 'required|url'
            ]);

            $business = getAuthedBusiness();
            noEntityAbort($business, 403);
            $business->redirect_to = $request->get('redirect_to');
            $business->save();
            return Response::create("Your URL was saved successfully", 201);
        } catch (Exception $e) {
            return Response::create("Please enter a valid url", 400);
        }
    }




    private function getUserObject()
    {
        return new User;
    }

    private function getBusinessObject()
    {
        return new Business;
    }

    public function findBusiness($id)
    {
        return $this->getBusinessObject()->find($id);
    }

    public function findUser($id)
    {
        return $this->getUserObject()->find($id);
    }

    private function verifyBusinessToUser($business) {
        return $business->user_id == Auth::id();
    }

    private function generateApiKey($id) {
        return sprintf('%s-%s-%s', uniqid("OV"),time()+rand(1,300),md5($id));
    }

    /**
     * This method is to ensure that all of the urls we get start with https://
     * @param Request $request
     *
     */
    private function formatRedirectToField(Request $request) {
        if($request->has('redirect_to') && !empty($request->get('redirect_to'))) {
            $url = str_replace("http://", "", $request->get('redirect_to'));
            if(strpos($url, "https://") === false) {
                $request->replace(['redirect_to' => "https://".$url]);
            }
        }
        return $request;
    }

    public function checkHandleAvailability(Request $request) {
        $handle = $request->get('choose_business_handle');
        if( Business::where('business_handle',$handle)->first() ) {
            echo 0; // not available
        } else {
            echo 1;
        }
    }

}
