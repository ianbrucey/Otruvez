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
use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use Elasticsearch\Common\Exceptions\BadRequest400Exception;
use Exception;
use Faker\Provider\cs_CZ\DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Elasticsearch\Client;
use App\PhotoClient\AWSPhoto;
use Intervention\Image\ImageManager;
use Stripe\Stripe;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


class PlanController extends Controller
{

    const STANDARD_CURRENCY = 'usd';

    const PHOTO_TYPE = 'plan';

    const MAX_GALLERY_COUNT = 4;

    private $esClient;

    private $photoClient;

    public function __construct(Client $esClient, Request $request)
    {
        $this->middleware('auth');
        $this->esClient = $esClient;
        $this->photoClient = new AWSPhoto();
        sanitizeRequest($request);

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
        $business = getAuthedBusiness();
        if(!$business) {
            return redirect("/business");
        }
        $plans = $business->plansDescending;
        return view('plan.manage-plans')
            ->with('plans',$plans)
            ->with('business', $business)
            ->with('maxGalleryCount',self::MAX_GALLERY_COUNT);
    }


//    public function storeAppPlansLocally() {
    // this is for charging businesses to us otruvez. probably wont use this though
//        /** @var \Stripe\Plan $plan */
//            $query = Plan::insert([
//                'stripe_plan_id' => 'sm_standard',
//                'stripe_plan_name' => 'Standard Plan',
//                'year_price' => 10000,
//                'month_price' => 100,
//                'o_interval' => null,
//                'use_limit' => "0",
//                'is_app_plan' => "1",
//                'created_at' => date("Y-m-d H:i:s"),
//                'updated_at' => date("Y-m-d H:i:s")
//            ]);

//        return;
//    }

    public function createServicePlan(Request $request)
    {
        $business = getAuthedBusiness();
        noEntityAbort($business, 403);
        $this->validate($request, [
            'stripe_plan_name'  => 'required',
            'description'       => 'required',
            'featured_photo'    => 'required|image',
            'gallery_photos.*'  => 'nullable|image',
            'use_limit_month'   => 'nullable|integer',
            'category'          => 'required|integer',
            'use_limit_year'    => 'nullable|integer',
            'month_price'       => 'nullable|integer',
            'year_price'        => 'nullable|integer'
        ]);

        $galleryPhotos = $request->file('gallery_photos');
        $featuredPhoto = $request->file('featured_photo');

        setStripeApiKey('secret');
        $es = $this->esClient;
        $planName             = $request->stripe_plan_name;
        $businessId           = $business->id;
        $planIdentifier       = uniqid(sprintf("%u_%u",$businessId,Auth::id()));
        $useLimitMonth        = abs(intval($request->get('use_limit_month')));
        $useLimitYear         = abs(intval($request->get('use_limit_year')));
        $limitInterval        = $this->getInterval($useLimitMonth, $useLimitYear);
        $monthPrice           = $request->month_price * 100;
        $yearPrice            = $request->year_price * 100;
        $description          = $request->description;
        $category             = $request->category; // this is stored as an int and the list is currently hardcoded in vendor/laravel/.../Support/helpers.php
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
        $plansCreated = [];
        foreach($intervals as $interval)
        {
            $stripeIdentifier = sprintf('%s_%s', $planIdentifier, $interval);
            try {
                \Stripe\Plan::create(array(
                    "name"      => sprintf("%s %s", $planName, $interval),
                    "id"        => $stripeIdentifier, // makes it unique in stripes DB
                    "interval"  => $interval,
                    "currency"  => self::STANDARD_CURRENCY,
                    "amount"    => $interval == 'month' ? $monthPrice ?: 0 : $yearPrice ?: 0
                ));
            } catch (Exception $e) {
                if(count($plansCreated) > 0) {
                    foreach ($plansCreated as $identifier) {
                        $stripeplan = \Stripe\Plan::retrieve($identifier);
                        $stripeplan->delete();
                    }
                }

                Bugsnag::notifyException($e);
                return redirect('/plan/managePlans')->with('successMessage',"We apologize, we are having technical difficulties. Please contact us about your issue");
            }

            $plansCreated[] = $stripeIdentifier;
        }

        try {
            $ex = null;
            $plan = Plan::create([
                'user_id' => Auth::id(),
                'business_id' => $businessId,
                'stripe_plan_id' => $planIdentifier,
                'stripe_plan_name' => $planName,
                'month_price' => $monthPrice,
                'year_price' => $yearPrice,
                'use_limit_month' => $useLimitMonth,
                'use_limit_year' => $useLimitYear,
                'limit_interval' => $limitInterval,
                'description' => $description,
                'category' => $category,
                'featured_photo_path' => null,
            ]);
        } catch (Exception $e) {
            $ex = $e;
            $plan = null;
        }

        if($plan == null) {
            if(count($plansCreated) > 0) {
                foreach ($plansCreated as $identifier) {
                    $stripeplan = \Stripe\Plan::retrieve($identifier);
                    $stripeplan->delete();
                }
            }

            Bugsnag::notifyException($ex);
            return redirect('/plan/managePlans')->with('successMessage',"We apologize, we are having technical difficulties. Please contact us about your issue");
        }

        $msg = 'Service created successfully! ';
        try {
            $photoUpdate = $this->updateFeaturedPhoto($request, $plan->id, $featuredPhoto);
            $obj = jsonToObject($photoUpdate->getContent());
            $plan->featured_photo_path = $obj->path;

            if($galleryPhotos != null && count($galleryPhotos) > 0) {
                foreach ($galleryPhotos as $file) {
                    $this->updateGalleryPhotos($request, $plan->id, $file);
                }
            }
        } catch (Exception $e) {
            Bugsnag::notifyException($e);
            $msg .= " Warning: there was a problem with one or more of your uploads";
        }


        try {
            $this->updateEsIndex($plan, $es);
        } catch (Exception $e) {
            $plan->delete();
            if(count($plansCreated) > 0) {
                foreach ($plansCreated as $identifier) {
                    $stripeplan = \Stripe\Plan::retrieve($identifier);
                    $stripeplan->delete();
                }
            }

            return redirect('/plan/managePlans')->with('errorMessage',"We could not complete this request. Please contact us if you experience this issue further.");
        }

        return redirect('/plan/managePlans')->with('successMessage',$msg);

    }

    public function updateFeaturedPhoto(Request $request, $id, $file = null)
    {
        $path = '';
        if(!empty($request)) {

            $this->validate($request, [
                'featured_photo'    => 'required|image'
            ]);

            $photo = $file ?: $request->file('featured_photo');
            (new ImageManager())->make($photo->path())->orientate()->save($photo->path()); // orients the photo and saves it back to the temporary file path before storing$photo
            $path = $this->photoClient->store($photo, S3FolderTypes::PLAN_FEATURED_PHOTO);
            try {
                $plan = Plan::where('user_id', Auth::id())->where('id',$id)->first();
                if ($plan->featured_photo_path) {
                    $this->photoClient->unlink(getFullPathToImage($plan->featured_photo_path));
                }
                $plan->featured_photo_path = $path;
                $plan->save();

                $this->updateEsIndex($plan, $this->esClient);

                return Response::create([
                    'msg' => sprintf("Upload successful"),
                    'path' => $path
                ], 200);

            } catch (Exception $e) {
                $this->photoClient->unlink($path);
                return Response::create([
                    'msg' => sprintf("Upload failed: %s", $e->getMessage())
                ], 400);
            }


        }

        return Response::create([
            'msg' => sprintf("Upload failed: Request is empty")
        ], 400);
    }

    public function updateGalleryPhotos(Request $request, $id, $file = null) {

        $plan = Plan::where('user_id', Auth::id())->where('id',$id)->first();

        if(!$plan) {
            return Response::create([
                'msg'   => 'The entity you are updating does not exists'
            ], 404);
        }

        $galleryCount = count($plan->photos);
        if($galleryCount >= self::MAX_GALLERY_COUNT) {
            return Response::create([
                'msg'   => 'Max uploads exceeded. Please remove a photo to add more '
            ], 400);
        }

        if(!$file) {
            $this->validate($request, [
                'gallery_photos'  => 'nullable|image'
            ]);

            $photo = $request->file('gallery_photos');
        } else {
            // in this case, its coming from createService and has already been validated
            $photo = $file;
        }



        try {
            (new ImageManager())->make($photo->path())->orientate()->save($photo->path()); // orients the photo and saves it back to the temporary file path before storing
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
        $this->validate($request,[
            'stripe_plan_name'   => 'required',
            'description'        => 'required'
        ]);
        $otruvezPlan = Plan::where('user_id', Auth::id())->where('id',$id)->first();

        noEntityAbort($otruvezPlan,404);

        $business   = $otruvezPlan->business;
        $data       = [
            'oldName'           => $otruvezPlan->stripe_plan_name,
            'oldDescription'    => $otruvezPlan->description,
        ];

        try {
            $this->updateEsIndex($otruvezPlan, $this->esClient);
            // may need to update the use limits
            $otruvezPlan->stripe_plan_name   = $request->stripe_plan_name;
            $otruvezPlan->description        = $request->description;
            $otruvezPlan->save();
            $subscriptions              = Subscription::where('plan_id', $id)->get();
            $notification               = new Notification();
            if($subscriptions) {
                foreach ($subscriptions as $subscription) {
                    $data['subscription'] = $subscription;
                    $notification->sendNotifyPlanModificationNotification($business, $subscription, $data);
                }
            }

            if($request->has('redirect_to'))
            {
                $business->redirect_to = $request->get('redirect_to');
                $business->save();
            }

        } catch (Exception $exception) {
            return redirect("/plan/managePlans")->with('successMessage','Could not update:' . " $exception");
        }

        return redirect("/plan/managePlans")->with('successMessage','Plan updated successfully!');
    }

    public function deletePlan(Request $request, $id)
    {
        // in the future, i'd like to obfuscate the plan id to prevent data mining
        $otruvezPlan = Plan::where('user_id', Auth::id())->where('id',$id)->first();
        noEntityAbort($otruvezPlan,404);
        $business = $otruvezPlan->business;
        $planName = $otruvezPlan->stripe_plan_name;
        $subscriptions = Subscription::where('plan_id', $id)->get();
        $notification         = new Notification();
        if($subscriptions) {
            foreach ($subscriptions as $subscription) {
                // need to calculate REFUND, maybe if create date is within "X"
                try {
                    Notification::where('subscription_id', $subscription->id)->delete();
                } catch (Exception $e) {
                    logException($e);
                }
                $data = [
                    'subscription'  => $subscription,
                    'plan'          => $otruvezPlan,
                    'business'      => $business,
                ];
                $data['refundStatus'] = Subscription::getRefundStatusAndAmount($subscription);
                $notification->sendNotifyPlanDeletionNotification($business, $subscription, $data);

            }
        }


        try {
            if ($otruvezPlan && $otruvezPlan->user_id != Auth::id()) {
                return redirect("/plan/managePlans")->with('errorMessage', 'YOU ARE NOT AUTHORIZED TO DO THIS! PLEASE DON\'T!');
            }

            if (!$otruvezPlan->delete()) {
                return redirect("/plan/managePlans")->with('warningMessage', "There was a problem. Please try again");
            }

            Subscription::where('plan_id', $id)->delete();
            $planId = $otruvezPlan->stripe_plan_id;
        } catch (Exception $e) {
            logException($e);
        }
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
        $plan = Plan::where('user_id', Auth::id())->where('id',$id)->first();
        noEntityAbort($plan, 404);
        $this->photoClient->unlink(getFullPathToImage($plan->featured_photo_path));
        $plan->featured_photo_path = null;
        $plan->save();

        return redirect("/plan/managePlans")->with('infoMessage',"Image removed successfully");
    }




    public function deleteGalleryPhoto(Request $request, $id)
    {
        try {
            $photo = Photo::where('user_id', Auth::id())->where('id',$id)->first();
            if(!$photo) {
                return Response::create([
                    'msg'   => 'Image does not exist'
                ], 400);
            }
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
            $body['business_handle'] = $plan->business->business_handle;
            $body['name']       = $plan->business->name;

            try {
                $es->index([
                    'index' => $plan->getSearchIndex(),
                    'type' => $plan->getSearchType(),
                    'id' => $plan->id,
                    'body' => $body,
                ]);
            } catch (Exception $e) {
                throw new BadRequest400Exception($e->getMessage());
            }
        }
    }

    public function showCreateService()
    {
        return view('plan.create-service');
    }

    public function apiSetup(Request $request, $planId) {
        $plan = Plan::where('user_id', Auth::id())->where('id',$planId)->first();
        noEntityAbort($plan,404);
        $business = $plan->business;
        $portalLink = sprintf("/portal/viewService/%s/%s/%s",$business->id,$plan->id,$business->api_key);
        return view('business.online-integration')->with([
            'business' => $business,
            'plan' => $plan,
            'portalLink' => $portalLink
        ]);
    }

    public function getInterval($month, $year) {
        if($month) {
            return 'month';
        } else if($year) {
            return 'year';
        } else {
            return null;
        }
    }




}
