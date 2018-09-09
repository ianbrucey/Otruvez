<?php

namespace App\Http\Controllers;

use App\Business;
use App\Email;
use App\Http\Requests\GalleryUploadRequest;
use App\Notification;
use App\Photo;
use App\Plan;
use App\Rating;
use App\S3FolderTypes;
use App\Subscription;
use App\User;
use Elasticsearch\Common\Exceptions\BadRequest400Exception;
use Exception;
use Faker\Provider\cs_CZ\DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Elasticsearch\Client;
use App\PhotoClient\AWSPhoto;
use Stripe\Stripe;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PlanController extends Controller
{

    const STANDARD_CURRENCY = 'usd';

    const PHOTO_TYPE = 'plan';

    const MAX_GALLERY_COUNT = 4;

    private $esClient;

    private $photoClient;

    public function __construct(Client $esClient)
    {
        $this->middleware('auth');
        $this->esClient = $esClient;
        $this->photoClient = new AWSPhoto();
    }

    private function getSecretStripeKey()
    {
        return config('services.stripe.secret');
    }


    public function showChooseAccountForm()
    {
        $appPlans = Plan::where('is_app_plan',"1")->get();
        return view('plan.chooseAccountPlan')->with('appPlans', $appPlans);
    }

    public function managePlans()
    {

        $plans = Auth::user()->business->plansDescending;
        return view('plan.manage-plans')
            ->with('plans',$plans)
            ->with('maxGalleryCount',self::MAX_GALLERY_COUNT);
    }


    public function storeAppPlansLocally() {

        /** @var \Stripe\Plan $plan */
            $query = Plan::insert([
                'stripe_plan_id' => 'sm_standard',
                'stripe_plan_name' => 'Standard Plan',
                'year_price' => 10000,
                'month_price' => 100,
                'o_interval' => null,
                'use_limit' => "0",
                'is_app_plan' => "1",
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]);

        return;
    }

    public function createServicePlan(Request $request)
    {

        $galleryPhotos = $request->file('gallery_photos');
        $featuredPhoto = $request->file('featured_photo');

        $this->validate($request, [
            'stripe_plan_name'  => 'required',
            'description'       => 'required',
            'featured_photo'    => 'required|image',
            'gallery_photos.*'  => 'nullable|image'
        ]);

        setStripeApiKey('secret');
        $es = $this->esClient;

        $planName             = $request->stripe_plan_name;
        $businessId           = Auth::user()->business->id;
        $planIdentifier       = uniqid(sprintf("%u_%u",$businessId,Auth::id()));
        $useLimitMonth        = abs($request->use_limit_month);
        $useLimitYear         = abs($request->use_limit_year);
        $limitInterval        = $useLimitMonth ? 'month' : $useLimitYear ? 'year' : null;
        $monthPrice           = $request->month_price * 100;
        $yearPrice            = $request->year_price * 100;
        $description          = $request->description;
        $intervals            = ['month','year'];


        // CHECK FOR EXISTING PLAN
        $exists = Plan::where('business_id',$businessId)->where('stripe_plan_id',$planIdentifier)->count();

        if($exists)
        {
            return redirect('/plan/managePlans')->with('warningMessage',"This plan already exists");
        }


        /**
         * NOTE: We need to validate the request before we create the stripe plans
         */
        foreach($intervals as $interval)
        {
            \Stripe\Plan::create(array(
                "name"      => sprintf("%s %s",$planName,$interval),
                "id"        => sprintf('%s_%s',$planIdentifier,$interval), // makes it unique in stripes DB
                "interval"  => $interval,
                "currency"  => self::STANDARD_CURRENCY,
                "amount"    => $interval == 'month' ? $monthPrice ?: 0 : $yearPrice ?: 0
            ));
        }

        $plan = Plan::create([
            'user_id'           => Auth::id(),
            'business_id'       => $businessId,
            'stripe_plan_id'    => $planIdentifier,
            'stripe_plan_name'  => $planName,
            'month_price'       => $monthPrice,
            'year_price'        => $yearPrice,
            'use_limit_month'   => $useLimitMonth,
            'use_limit_year'    => $useLimitYear,
            'limit_interval'    => $limitInterval,
            'description'       => $description,
            'featured_photo_path' => null,
        ]);

        $msg = 'Service created successfully! ';
        try {
            $this->updateFeaturedPhoto($request, $plan->id, $featuredPhoto);

            foreach ($galleryPhotos as $file) {
                $this->updateGalleryPhotos($request, $plan->id, $file);
            }
        } catch (Exception $e) {
            $msg .= " Warning: there was a problem with one or more of your uploads";
        }

        $this->updateEsIndex($plan, $es);

        return redirect('/plan/managePlans')->with('successMessage',$msg);

    }

    public function updateFeaturedPhoto(Request $request, $id, $file = null)
    {
        if(!empty($request)) {

            $this->validate($request, [
                'featured_photo'    => 'required|image'
            ]);

            $photo = $file ?: $request->file('featured_photo');
            $path = $this->photoClient->store($photo, S3FolderTypes::PLAN_FEATURED_PHOTO);
            try {
                $plan = Plan::where('user_id', Auth::id())->where('id',$id)->first();
                if ($plan->featured_photo_path) {
                    $this->photoClient->unlink(getFullPathToImage($plan->featured_photo_path));
                }
                $plan->featured_photo_path = $path;
                $plan->save();
            } catch (Exception $e) {
                $this->photoClient->unlink($path);
                return Response::create([
                    'msg' => sprintf("Upload failed: %s", $e->getMessage())
                ], 400);
            }

            return Response::create([
                'msg' => sprintf("Upload successful")
            ], 200);
        }

        return Response::create([
            'msg' => sprintf("Upload failed: Request is empty")
        ], 400);
    }

    public function updateGalleryPhotos(Request $request, $id, $file = null) {

        $plan = Plan::find($id);
        $galleryCount = count($plan->photos);
        if($galleryCount >= self::MAX_GALLERY_COUNT) {
            return Response::create([
                'msg'   => 'Max uploads exceeded. Please remove a photo to add more '
            ], 400);
        }

        if(!$file) {
            $this->validate($request, [
                'gallery_photos'  => 'image'
            ]);

            $photo = $request->file('gallery_photos');
        } else {
            // in this case, its coming from createService and has already been validated
            $photo = $file;
        }



        try {
                $path = $this->photoClient->store($photo, S3FolderTypes::PLAN_GALLERY_PHOTO);

                $newPhoto = Photo::create([
                    'plan_id'   => $plan->id,
                    'user_id'   => Auth::id(),
                    'type'      => self::PHOTO_TYPE,
                    'path'      => $path
                ]);

                return Response::create([
                    'path'          => getImage($path),
                    'deleteRoute'   => sprintf("/plan/galleryPhoto/%s",$newPhoto->id),
                    'msg'           => 'upload successful'
                ], 200);

        } catch (Exception $e) {
            $this->photoClient->unlink($path);
            return Response::create([
                'msg'   => 'upload not successful: ' . $e->getMessage()
            ], 400);
        }

    }

    public function updatePlan(Request $request, $id)
    {
        $smPlan = Plan::find($id);

        if($smPlan && $smPlan->user_id != Auth::id()) {
            return redirect("/plan/managePlans")->with('errorMessage','YOU ARE NOT AUTHORIZED TO DO THIS! PLEASE DON\'T!');
        }

        $business   = $smPlan->business;
        $data       = [
            'oldName'           => $smPlan->stripe_plan_name,
            'oldDescription'    => $smPlan->description,
        ];

        try {
            $this->updateEsIndex($smPlan, $this->esClient);
            // may need to update the use limits
            $smPlan->stripe_plan_name   = $request->stripe_plan_name;
            $smPlan->description        = $request->description;
            $smPlan->save();
            $subscriptions              = Subscription::where('plan_id', $id)->get();
            $notification               = new Notification();
            if($subscriptions) {
                foreach ($subscriptions as $subscription) {
                    $data['subscription'] = $subscription;
                    $notification->sendNotifyPlanModificationNotification($business, $subscription, $data);
                }
            }

        } catch (Exception $exception) {
            return redirect("/plan/managePlans")->with('successMessage','Could not update' . " $exception");
        }

        return redirect("/plan/managePlans")->with('successMessage','Plan updated successfully!');
    }

    public function deletePlan(Request $request, $id)
    {
        // in the future, i'd like to obfuscate the plan id to prevent data mining
        $smPlan = Plan::find($id);
        $business = $smPlan->business;
        $planName = $smPlan->stripe_plan_name;
        $subscriptions = Subscription::where('plan_id', $id)->get();
        $refundStatus =
        $notification         = new Notification();
        if($subscriptions) {
            foreach ($subscriptions as $subscription) {
                // need to calculate REFUND, maybe if create date is within "X"
                Notification::where('subscription_id', $subscription->id)->delete();
                $data = [
                    'subscription'  => $subscription,
                    'plan'          => $smPlan,
                    'business'      => $business,
                    'refund'        => getRefundStatusAndAmount($subscription)
                ];
                $data['refundStatus'] = Subscription::getRefundStatusAndAmount($subscription);
                $notification->sendNotifyPlanDeletionNotification($business, $subscription, $data);

            }
        }




        if($smPlan && $smPlan->user_id != Auth::id()) {
            return redirect("/plan/managePlans")->with('errorMessage','YOU ARE NOT AUTHORIZED TO DO THIS! PLEASE DON\'T!');
        }

        if(!$smPlan->delete())
        {
            return redirect("/plan/managePlans")->with('warningMessage',"There was a problem. Please try again");
        }

        Subscription::where('plan_id', $id)->delete();
        $planId =  $smPlan->stripe_plan_id;
        try {
            setStripeApiKey('secret');
            $plan = \Stripe\Plan::retrieve($planId . "_month");
            $plan->delete();
            $plan = \Stripe\Plan::retrieve($planId . "_year");
            $plan->delete();
        } catch (Exception $e) {
            return redirect("/plan/managePlans")->with('warningMessage',"There was a problem deleting the service because <br>it may no longer exist. please contact support with the following id if problems persist: $planId");
        }

        return redirect("/plan/managePlans")->with('infoMessage',"$planName deleted successfully");

    }



    public function deleteFeaturedPhoto(Request $request, $id)
    {
        $plan = Plan::find($id);
        $this->photoClient->unlink(getFullPathToImage($plan->featured_photo_path));
        $plan->featured_photo_path = null;
        $plan->save();

        return redirect("/plan/managePlans")->with('infoMessage',"Image removed successfully");
    }




    public function deleteGalleryPhoto(Request $request, $id)
    {
        try {
            $photo = Photo::find($id);
            $this->photoClient->unlink($photo->path);
            $photo->delete();
        } catch (Exception $e) {
            return Response::create([
                'msg'   => 'Delete unsuccessful.'
            ], 400);
        }
        return Response::create([
            'msg'           => 'image deleted successfully'
        ], 201);
    }

    public function updateEsIndex(Plan $plan, Client $es)
    {
        if($plan->business) {
            $body = $plan->toSearchArray();
            $location = ['lat' => $plan->business->lat,'lon' => $plan->business->lng];
            $body['location'] = $location;
            $body['rating'] = (new Rating())->where('plan_id', $plan->id)->avg('rate_number') ?: 0;

            $es->index([
                'index' => $plan->getSearchIndex(),
                'type' => $plan->getSearchType(),
                'id' => $plan->id,
                'body' => $body,
            ]);
        }
    }

    public function showCreateService()
    {
        return view('plan.create-service');
    }

    public function apiSetup(Request $request, $planId) {
        $plan     = (new Plan())->where('id', $planId)->first();
        $business = $plan->business;
        return view('business.online-integration')->with([
            'business' => $business,
            'plan' => $plan
        ]);
    }




}
