<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        try {
            // Get school by slug
            $school = School::where('slug', $request->input('slug'))->firstOrFail();
            // Load relationships
            $school->load('users', 'classes', 'courses', 'lessons');
            return response()->json($school, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
