<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\School;
use App\Models\User;
use Carbon\Carbon;
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
        try {
            $request->validate([
                'school_slug' => 'required|exists:schools,slug',
                'type' => 'required|in:one,multiple',
            ]);
            $school = School::all()->where('slug', $request->school_slug)->firstOrFail();
            if ($this->isOwner($school->id)) {
                if ($request->type === 'one') {
                    $request->validate([
                        'role_id' => 'required|exists:roles,id',
                        'name' => 'required|string',
                        'username' => 'required|string|unique:users,username',
                        'email' => 'required|email|unique:users,email',
                        'date_of_birth' => 'required|date|before:today',
                        'email_verified_at' => 'nullable|date',
                        'password' => 'required|string|min:8|confirmed',
                        'status' => 'required|in:active,inactive',
                    ]);
                    $user = $school->users()->create([
                        'role_id' => $request->role_id,
                        'name' => $request->name,
                        'username' => $request->username,
                        'email' => $request->email,
                        'date_of_birth' => $request->date_of_birth,
                        'email_verified_at' => $request->email_verified_at ? $request->email_verified_at : Carbon::now(),
                        'password' => bcrypt($request->password),
                        'status' => $request->status,
                    ]);
                    return response()->json([
                        'message' => 'User created successfully',
                        'user' => $user
                    ], 200);
                } else {
                    $request->validate([
                        'users' => 'required|array',
                    ]);
                    foreach ($request->input('users') as $user) {
                        $school->users()->create([
                            'name' => $user[1],
                            'username' => $user[2],
                            'email' => $user[3],
                            'role_id' => Role::all()->where('name', $user[4])->firstOrFail()->id,
                            'date_of_birth' => Carbon::createFromFormat('d/m/Y', $user[5]),
                            'email_verified_at' => Carbon::now(),
                            'password' => bcrypt($user[6]),
                        ]);
                    }
                    return response()->json([
                        'message' => 'Users created successfully'
                    ], 200);
                }
            } else {
                return response()->json([
                    'message' => 'You do not have permission to create user'
                ], 400);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
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
        try {
            $school = School::all()->where('slug', $id)->firstOrFail();
            if ($this->isOwner($school->id)) {
                $request->validate([
                    'role_id' => 'required|exists:roles,id',
                    'name' => 'required|string',
                    'username' => 'required|string|unique:users,username,' . $request->input('id'),
                    'email' => 'required|email|unique:users,email,' . $request->input('id'),
                    'date_of_birth' => 'required|date|before:today',
                    'email_verified_at' => 'nullable|date',
                    'password' => 'nullable|string|min:8|confirmed',
                    'status' => 'required|in:active,inactive',
                ]);
                $user = User::all()->where('id', $request->input('id'))->firstOrFail();
                // check if user is manager
                if ($user->role->name === 'manager') {
                    // check if user is the owner of the school
                    if ($user->school->id === $school->id) {
                        // check if user is the only manager
                        if ($school->users->where('role_id', $user->role->id)->count() === 1) {
                            return response()->json([
                                'message' => 'You cannot update the only manager'
                            ], 400);
                        } else {
                            $user->update([
                                'role_id' => $request->role_id,
                                'name' => $request->name,
                                'username' => $request->username,
                                'email' => $request->email,
                                'date_of_birth' => $request->date_of_birth,
                                'email_verified_at' => $request->email_verified_at ? $request->email_verified_at : Carbon::now(),
                                'password' => $request->password ? bcrypt($request->password) : $user->password,
                                'status' => $request->status,
                            ]);
                        }
                    } else {
                        return response()->json([
                            'message' => 'You do not have permission to update user'
                        ], 400);
                    }
                } else {
                    $user->update([
                        'role_id' => $request->role_id,
                        'name' => $request->name,
                        'username' => $request->username,
                        'email' => $request->email,
                        'date_of_birth' => $request->date_of_birth,
                        'email_verified_at' => $request->email_verified_at ? $request->email_verified_at : Carbon::now(),
                        'password' => $request->password ? bcrypt($request->password) : $user->password,
                        'status' => $request->status,
                    ]);
                }

                return response()->json([
                    'message' => 'User updated successfully',
                ], 200);
            } else {
                return response()->json([
                    'message' => 'You do not have permission to update user'
                ], 400);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);
            $auth = auth()->user();
            if ($auth->role->name === 'admin' || $auth->role->name === 'super') {
                $user->delete();
                return response()->json([
                    'message' => 'User deleted successfully'
                ], 200);
            } else {
                if ($user->school->id === $auth->school->id) {
                    if ($user->role->name === 'manager') {
                        if ($user->school->users->where('role_id', $user->role->id)->count() === 1) {
                            return response()->json([
                                'message' => 'You cannot delete the only manager'
                            ], 400);
                        } else {
                            $user->delete();
                            return response()->json([
                                'message' => 'User deleted successfully'
                            ], 200);
                        }
                    } else {
                        $user->delete();
                        return response()->json([
                            'message' => 'User deleted successfully'
                        ], 200);
                    }
                } else {
                    return response()->json([
                        'message' => 'You do not have permission to delete user'
                    ], 400);
                }
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
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
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
