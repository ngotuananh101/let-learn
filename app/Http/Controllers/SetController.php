<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Set;
use App\Models\SetDetail;

class SetController extends Controller
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
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|unique:sets',
                'description' => 'string',
                'is_public' => 'required|boolean',
                'password' => 'required|string',
                'data' => 'required'
            ]);
            $set = new Set([
                'user_id' => auth() -> user_id,
                'name' => $request -> uniqid('newSet_'),
                'is_public' => $request -> boolval(1),
                'description' => $request -> description
            ]);
            $set->save();

            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Add new set successfully!',
                'data' => $set
            ], 200);
            $user = auth()->user();
            dd($user);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage('Add new set fail!')
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $set = Set::findOrFail($id);
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Show set successfully!',
                'data' => $set
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage('Show set fail!')
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
    public function update(Request $request, $id)
    {
        try {
            $set = Set::findOrFail($id);
            $set -> update($request -> all());
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Update set successfully!',
                'data' => $set
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage('Update set fail!')
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $set = Set::findOrFail($id);
            $set -> update('status' => 'inactive');
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Delete set successfully!'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage("Delete set fail!")
            ], 500);
        }
    }
}
