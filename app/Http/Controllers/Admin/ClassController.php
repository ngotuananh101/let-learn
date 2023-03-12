<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Classes;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class ClassController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permissions:admin.classes')->only(['index']);
        $this->middleware('permissions:admin.classes.create')->only(['store']);
        $this->middleware('permissions:admin.classes.edit')->only(['update']);
        $this->middleware('permissions:admin.classes.delete')->only(['destroy']);
    }


    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            // get all classes
            $classes = Classes::all();
            $classes = $classes->map(function ($class) {
                return [
                    $class->id,
                    $class->name,
                    $class->description,
                    $class->status,
                    $class->school->name,
                ];
            });
            // Return json
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'data' => $classes
            ]);
        } catch (\Exception $e) {
            // Return json
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $e->getMessage()
            ]);
        }
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
                'name' => 'required|string',
                'description' => 'required|string',
                'status' => 'required|in:active,inactive',
                'school_id' => 'required|exists:schools,id',
            ]);

            $class = Classes::create([
                'name' => $request->name,
                'description' => $request->description,
                'status' => $request->status,
                'school_id' => $request->school_id,
            ]);

            // Return json
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Class created successfully'
            ], 200);
        } catch (\Exception $e) {
            // Return json
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function show(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'type' => 'required|in:all,managers,teachers,students,search_user',
                'keyword' => 'required_if:type,search_user|string',
            ]);
            // get class
            $class = Classes::findOrFail($id);
            $data = match ($request->type) {
                'all' => [
                    'class' => $class,
                    //get school of class
                    'school' => $class->school,
                ],
                // 'search_user' => User::where('email', 'like', '%' . $request->keyword . '%')
                //     ->orWhere('username', 'like', '%' . $request->keyword . '%')
                //     ->get(),
                default => throw new \Exception('Invalid type'),
            };
            // Return json
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            // Return json
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
                'type' => 'required|in:class,add_manager,remove_manager',
            ]);
            $class = Classes::findOrFail($id);
            switch ($request->type) {
                case 'class':
                    $request->validate([
                        'name' => 'required|string',
                        'description' => 'required|string',
                        'status' => 'required|in:active,inactive',
                    ]);
                    $class->name = $request->name;
                    $class->description = $request->description;
                    $class->status = $request->status;
                    $class->save();                    
                    break;                
                default:
                    throw new \Exception('Invalid type');
            }
            // Return json
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Class updated successfully'
            ], 200);
        } catch (\Exception $e) {
            // Return json
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            // delete class by id (soft delete by set status to inactive)
            $class = Classes::find($id);
            if ($class) {
                $class->status = 'inactive';
                return response()->json([
                    'status' => 'success',
                    'status_code' => 200,
                    'message' => 'Class deleted successfully'
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 404,
                    'message' => 'Class not found'
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
