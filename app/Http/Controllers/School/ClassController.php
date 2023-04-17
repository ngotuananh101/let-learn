<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\School;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'slug' => 'required|string',
            ]);
            $user = auth()->user();
            // get school by slug
            $school = School::where('slug', $request->slug)->first();
            // check if user is admin or super or user is the owner of the school
            if ($user->role->name === 'admin' || $user->role->name === 'super' || $user->school_id === $school->id) {
                // get all classes
                $classes = $school->classes;
                return response()->json([
                    'classes' => $classes,
                ], 200);
            } else {
                return response()->json([
                    'message' => 'You are not authorized to view this resource',
                ], 403);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Check permission for the specified resource.
     * @param string $id The class id
     */
    public function checkPermission(string $id)
    {
        $user = auth()->user();
        if ($user->role->name === 'admin' || $user->role->name === 'super') {
            return true;
        } else {
            $class = Classes::find($id);
            if ($class->school_id === $user->school_id) {
                return true;
            } else {
                return false;
            }
        }
    }
}
