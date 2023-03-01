<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Folder;
use App\Models\User;
use App\Models\Set;
use Illuminate\Http\JsonResponse;
use PhpOffice\PhpSpreadsheet\Reader\Xml\Style\Font;
use PhpParser\Node\Stmt\TryCatch;

class FolderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'description' => 'required|string',
                'password' => 'nullable|string',
            ]);
            // add new folder to user
            $folder = auth()->user()->folders()->create([
                'name' => $request->name,
                'description' => $request->description,
                'password' => $request->password,
            ]);
            $folder->save();
            // return json response
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Folder created successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response: JsonResponse
     */
    public function show($id)
    {
        try {
            $folder = Folder::findOrFail($id);
            // check folder is deleted
            if ($folder->status == 'inactive') {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 404,
                    'message' => 'Folder not found'
                ], 404);
            }
            // check folder is public
            if ($folder->is_public == false && auth()->user()->id != $folder->user_id) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 403,
                    'message' => 'You don\'t have permission to access this folder'
                ], 403);
            }
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Get set successfully!',
                'data' => $folder
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'description' => 'required|string',
                'status' => 'required|in:active,inactive',
                'is_public' => 'required|in:1,0',
                'password' => 'nullable|string',
            ]);
            $folder = Folder::findOrFail($id);
            // update folder
            $folder->update([
                'name' => $request->name,
                'description' => $request->description,
                'status' => $request->status,
                'is_public' => $request->is_public,
                'password' => $request->password,
            ]);
            $folder->save();

            // return json response
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Update folder successfully!',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): JsonResponse
    {
        try {
            $folder = Folder::findOrFail($id);
            // Soft delete folder
            $folder->update([
                'status' => 'inactive'
            ]);
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Delete folder successfully!',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    // show all folder by user id
    public function showAllFolderByUserId(): JsonResponse
    {
        try {
            $user = auth()->user();
            $folders = $user->folders()->where('status', 'active')->get();
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'data' => $folders
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    //add set to folder by set id and folder id
    public function addSetToFolder(Request $request, $id, $set_id): JsonResponse
    {
        try {
            $folder = Folder::findOrFail($id);
            // check folder is deleted
            if ($folder->status == 'inactive') {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 404,
                    'message' => 'Folder not found'
                ], 404);
            }
            // check folder is public
            if ($folder->is_public == false && auth()->user()->id != $folder->user_id) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 403,
                    'message' => 'You don\'t have permission to access this folder'
                ], 403);
            }
            // check set is deleted
            $set = Set::findOrFail($set_id);
            if ($set->status == 'inactive') {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 404,
                    'message' => 'Set not found'
                ], 404);
            }
            // check set is public
            if ($set->is_public == false && auth()->user()->id != $set->user_id) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 403,
                    'message' => 'You don\'t have permission to access this set'
                ], 403);
            }
            // check set is exist in folder
            $checkSet = $folder->sets()->where('set_id', $set_id)->first();
            if ($checkSet) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 400,
                    'message' => 'Set is exist in folder'
                ], 400);
            }
            // add set to folder
            $folder->sets()->attach($set_id);
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Add set to folder successfully!',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    // remove set from folder by set id and folder id
    public function removeSetFromFolder(Request $request, $id, $set_id): JsonResponse
    {
        try {
            $folder = Folder::findOrFail($id);
            // check folder is deleted
            if ($folder->status == 'inactive') {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 404,
                    'message' => 'Folder not found'
                ], 404);
            }
            // check folder is public
            if ($folder->is_public == false && auth()->user()->id != $folder->user_id) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 403,
                    'message' => 'You don\'t have permission to access this folder'
                ], 403);
            }
            // check set is deleted
            $set = Set::findOrFail($set_id);
            if ($set->status == 'inactive') {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 404,
                    'message' => 'Set not found'
                ], 404);
            }
            // check set is public
            if ($set->is_public == false && auth()->user()->id != $set->user_id) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 403,
                    'message' => 'You don\'t have permission to access this set'
                ], 403);
            }
            // check set is exist in folder
            $checkSet = $folder->sets()->where('set_id', $set_id)->first();
            if (!$checkSet) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 400,
                    'message' => 'Set is not exist in folder'
                ], 400);
            }
            // remove set from folder
            $folder->sets()->detach($set_id);
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Remove set from folder successfully!',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage(),
            ],
                500);
        }
    }
}
