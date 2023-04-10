<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class SettingController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('permission:admin.setting')->only(['update']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            // check if settings exists in cache
            if (cache()->has('settings')) {
                $setting = cache()->get('settings');
            } else {
                // get all settings
                $setting = Setting::all();
                // convert to key value pair
                $setting = $setting->mapWithKeys(function ($item) {
                    return [$item['key'] => $item['value']];
                });
                // get php supported timezone list
                $setting['timezone'] = timezone_identifiers_list();
                // cache settings for 1 day
                cache()->put('settings', $setting, 60 * 6);
            }
            return response()->json([
                'data' => $setting
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'app_name' => 'required|string',
                'app_description' => 'required|string',
                'app_logo' => 'required|string',
                'app_favicon' => 'required|string',
                'app_timezone' => 'required|string',
                'app_keywords' => 'required|string',
                'app_head_code' => 'nullable|string',
            ]);
            // update all settings
            foreach ($request->all() as $key => $value) {
                Setting::where('key', $key)->update(['value' => $value]);
            }
            // update system timezone
            date_default_timezone_set($request->app_timezone);
            // clear cache
            cache()->forget('settings');
            return response()->json([
                'message' => 'Settings updated successfully'
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
