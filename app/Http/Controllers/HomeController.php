<?php

namespace App\Http\Controllers;

use App\Location;
use App\Plan;
use Elasticsearch\Client;
use Elasticsearch\Transport;
use Illuminate\Http\Request;
use App\Repositories\ESPlanRepository;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    private $maxResults = 25;

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ESPlanRepository $ESPlanRepository, Request $request)
    {
        $location = (new Location())->find($request->get('location_id') ?: Auth::user()->location_id);
        $kms = $request->get('miles') ? ($request->get('miles') * 1.61) . "km" : '16.10km'; // default distance is 10 miles | 8.05km == 5 mi
        $lat = $location ? $location->lat : null;
        $lng = $location ? $location->lng : null;
        $paginationIndex = $request->get('from');
        $maxResults     = $this->maxResults;

        $results = $ESPlanRepository->search($request->get('searchField'), $lat, $lng, $kms, $paginationIndex);

        if($request->get('location_id') != Auth::user()->location_id && $request->get('location_id') > 0) {
            $user = Auth::user();
            $user->location_id = $request->get('location_id');
            $user->save();
        }

        $searchFrom = $paginationIndex ?: null;
        $totalResultCount = $results['actualTotal'];

        // pagination variables
        $totalPages             = ceil($results['actualTotal']/$maxResults); // total pages needed for pagination
        $currentPageInterval    = $searchFrom ? floor($searchFrom/125) + 1 : 1; // we will paginate in increments of 5. this determines which interval of 5 we will be on
        $loopStart              = $currentPageInterval < 2 ? 1 : ($currentPageInterval - 1) * 5; // which multiple of 5 we should start our loop based on the current interval
        $loopEnd                = $totalPages - $loopStart >= 5 ? ($currentPageInterval * 5) : $totalPages + 1 - $loopStart;
        $rightArrow             = $totalPages > 5 && (!$searchFrom || $searchFrom < 125)  || ( ( $totalResultCount - ( floor($searchFrom/125) * 125 ) ) / $maxResults ) > 5;
        $rightArrowFrom         = ($currentPageInterval * 125 );
        $leftArrow              = $currentPageInterval > 1;
        $leftArrowFrom          = ($currentPageInterval - 1) * 125;

        return view('home')
            ->with('maxResults', $maxResults)
            ->with('plans', $results['plans'])
            ->with('searchFrom', $searchFrom)
            ->with('maxPages', 5)
            ->with('searchField', $request->get('searchField') ?: '')
            ->with('totalResultCount', $results['actualTotal']) // this may change. With pagination, we need the total "hits" and the returned results
            ->with('miles', $request->get('miles') > 0 ? $request->get('miles') : 10)
            ->with('location', $location ?: new Location())
            ->with('totalPages', $totalPages)
            ->with('loopStart', $loopStart)
            ->with('loopEnd', $loopEnd)
            ->with('rightArrow', $rightArrow)
            ->with('rightArrowFrom', $rightArrowFrom)
            ->with('leftArrow', $leftArrow)
            ->with('leftArrowFrom', $leftArrowFrom)
            ->with('currentPageInterval',$currentPageInterval)
            ->with('queryString', !empty($request->get('searchField')) ? $request->get('searchField') : '');



    }
}
