<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\School;
use Illuminate\Http\Request;

class CourseController extends Controller
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
                'slug' => 'required|string|exists:schools,slug',
                'name' => 'required|string',
                'description' => 'required|string',
            ]);
            $school = School::where('slug', $request->input("slug"))->firstOrFail();
            $course = Course::create([
                'name' => $request->name,
                'description' => $request->description,
                'user_id' => auth()->user()->id,
                'school_id' => $school->id,
            ]);
            return response()->json($course);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        try {
            $school = School::where('slug', $slug)->firstOrFail();
            $courses = $school->courses->load('user');
            return response()->json($courses);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $course = Course::where('id', $id)->firstOrFail();
            return response()->json($course->load('lessons'));
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'type' => 'required|string|in:add-lesson,remove-lesson,update',
            ]);
            $course = Course::where('id', $id)->firstOrFail();
            if ($request->type == 'add-lesson') {
                $request->validate([
                    'lesson_ids' => 'required|array',
                ]);
                $course->lessons()->syncWithoutDetaching($request->input('lesson_ids'));
            } else if ($request->type == 'remove-lesson') {
                $request->validate([
                    'lesson_ids' => 'required|array',
                ]);
                $course->lessons()->detach($request->input('lesson_ids'));
            } else if ($request->type == 'update') {
                $request->validate([
                    'name' => 'required|string',
                    'description' => 'required|string',
                    'password' => 'nullable|string',
                    'status' => 'required|in:active,inactive',
                ]);
                if ($request->has('password') && $request->input('password') != '')
                    $request->merge(['password' => bcrypt($request->input('password'))]);
                $course->update($request->all());
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $course = Course::where('id', $id)->firstOrFail();
            $course->delete();
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
