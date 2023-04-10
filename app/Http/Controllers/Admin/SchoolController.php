<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $schools = School::all();
            return response()->json([
                'school' => $schools,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
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
        try {
            $request->validate([
                'name' => 'required|string',
                'address' => 'required|string',
                'website' => 'required|string',
                'city' => 'required|string',
                'region' => 'required|string',
                'logo' => 'required|string',
                'slug' => 'nullable|string',
                'status' => 'required|string|in:active,inactive',
            ]);
            $request->merge([
                'slug' => $request->input('slug') ?? Str::slug($request->input('name')),
            ]);
            $school = School::create($request->all());
            return response()->json([
                'message' => 'School created successfully',
                'school' => $school,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $school = School::findOrFail($id);
            // get school user list with role
            $users = $school->users()->with('role')->get();
            return response()->json([
                'school' => $school,
                'users' => $users,
                'classes' => $school->classes,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'address' => 'required|string',
                'website' => 'required|string',
                'city' => 'required|string',
                'region' => 'required|string',
                'logo' => 'required|string',
                'slug' => 'nullable|string',
                'status' => 'required|string|in:active,inactive',
            ]);
            $request->merge([
                'slug' => $request->input('slug') ?? Str::slug($request->input('name')),
            ]);
            $school = School::findOrFail($id);
            $school->update($request->all());
            return response()->json([
                'message' => 'School updated successfully',
                'school' => $school,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $school = School::findOrFail($id);
            $school->delete();
            return response()->json([
                'message' => 'School deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
