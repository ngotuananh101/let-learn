<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {

            // get all permissions
            if ($request->type === 'all_permissions') {
                $permissions = Permission::all();
                $permissions = $permissions->map(function ($permission) {
                    return [
                        'value' => $permission->id,
                        'label' => $permission->name,
                    ];
                });
                // return response
                return response()->json([
                    'status' => 'success',
                    'data' => $permissions
                ], 200);
            }

            // get all roles
            $roles = Role::all();
            $roles = $roles->map(function ($role) {
                return [
                    $role->id,
                    $role->name,
                    $role->description,
                    $role->status,
                    $role->updated_at,
                ];
            });

            // return response
            return response()->json([
                'status' => 'success',
                'data' => $roles
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'permissions' => 'required|array',
            ]);

            // create role
            $role = Role::create([
                'name' => $request->name,
                'description' => $request->description,
            ]);
            // attach permissions
            $role->permissions()->attach($request->permissions);
            $role = [
                $role->id,
                $role->name,
                $role->description,
                $role->status,
                $role->updated_at,
            ];

            // return response
            return response()->json([
                'status' => 'success',
                'message' => 'Role created successfully',
                'data' => $role
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(Request $request, int $id): JsonResponse
    {
        try {
            $request->validate([
                'type' => 'required|in:permissions,users'
            ]);

            // get role
            $role = Role::findOrFail($id);
            // get role permissions
            if ($request->type === 'permissions') {
                $permissions = $role->permissions()->get();
                $permissions = $permissions->map(function ($permission) {
                    return [
                        $permission->id,
                        $permission->name,
                        $permission->description,
                    ];
                });

                // return response
                return response()->json([
                    'status' => 'success',
                    'data' => $permissions
                ], 200);
            }

            // get role users
            if ($request->type === 'users') {
                $users = $role->users()->get();
                $users = $users->map(function ($user) {
                    return [
                        $user->id,
                        $user->username,
                        $user->email,
                        $user->status,
                        Carbon::parse($user->created_at)->format('d/m/Y'),
                    ];
                });

                // return response
                return response()->json([
                    'status' => 'success',
                    'data' => $users
                ], 200);
            }

            // return response
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid request'
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function edit(int $id): JsonResponse
    {
        try {
            // get role info
            $role = Role::findOrFail($id);
            // get role permissions
            $permissions = $role->permissions()->get();
            // convert
            $role = [
                $role->id,
                $role->name,
                $role->description,
            ];

            $permissions = $permissions->map(function ($permission) {
                return [
                    $permission->id,
                    $permission->name,
                    $permission->description,
                ];
            });

            // return response
            return response()->json([
                'status' => 'success',
                'data' => [
                    'role' => $role,
                    'permissions' => $permissions
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'permissions' => 'required|array',
            ]);

            // get role
            $role = Role::findOrFail($id);
            // update role
            $role->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);
            // sync permissions
            $role->permissions()->sync($request->permissions);
            $role = [
                $role->id,
                $role->name,
                $role->description,
                $role->status,
                $role->updated_at,
            ];

            // return response
            return response()->json([
                'status' => 'success',
                'message' => 'Role updated successfully',
                'data' => $role
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try{
            // get role
            $role = Role::findOrFail($id);
            if($role->name === 'super' || $role->name === 'admin' || $role->name === 'user' || $role->name === 'manager' || $role->name === 'teacher' || $role->name === 'student'){
                return response()->json([
                    'status' => 'error',
                    'message' => 'You can not delete this role'
                ], 400);
            }
            // delete role
            $role->delete();

            // return response
            return response()->json([
                'status' => 'success',
                'message' => 'Role deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
