<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
                'school_slug' => 'required|string|exists:schools,slug',
                'name' => 'required|string|unique:lessons,name',
                'description' => 'required|string|max:100',
                'details' => 'required|array',
            ]);
            if($request->has('password') && $request->input('password')) {
                $request->validate([
                    'password' => 'required|string|min:6',
                ]);
                $request->merge([
                    'password' => bcrypt($request->input('password')),
                ]);
            }
            $school = School::where('slug', $request->input('school_slug'))->firstOrFail();
            $lesson = $school->lessons()->create($request->all());
            $lesson->details()->createMany($request->input('details'));
            return response()->json([
                'message' => 'Lesson created successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
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
}
