<?php

namespace App\Http\Controllers\Admin;

use AkkiIo\LaravelGoogleAnalytics\Exceptions\InvalidPeriod;
use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use AkkiIo\LaravelGoogleAnalytics\Facades\LaravelGoogleAnalytics;
use AkkiIo\LaravelGoogleAnalytics\Period;

class AnalyticsController extends Controller
{
    // constructor
    public function __construct()
    {
    }

    /**
     * Get analytics data form Google Analytics
     * @param Request $request
     * @return JsonResponse
     */
    public function getAnalytics(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'type' => 'required|string|in:duration,sessions,week,weekByEvent,local',
            ]);
            $data = match ($request->type) {
                'duration' => $this->getAverageSessionDuration(),
                'sessions' => $this->getBrowserAndCountry(),
                'week' => $this->compareWeek(),
                'weekByEvent' => $this->compareWeekByEvent(),
                'local' => $this->getLocalAnalytics(),
                default => [],
            };
            // return
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'data' => $data,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Get average session duration
     * @return array
     * @throws InvalidPeriod
     */
    private function getAverageSessionDuration(): array
    {
        // load data from cache if exists
        if (cache()->has('averageSessionDuration')) {
            return cache()->get('averageSessionDuration');
        }
        $averageSessionDuration = LaravelGoogleAnalytics::dateRanges(Period::create(Carbon::today()->subDays(30), Carbon::today()))
            ->metrics('averageSessionDuration')
            ->dimensions('year', 'month', 'day')
            ->orderByDimension('year', 'ASC')
            ->orderByDimension('month', 'ASC')
            ->orderByDimension('day', 'ASC')
            ->get();
        // save data to cache
        cache()->put('averageSessionDuration', $averageSessionDuration->table, 60 * 60 * 24);
        return $averageSessionDuration->table;
    }

    /**
     * Get browser and country by sessions
     * @return array
     * @throws InvalidPeriod
     */
    private function getBrowserAndCountry(): array
    {
        // load data from cache if exists
        if (cache()->has('browserAndCountry')) {
            return cache()->get('browserAndCountry');
        }
        $sessions = LaravelGoogleAnalytics::dateRanges(Period::create(Carbon::today()->subDays(30), Carbon::today()))
            ->metrics('sessions')
            ->dimensions('browser', 'country')
            ->get()->table;
        $browsers = $countries = [];
        foreach ($sessions as $session) {
            $browsers[$session['browser']] = ($browsers[$session['browser']] ?? 0) + $session['sessions'];
            $countries[$session['country']] = ($countries[$session['country']] ?? 0) + $session['sessions'];
        }
        arsort($browsers);
        arsort($countries);
        // save data to cache
        cache()->put('browserAndCountry', [
            'browser' => array_slice($browsers, 0, 5),
            'country' => array_slice($countries, 0, 5),
        ], 60 * 60 * 24);
        return [
            'browser' => array_slice($browsers, 0, 5),
            'country' => array_slice($countries, 0, 5),
        ];
    }

    /**
     * Compare this week with last week by page views
     * @return array
     * @throws InvalidPeriod
     */
    private function compareWeek(): array
    {
        // load data from cache if exists
        if (cache()->has('compareWeek')) {
            return cache()->get('compareWeek');
        }
        $lastWeek = LaravelGoogleAnalytics::dateRanges(Period::create(Carbon::today()->subDays(14), Carbon::today()->subDays(7)))
            ->metrics('screenPageViews')
            ->dimensions('dayOfWeek')
            ->get()->table;

        $thisWeek = LaravelGoogleAnalytics::dateRanges(Period::create(Carbon::today()->subDays(7), Carbon::today()))
            ->metrics('screenPageViews')
            ->dimensions('dayOfWeek')
            ->get()->table;
        // save data to cache
        cache()->put('compareWeek', [
            'lastWeek' => $lastWeek,
            'thisWeek' => $thisWeek,
        ], 60 * 60 * 24);
        return [
            'lastWeek' => $lastWeek,
            'thisWeek' => $thisWeek,
        ];
    }

    /**
     * Compare this week with last week by event count
     * @return array
     * @throws InvalidPeriod
     */
    private function compareWeekByEvent(): array
    {
        // load data from cache if exists
        if (cache()->has('compareWeekByEvent')) {
            return cache()->get('compareWeekByEvent');
        }
        $lastWeek = LaravelGoogleAnalytics::dateRanges(Period::create(Carbon::today()->subDays(14), Carbon::today()->subDays(7)))
            ->metrics('eventCount')
            ->dimensions('dayOfWeek')
            ->get()->table;

        $thisWeek = LaravelGoogleAnalytics::dateRanges(Period::create(Carbon::today()->subDays(7), Carbon::today()))
            ->metrics('eventCount')
            ->dimensions('dayOfWeek')
            ->get()->table;
        // save data to cache
        cache()->put('compareWeekByEvent', [
            'lastWeek' => $lastWeek,
            'thisWeek' => $thisWeek,
        ], 60 * 60 * 24);
        return [
            'lastWeek' => $lastWeek,
            'thisWeek' => $thisWeek,
        ];
    }

    /**
     * Get local analytics data
     * @return array
     */
    public function getLocalAnalytics(): array
    {
//         load data from cache if exists
//        if (cache()->has('localAnalytics')) {
//            return cache()->get('localAnalytics');
//        }
        $local = [
            'users' => [
                'total' => User::all()->count(),
                'change' => User::whereDate('created_at', Carbon::today())->count() - User::whereDate('created_at', Carbon::today()->subDays(1))->count(),
            ],
            'lessons' => [
                'total' => Lesson::all()->count(),
                'change' => Lesson::whereDate('created_at', Carbon::today())->count() - Lesson::whereDate('created_at', Carbon::today()->subDays(1))->count(),
            ],
            'courses' => [
                'total' => Course::all()->count(),
                'change' => Course::whereDate('created_at', Carbon::today())->count() - Course::whereDate('created_at', Carbon::today()->subDays(1))->count(),
            ],
            'classes' => [
                'total' => Classes::all()->count(),
                'change' => Classes::whereDate('created_at', Carbon::today())->count() - Classes::whereDate('created_at', Carbon::today()->subDays(1))->count(),
            ],
        ];
        // save data to cache
        cache()->put('localAnalytics', $local, 60 * 60 * 24);
        return $local;
    }
}
