<?php

namespace App\Http\Controllers;

use App\Business;
use App\Http\Requests\GalleryUploadRequest;
use App\Photo;
use App\Plan;
use App\Rating;
use App\User;
use Exception;
use Faker\Provider\cs_CZ\DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Stripe\Stripe;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PlanController extends Controller
{

    const STANDARD_CURRENCY = 'usd';

    const PHOTO_TYPE = 'plan';

    const MAX_GALLERY_COUNT = 4;

    private $esClient;

    public function __construct(Client $esClient)
    {
        $this->middleware('auth');
        $this->esClient = $esClient;
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

        setStripeApiKey('secret');
        $es = $this->esClient;

        $planName             = $request->stripe_plan_name;
        $businessId           = Auth::user()->business->id;
        $planIdentifier       = uniqid(sprintf("%u_%u",$businessId,Auth::id()));
        $useLimitMonth        = abs($request->use_limit_month);
        $useLimitYear         = abs($request->use_limit_year);
//        return redirect()->back()->with('successMessage', $useLimitMonth);
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
         * NOTE: this needs to be wrapped in a foreach loop
         * as there will need to be 2 plans for each interval due to the price
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
            'featured_photo_path' => '',
        ]);

        $this->updateEsIndex($plan, $es);

        return redirect('/plan/managePlans')->with('successMessage','Service created successfully!');

    }

    public function updatePlan(Request $request, $id)
    {
        $smPlan = Plan::find($id);

        if($smPlan && $smPlan->user_id != Auth::id()) {
            return redirect("/plan/managePlans")->with('errorMessage','YOU ARE NOT AUTHORIZED TO DO THIS! PLEASE DON\'T!');
        }

        $this->updateEsIndex($smPlan, $this->esClient);

        $smPlan->stripe_plan_name   = $request->stripe_plan_name;
        $smPlan->use_limit          = $request->use_limit;
        $smPlan->description        = $request->description;
        try {
            $smPlan->save();
            return redirect("/plan/managePlans")->with('successMessage','Plan updated successfully!');
        } catch (Exception $exception) {
            return redirect("/plan/managePlans")->with('successMessage','Could not update');
        }
    }

    public function deletePlan(Request $request, $id)
    {
        // in the future, i'd like to obfuscate the plan id to prevent data mining
        $smPlan = Plan::find($id);
        $planName = $smPlan->stripe_plan_name;

        if($smPlan && $smPlan->user_id != Auth::id()) {
            return redirect("/plan/managePlans")->with('errorMessage','YOU ARE NOT AUTHORIZED TO DO THIS! PLEASE DON\'T!');
        }

        if(!$smPlan->delete())
        {
            return redirect("/plan/managePlans")->with('warningMessage',"There was a problem. Please try again");
        }

        $planId =  $smPlan->stripe_plan_id;
        setStripeApiKey('secret');
        $plan = \Stripe\Plan::retrieve($planId."_month");
        $plan->delete();
        $plan = \Stripe\Plan::retrieve($planId."_year");
        $plan->delete();

        return redirect("/plan/managePlans")->with('infoMessage',"$planName deleted successfully");

    }

    public function updateFeaturedPhoto(Request $request, $id)
    {
        if(!empty($request)) {
            $path = $request->file('file')->store('public/images/plan');
            $path = str_replace('public/','',$path); // fix path here so we can render properly. will likely be changed once we move to prod
            try {
                $plan = Plan::where('user_id', Auth::id())->where('id',$id)->first();
                if ($plan->featured_photo_path) {
                    unlink(getFullPathToImage($plan->featured_photo_path));
                }
                $plan->featured_photo_path = $path;
                $plan->save();
            } catch (Exception $e) {
                unlink($path);
            }

            return redirect("/plan/managePlans")->with('successMessage',"Image uploaded successfully!");
        }

        return redirect("/plan/managePlans")->with('warningMessage',"Empty request");
    }

    public function deleteFeaturedPhoto(Request $request, $id)
    {
        $plan = Plan::find($id);
        unlink(getFullPathToImage($plan->featured_photo_path));
        $plan->featured_photo_path = null;
        $plan->save();

        return redirect("/plan/managePlans")->with('infoMessage',"Image removed successfully");
    }

    public function updateGalleryPhotos(Request $request, $id) {
        $plan = Plan::find($id);
        $galleryCount = count($plan->photos);
        $photo = $request->file('file');
        if($galleryCount >= self::MAX_GALLERY_COUNT) {
            return redirect("/plan/managePlans")->with('warningMessage',"Max uploads exceeded. Please remove a photo to add more ");
        }

        $path = $photo->store('public/images/plan-gallery');
        $path = str_replace('public/','',$path); // because of rendering issues
        try
        {
            DB::table('photos')->insert([
                'plan_id'   => $plan->id,
                'user_id'   => Auth::id(),
                'type'      => self::PHOTO_TYPE,
                'path'      => $path
            ]);
        } catch (Exception $e) {
            unlink($path);
            return redirect("/plan/managePlans")->with('errorMessage',"Unsuccessful uploaded");
        }


        return redirect("/plan/managePlans")->with('infoMessage', " out of 4 were successfully uploaded");
    }


    public function deleteGalleryPhoto(Request $request, $id)
    {
        $photo = Photo::find($id);
        unlink(getFullPathToImage($photo->path));
        $photo->delete();
        return redirect("/plan/managePlans")->with('infoMessage',"Image removed successfully");
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




}
