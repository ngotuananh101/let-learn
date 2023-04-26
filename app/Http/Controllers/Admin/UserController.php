<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'type' => 'required|in:all,active,inactive',
            ]);
            return match ($request->type) {
                'all' => response()->json(User::with('role')->get()),
                'active' => response()->json([
                    'users' => User::where('status', 'active')->get(),
                ]),
                'inactive' => response()->json([
                    'users' => User::where('status', 'inactive')->get(),
                ]),
                default => response()->json([
                    'message' => 'Invalid type',
                ], 400),
            };
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'role_id' => 'required|exists:roles,id',
                'name' => 'required|string',
                'username' => 'required|string|unique:users,username',
                'email' => 'required|email|unique:users,email',
                'date_of_birth' => 'required|date',
                'email_verified_at' => 'nullable|date',
                'password' => 'required|string|min:8|confirmed',
                'status' => 'required|in:active,inactive',
            ]);
            $authUser = auth()->user();
            if ($authUser->role->id !== 1 && $request->role_id === 1) {
                return response()->json([
                    'message' => 'You are not authorized to create super admin',
                ], 403);
            } else {
                $request->merge([
                    'password' => bcrypt($request->password),
                ]);
                $request->email_verified_at ?? $request->merge([
                    'email_verified_at' => now(),
                ]);
                $user = User::create($request->all());
                return response()->json([
                    'message' => 'User created successfully',
                    'user' => $user->load('role'),
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $request->validate([
                'role_id' => 'required|exists:roles,id',
                'name' => 'required|string',
                'username' => 'required|string|unique:users,username,' . $id,
                'email' => 'required|email|unique:users,email,' . $id,
                'date_of_birth' => 'required|date',
                'email_verified_at' => 'nullable|date',
                'password' => 'nullable|string|min:8|confirmed',
                'status' => 'required|in:active,inactive',
            ]);
            $authUser = auth()->user();
            $user = User::findOrfail($id);
            if ($authUser->role->id !== 1 && $request->role_id === 1) {
                return response()->json([
                    'message' => 'You are not authorized to update super admin',
                ], 403);
            } else {
                if ($request->password) {
                    $request->merge([
                        'password' => bcrypt($request->password),
                    ]);
                } else {
                    $request->merge([
                        'password' => $user->password,
                    ]);
                }
//                dd($request->all());
                $user->update($request->all());
                return response()->json([
                    'message' => 'User updated successfully',
                    'user' => $user->load('role'),
                ]);
            }
        } catch (Exception $e) {
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
            $authUser = auth()->user();
            $user = User::findOrfail($id);
            if ($authUser->role->id !== 1 && $user->role->id === 1) {
                return response()->json([
                    'message' => 'You are not authorized to delete super admin',
                ], 403);
            } else {
                $user->delete();
                return response()->json([
                    'message' => 'User deleted successfully',
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
