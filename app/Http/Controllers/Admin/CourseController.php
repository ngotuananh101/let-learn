<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class CourseController extends Controller
{
    /**
     * Get the list of folders
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            // Get the list of folders
            $folders = Course::all();
            $folders = $folders->map(function ($folder) {
                return [
                    $folder->id,
                    $folder->name,
                    $folder->description,
                    $folder->user->username,
                    $folder->is_public ? 'yes' : 'no',
                    Carbon::parse($folder->created_at)->format('d/m/Y'),
                    Carbon::parse($folder->updated_at)->format('d/m/Y'),
                    $folder->status,
                ];
            });
            // Return json
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'data' => $folders
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
     * Show the form for creating a new resource.
     *
     * @return Response
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
        // validate the request
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'is_public' => 'required|in:1,0',
            'status' => 'required|in:active,inactive',
            'password' => 'nullable',
        ]);

        try {
            // Create the folder
            $folder = Course::create([
                'name' => $request->name,
                'description' => $request->description,
                'is_public' => $request->is_public,
                'status' => $request->status,
                'password' => $request->password,
                'user_id' => auth()->user()->id,
            ]);

            $folder = [
                $folder->id,
                $folder->name,
                $folder->description,
                $folder->user->username,
                $folder->is_public ? 'yes' : 'no',
                Carbon::parse($folder->created_at)->format('d/m/Y'),
                Carbon::parse($folder->updated_at)->format('d/m/Y'),
                $folder->status,
            ];

            // Return json
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Course created successfully',
                'data' => $folder
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
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Get the folder details by id
     *
     * @param int $id
     * @return JsonResponse
     */
    public function edit($id)
    {
        try {
            // Get the folder details
            $folder = Course::findOrFail($id);
            // Get lesson in folder
            $set = $folder->sets;
            $set = $set->map(function ($set) {
                return [
                    $set->id,
                    $set->name,
                    $set->description,
                    $set->user->username,
                    $set->status,
                ];
            });
            // Return json
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'data' => [
                    'folder' => $folder,
                    'sets' => $set
                ]
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
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'type' => 'required|in:update_folder,find_set,add_set,remove_set',
            ]);
            switch ($request->type) {
                case 'update_folder':
                    $request->validate([
                        'name' => 'required|string',
                        'description' => 'required|string',
                        'is_public' => 'required|in:1,0',
                        'status' => 'required|in:active,inactive',
                        'password' => 'nullable',
                    ]);
                    // Update the folder
                    $folder = Course::findOrFail($id);
                    $folder->update([
                        'name' => $request->name,
                        'description' => $request->description,
                        'is_public' => $request->is_public,
                        'status' => $request->status,
                        'password' => $request->password,
                    ]);
                    break;

                case 'find_set':
                    $request->validate([
                        'search' => 'required|string',
                    ]);
                    // Find lesson
                    $set = Lesson::where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('description', 'like', '%' . $request->search . '%')
                        ->orWhere('id', 'like', '%' . $request->search . '%')
                        ->orWhereHas('user', function ($query) use ($request) {
                            $query->where('username', 'like', '%' . $request->search . '%');
                        })
                        ->get();
                    $set = $set->map(function ($set) {
                        return [
                            'value' => $set->id,
                            'label' => $set->id . ' - ' . $set->name . ' - ' . $set->user->username,
                        ];
                    });
                    // Return json
                    return response()->json([
                        'status' => 'success',
                        'status_code' => 200,
                        'data' => $set
                    ]);
                    break;
                case 'add_set':
                    $request->validate([
                        'set_id' => 'required|exists:sets,id',
                    ]);
                    // Add lesson to folder
                    $folder = Course::findOrFail($id);
                    $set = Lesson::findOrFail($request->set_id);
                    $folder->sets()->attach($set);
                    $data[] = [
                        $set->id,
                        $set->name,
                        $set->description,
                        $set->user->username,
                        $set->status,
                    ];

                    return response()->json([
                        'status' => 'success',
                        'status_code' => 200,
                        'data' => $data
                    ]);

                    break;
                case 'remove_set':
                    $request->validate([
                        'set_ids' => 'required|array',
                    ]);
                    // Remove lesson from folder
                    $folder = Course::findOrFail($id);
                    $folder->sets()->detach($request->set_ids);
                    break;
            }

            // Return json
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Course updated successfully',
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
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            // Delete the folder
            Course::destroy($id);

            // Return json
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Course deleted successfully',
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
}
