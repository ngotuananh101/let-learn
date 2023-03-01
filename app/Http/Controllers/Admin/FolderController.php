<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Folder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class FolderController extends Controller
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
            $folders = Folder::all();
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
        }catch (\Exception $e) {
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
            $folder = Folder::create([
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
                'message' => 'Folder created successfully',
                'data' => $folder
            ]);
        }catch (\Exception $e) {
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
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
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
            Folder::destroy($id);

            // Return json
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Folder deleted successfully',
            ]);
        }
        catch (\Exception $e) {
            // Return json
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }
}
