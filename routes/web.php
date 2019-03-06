<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

Route::get('/', function () {
    if(Auth::check()) {
        return redirect('/home');
    } else {
        return view('index');
    }

});

Route::get('/contact', function () {
    if(Auth::check()) {
        return redirect('/account/support');
    } else {
        return view('contact');
    }
});

Route::get('/faqs', function () {
        return view('faqs');
});

Route::get('/merchant-faqs', function () {
    return view('merchant-faqs');
});

Route::get('/privacy', function () {
    return view('legal.privacy');
});

Route::get('/terms-and-conditions', function () {
    return view('legal.terms-and-conditions');
});

Route::get('/sellYourServices', function () {

        $sections = [
            [
                'msg'       => 'We charge 10% per Subscription you sell and payout every 2 weeks. That\'s it. Now let\'s talk about selling',
                'photoPath' => '',
                'first'     => 'Pricing / Fees'
            ],
            [
                'msg'       => '1st, register with us and then login. After logging in you will see "business account" at the top. Go there',
                'photoPath' => baseUrlConcat('/images/highlight-business.png'),
                'first'     => 'Selling your services with Otruvez'
            ],
            [
                'msg'       => "Once in the business center, you'll see this form. <br>Enter all your business's information and submit",
                'photoPath' => baseUrlConcat('/images/merchantForm.png'),
                'first'     => ''
            ],
            [
                'msg'       => "Now you can begin adding services.",
                'photoPath' => baseUrlConcat('/images/business-dash-buttons.png'),
                'first'     => ''
            ],
            [
                'msg'       => "On the Manage Services page, you'll see this button<br> Click it.",
                'photoPath' => baseUrlConcat('/images/createservicebutton.png'),
                'first'     => ''
            ],
            [
                'msg'       => "That button will present to you the form to create your service",
                'photoPath' => baseUrlConcat('/images/createserviceform.png'),
                'first'     => ''
            ],
            [
                'msg'       => "After creating the service, you'll still need to <br>activate it by adding a \"Featured Photo\"",
                'photoPath' => baseUrlConcat('/images/inactiveplan.png'),
                'first'     => ''
            ],
            [
                'msg'       => "You can add a featured photo and up to 4 gallery photos",
                'photoPath' => baseUrlConcat('/images/addphoto.png'),
                'first'     => ''
            ],
            [
                'msg'       => "After adding your photo, your plan will be active",
                'photoPath' => baseUrlConcat('/images/activeplan.png'),
                'first'     => ''
            ],
            [
                'msg'       => "Finally, here's what potential customers will see when they search for your service",
                'photoPath' => baseUrlConcat('/images/searchview.png'),
                'first'     => ''
            ]
        ];
        return view('sell-your-services')->with('sections', $sections);
});


Route::post('/contactUs', 'HomeController@contactUs');

Route::get('/log/out', function () {
    \Illuminate\Support\Facades\Auth::logout();
    return redirect('/');
});

Route::get('/email', function(){
    Mail::to('ib708090@gmail.com')->send(new \App\Mail\ConfirmAccount());
    return redirect('/');
});


Auth::routes();

/** USER ROUTES */
Route::get('/user/activateUserAccount', 'UserController@activateUserAccount');
Route::post('/user/test', 'UserController@test');
Route::get('/user/testView', 'UserController@testView');
Route::post('/validateToken', 'UserController@validateToken');
/** PLAN USER END */



/** BUSINESS ROUTES */
Route::get('/confirmAccount', 'HomeController@showConfirmAccount');
Route::get('/home', 'HomeController@index');
Route::post('/home/findServices', 'HomeController@findServices');
/** BUSINESS ROUTES */





/*
 * --------- API ROUTES ---------- *
 * --------- API ROUTES ---------- *
 * --------- API ROUTES ---------- *
 */

/** BUSINESS ROUTES */
Route::get('/business', 'BusinessController@index')->name('business');
Route::get('/business/signup', 'BusinessController@signup')->name('business/signup');
Route::get('/business/manageBusiness', 'BusinessController@manageBusiness');
Route::get('/business/viewStore/{handle}', 'BusinessController@viewStore');
Route::get('/business/viewStore/{handle}/about', 'BusinessController@about');
Route::get('/business/viewStore/{handle}/contact', 'BusinessController@contact');
Route::get('/business/viewService/{planId}', 'BusinessController@viewService');
Route::get('/business/checkins', 'BusinessController@showCheckinView');
Route::get('/business/cancel', 'BusinessController@showCancelAccountView');
Route::get('/business/notifyCustomers', 'BusinessController@showNotifyCustomersView');
Route::get('/business/notifications', 'BusinessController@showBusinessNotificationView');
Route::get('/store/{businessHandle}', 'BusinessController@getStore');
Route::get('/business/subscribers', 'BusinessController@showSubscribers');
Route::post('/business/checkHandleAvailability', 'BusinessController@checkHandleAvailability');
Route::post('/business/notifyCustomers', 'BusinessController@notifyCustomers');
Route::post('/business/deleteBusiness/{id}', 'BusinessController@deleteBusiness');
Route::post('/business/createAccount', 'BusinessController@createBusinessAccount');
Route::post('/business/create', 'BusinessController@createBusiness');
Route::post('/business/updatePhoto/{businessId}', 'BusinessController@updateBusinessPhoto');
Route::post('/business/updateLogo/{businessId}', 'BusinessController@updateBusinessLogo');
Route::post('/business/updateRedirectTo', 'BusinessController@updateRedirectTo');
Route::put('/business/update/{id}', 'BusinessController@updateBusiness');
Route::put('/business/deactivate/{id}', 'BusinessController@deactivateBusiness');
Route::put('/business/activate/{id}', 'BusinessController@activateBusiness');
Route::put('/business/suspend/{id}', 'BusinessController@suspendBusiness');
Route::delete('/business/deletePhoto/{businessId}', 'BusinessController@deleteBusinessPhoto');
Route::delete('/business/deleteLogo/{businessId}', 'BusinessController@deleteBusinessLogo');
/** BUSINESS ROUTES END */


/** SUBSCRIPTION SERVICE ROUTES */
Route::post('/subscriptionService/create', 'SubscriptionServiceController@createSubscriptionService');
Route::put('/subscriptionService/update/{id}', 'SubscriptionServiceController@updateSubscriptionService');
Route::put('/subscriptionService/deactivate/{id}', 'SubscriptionServiceController@deactivateSubscriptionService');
Route::put('/subscriptionService/activate/{id}', 'SubscriptionServiceController@activateSubscriptionService');
Route::delete('/subscriptionService/delete/{id}', 'SubscriptionServiceController@deleteSubscriptionService');

/** SUBSCRIPTION SERVICE ROUTES END */


/** CUSTOMER ROUTES */
Route::post('/customer/create', 'CustomerController@createCustomer');
Route::put('/customer/update/{id}', 'CustomerController@updateBusiness');
Route::put('/customer/deactivate/{id}', 'CustomerController@deactivateBusiness');
Route::put('/customer/activate/{id}', 'CustomerController@activateBusiness');
Route::put('/customer/suspend/{id}', 'CustomerController@suspendBusiness');
Route::delete('/customer/delete/{id}', 'CustomerController@deleteBusiness');

/** CUSTOMER ROUTES END */


/** SUBSCRIPTION ROUTES */
Route::get('/subscription/subscribe/{planId}', 'SubscriptionController@showSubscriptionForm')->name('chooseSubscription');
Route::get('/subscription/subscribed', 'SubscriptionController@subscribed');
Route::post('/subscription/subscribe', 'SubscriptionController@createSubscription');
Route::post('/subscription/create', 'SubscriptionController@createBusiness');
Route::put('/subscription/update/{id}', 'SubscriptionController@updateBusiness');
Route::put('/subscription/deactivate/{id}', 'SubscriptionController@deactivateBusiness');
Route::put('/subscription/activate/{id}', 'SubscriptionController@activateBusiness');
Route::put('/subscription/suspend/{id}', 'SubscriptionController@suspendBusiness');
Route::delete('/subscription/cancel/{id}', 'SubscriptionController@cancelSubscription');

// AJAX
Route::post('/subscription/checkin/{planId}/{subscriptionId}', 'SubscriptionController@checkin');
Route::post('/subscription/confirmCheckin/{subscriptionId}', 'SubscriptionController@confirmCheckin');
/** SUBSCRIPTION ROUTES END */


/** PLAN ROUTES */
//Route::get('/plan/chooseAccountPlan', 'PlanController@showChooseAccountForm');
Route::get('/plan/createAppPlans', 'PlanController@storeAppPlansLocally');
Route::get('/plan/managePlans', 'PlanController@managePlans');
Route::get('/plan/createService', 'PlanController@showCreateService');
Route::get('/plan/apiSetup/{planId}', 'PlanController@apiSetup');
Route::post('/plan/createPlan', 'PlanController@createServicePlan');
Route::post('/plan/featuredPhoto/{id}', 'PlanController@updateFeaturedPhoto');
Route::post('/plan/galleryPhoto/{id}', 'PlanController@updateGalleryPhotos');

Route::put('/plan/update/{id}', 'PlanController@updatePlan');

Route::delete('/plan/delete/{id}', 'PlanController@deletePlan');
Route::delete('/plan/featuredPhoto/{id}', 'PlanController@deleteFeaturedPhoto');
Route::delete('/plan/galleryPhoto/{id}', 'PlanController@deleteGalleryPhoto');
/** PLAN ROUTES END */

/** LOCATION ROUTES */
Route::get('/location', 'LocationController@getLocations');
/** LOCATION ROUTES END */


/** REVIEW ROUTES */
Route::get('/review/all/{businessId}', 'ReviewController@getAll');
Route::post('/review/addReview/{businessId}', 'ReviewController@addReview');
Route::delete('/review/deleteReview/{businessId}', 'ReviewController@deleteReview');
/** REVIEW ROUTES END */

/** RATING ROUTES */
Route::get('/account', 'AccountController@index');
Route::post('/rating/rateService/{planId}', 'RatingController@rateService');
/** RATING ROUTES END */

/** NOTIFICATION ROUTES */
Route::get('/account', 'AccountController@index');
Route::get('/account/mysubscriptions/{portalBusinessId?}', 'AccountController@subscriptions');
Route::get('/account/notifications', 'AccountController@accountNotificationView');
Route::get('/account/delete', 'AccountController@showDeleteAccountView');
Route::get('/account/support', 'AccountController@showSupportView');
Route::get('/account/updatePayment', 'AccountController@showUpdatePaymentView');
Route::post('/account/updatePaymentMethod', 'AccountController@UpdatePaymentMethod');
Route::post('/account/contactSupport', 'AccountController@contactSupport');
Route::post('/account/deleteAccount', 'AccountController@deleteAccount');
/** NOTIFICATION ROUTES END */

/** WEBHOOK ROUTES */
Route::post('/stripeWebhook/failedPayment', 'WebhookController@failedPayment'); // [charge.failed , invoice.payment_failed]
Route::post('/stripeWebhook/successfulPayment', 'WebhookController@successfulPayment'); // [charge.failed , invoice.payment_failed]
/** WEBHOOK ROUTES END */

/** PORTAL ROUTES */
Route::get('/portal/login/{businessId}/{stripeId}/{apiKey}/{customerEmail?}', 'PortalController@showLogin');
Route::get('/portal/register/{businessId}/{stripeId}/{apiKey}/{customerEmail?}', 'PortalController@showRegister');
Route::get('/portal/viewService/{businessId}/{stripeId}/{apiKey}', 'PortalController@showService');
Route::get('/portal/confirmAccount/{businessId}/{stripeId}/{apiKey}', 'PortalController@showConfirmAccount');
/** PORTAL ROUTES END */


/** SOCIAL AUTH ROUTES */
Route::get('auth/{provider}/', 'Auth\SocialAuthController@redirectToProvider');
Route::get('auth/{provider}/callback', 'Auth\SocialAuthController@handleProviderCallback');
Route::get('portal/auth/{provider}/{businessId}/{stripeId}/{apiKey}', 'Auth\SocialAuthController@redirectToProviderPortal');
Route::get('portal/auth/callback/{provider}/{businessId}/{stripeId}/{apiKey}', 'Auth\SocialAuthController@handleProviderCallbackPortal');
/** SOCIAL AUTH ROUTES END */
