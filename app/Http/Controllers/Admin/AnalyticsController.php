<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use AkkiIo\LaravelGoogleAnalytics\Facades\LaravelGoogleAnalytics;
use AkkiIo\LaravelGoogleAnalytics\Period;
use Google\Analytics\Data\V1beta\Filter\StringFilter\MatchType;
use Google\Analytics\Data\V1beta\MetricAggregation;
use Google\Analytics\Data\V1beta\Filter\NumericFilter\Operation;

class AnalyticsController extends Controller
{
    // constructor
    public function __construct()
    {
    }

    /**
     * Get analytics data form Google Analytics
     * @param Request $request
     * @return json
     */
    public function getAnalytics(Request $request)
    {
        try {
            // get average session duration of last 12 months
            $averageSessionDuration = LaravelGoogleAnalytics::dateRanges(Period::create(Carbon::today()->subMonth(12), Carbon::today()))
                ->metrics('averageSessionDuration')
                ->dimensions('year', 'month')
                ->get();
            // get browser and country by sessions
            $dataOfSession = LaravelGoogleAnalytics::dateRanges(Period::create(new Carbon('2020-01-21'), Carbon::today()))
                ->metrics('sessions')
                ->dimensions('browser', 'country')
                ->get();
            $dataOfSession = $dataOfSession->table;

            $browsers = [];
            $countrys = [];
            // get browser and country by sessions
            foreach ($dataOfSession as $key => $value) {
                if (!isset($browsers[$value['browser']])) {
                    $browsers[$value['browser']] = 0;
                }
                if (!isset($countrys[$value['country']])) {
                    $countrys[$value['country']] = 0;
                }
                $browsers[$value['browser']] += $value['sessions'];
                $countrys[$value['country']] += $value['sessions'];
            }

            return response()->json([
                'averageSessionDuration' => $averageSessionDuration,
                'browser' => $browsers,
                'country' => $countrys
            ]);
        } catch (\Throwable $th) {
            dd($th);
        }
    }
}
