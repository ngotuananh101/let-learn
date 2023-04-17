<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
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
                'name' => 'required|string|unique:lessons,name',
                'description' => 'required|string|max:100',
                'details' => 'required|array',
            ]);
            if($request->has('password') && $request->password) {
                $request->validate([
                    'password' => 'required|string|min:6',
                ]);
                $request->merge([
                    'password' => bcrypt($request->password),
                ]);
            }
            $user = auth()->user();
            if($user->role->name == 'admin' || $user->role->name == 'super') {
                $lesson = $user->lessons()->create($request->all());
            } else {
                $school = $user->school;
                $request->merge([
                    'school_id' => $school->id,
                ]);
                $lesson = $user->lessons()->create($request->all());
            }

            $lesson->details()->createMany($request->details);
            return response()->json(['message' => 'Lesson created successfully'], 200);

        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        try {
            $school = School::where('slug', $slug)->firstOrFail();
            return response()->json(['lessons' => $school->lessons->load('classes')], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $user = auth()->user();
            if($user->role->name == 'admin' || $user->role->name == 'super') {
                $lesson = Lesson::findOrFail($id);
            } else {
                $lesson = Lesson::where('school_id', $user->school->id)->findOrFail($id);
            }
            return response()->json(['lesson' => $lesson->load(['details'])], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $user = auth()->user();
            if($user->role->name == 'admin' || $user->role->name == 'super') {
                $lesson = Lesson::findOrFail($id);
            } else {
                $lesson = Lesson::where('school_id', $user->school->id)->findOrFail($id);
            }
            if($lesson){
                return (new \App\Http\Controllers\Admin\LessonController)->update($request, $id);
            }
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = auth()->user();
            if($user->role->name == 'admin' || $user->role->name == 'super') {
                $lesson = Lesson::findOrFail($id);
            } else {
                $lesson = Lesson::where('school_id', $user->school->id)->findOrFail($id);
            }
            if($lesson){
                $lesson->delete();
            }
            return response()->json(['message' => 'Lesson deleted successfully'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
}
