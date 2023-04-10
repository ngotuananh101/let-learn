<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            return response()->json([
                'roles' => Role::all(),
                'permissions' => Permission::all(),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'permission' => 'required|array',
            ]);
            $role = Role::create([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
            ]);
            $role->permissions()->attach($request->input('permission'));
            return response()->json([
                'message' => 'Created role successfully',
                'role' => $role,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            // Get list user by role id
            $role = Role::with('users')->with('permissions')->find($id);
            return response()->json([
                'role' => $role,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // Validate type
            $request->validate([
                'type' => 'required|string|in:role,assigned,unassigned',
            ]);
            $type = $request->input('type');
            switch ($type) {
                case 'role':
                    return $this->role($request, $id);
                    break;
                case 'assigned':
                    return $this->assigned($request);
                    break;
                case 'unassigned':
                    return $this->unassigned($request);
                    break;
                default:
                    break;
            }
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
    public function role($request, $id): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'permission' => 'required|array',
            ]);
            $role = Role::findOrFail($id);
            if($role->name == 'super' || $role->name == 'admin'){
                return response()->json([
                    'message' => 'You can not change ' . $role->name . ' role',
                ], 400);
            } else {
                $role->update([
                    'name' => $request->input('name'),
                    'description' => $request->input('description'),
                ]);
                $role->permissions()->sync($request->input('permission'));
            }
            return response()->json([
                'message' => 'Updated role successfully',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function assigned($request)
    {
        try {
            $user = User::where('email', $request->input('email'))->firstOrFail();
            $request->merge(['user_id' => $user->id]);
            $check = $this->checkUserPer($request);
            if ($check) {
                $user_id = $request->input('user_id');
                $role_id = $request->input('role_id');
                $user = User::find($user_id);
                $user->update([
                    'role_id' => $role_id,
                ]);
                return response()->json([
                    'message' => 'Assigned role successfully',
                ]);
            } else {
                return response()->json([
                    'message' => 'You can not assigned this user',
                ], 400);
            }
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function unassigned($request): JsonResponse
    {
        try {
            $check = $this->checkUserPer($request);
            if ($check) {
                $user_id = $request->input('user_id');
                $user = User::find($user_id);
                $user->update([
                    'role_id' => Role::where('name', 'user')->first()->id,
                ]);
                return response()->json([
                    'message' => 'Unassigned role successfully',
                ]);
            } else {
                return response()->json([
                    'message' => 'You can not unassigned this user',
                ], 400);
            }
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function checkUserPer($request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'role_id' => 'required|integer|exists:roles,id',
            'type' => 'required|string|in:role,assigned,unassigned',
        ]);
        $user_id = $request->input('user_id');
        $role_id = $request->input('role_id');
        $type = $request->input('type');

        $user = User::find($user_id);
        $role = Role::find($role_id);

        if ($type == 'unassigned') {
            if (!$user->role || $user->role->name !== $role->name) {
                return false;
            }

            if (in_array($role->name, ['admin', 'super']) && $role->users->count() <= 1) {
                $auth_role_name = auth()->user()->role->name;

                if ($auth_role_name === 'super') {
                    return true;
                }

                if ($auth_role_name === 'admin' && $role->name === 'super') {
                    return false;
                }
            }

            return true;
        }

        if ($type == 'assigned') {
            if ($user->role && $user->role->name === $role->name) {
                return false;
            }

            if (in_array($role->name, ['admin', 'super'])) {
                $auth_role_name = auth()->user()->role->name;

                return $auth_role_name === 'super' || ($auth_role_name === 'admin' && $role->name !== 'super');
            }

            return true;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $role = Role::findOrFail($id);
            if($role->id <= 6){
                return response()->json([
                    'message' => 'You can not delete ' . $role->name . ' role',
                ], 400);
            } else {
                $role->delete();
                return response()->json([
                    'message' => 'Deleted role successfully',
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
