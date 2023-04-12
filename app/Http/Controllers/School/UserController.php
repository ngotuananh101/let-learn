<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;

class UserController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        try {
            $school = School::all()->where('slug', $slug)->firstOrFail();
            if ($this->isOwner($school->id)) {
                $user = $school->users;
                $manager = [];
                $teacher = [];
                $student = [];
                foreach ($user as $key => $value) {
                    if ($value->role->name === 'manager') {
                        $manager[] = $value;
                    } elseif ($value->role->name === 'teacher') {
                        $teacher[] = $value;
                    } elseif ($value->role->name === 'student') {
                        $student[] = $value;
                    }
                }
                return response()->json([
                    'manager' => $manager,
                    'teacher' => $teacher,
                    'student' => $student
                ], 200);
            } else {
                return response()->json([
                    'message' => 'You are not the owner of this school'
                ], 400);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
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
     * Check if the user is the owner of the school
     */
    public function isOwner(int $id)
    {
        $user = auth()->user();
        // if user is admin or super admin
        if ($user->role->name === 'admin' || $user->role->name === 'super') {
            return true;
        }
        $school = $user->school;
        if ($school) {
            if ($school->id === $id) {
                return true;
            }else{
                return false;
            }
        } else {
            return false;
        }
    }
}
