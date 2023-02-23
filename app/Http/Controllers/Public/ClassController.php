<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $classes = Classes::where('status', 'active')
                ->with(['students', 'teachers', 'sets', 'folders'])
                ->get();

            return response()->json($classes);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
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
        try {
            return view('classes.create');
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
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
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'status' => 'required|in:active,inactive'
            ]);

            // Tao set moi
            $class = new Classes();
            $class->name = $request->name;
            $class->description = $request->description;
            $class->status = $request->status;
            $class->save();

            // return json response
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Create class successfully!',
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
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $class = Classes::findOrFail($id);
            if ($class->status == 'inactive') {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 404,
                    'message' => 'Class not found!'
                ], 404);
            }
            $sets = $class->sets;
            $folders = $class->folders;
            $students = $class->students;
            $teachers = $class->teachers;

            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Get set successfully!',
                'data' => [
                    'class' => $class,
                    'sets' => $sets,
                    'folders' => $folders,
                    'students' => $students,
                    'teachers' => $teachers
                ]
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
        try {
            $class = Classes::findOrFail($id);

            return view('classes.edit', compact('class'));
        }catch (\Illuminate\Database\Eloquent\ModelNotFoundException $ex) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 404,
                    'message' => 'Class not found!',
                ], 404);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
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
            $class = Classes::findOrFail($id);

            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'status' => 'required|string|max:255',
            ]);

            $class->name = $validatedData['name'];
            $class->description = $validatedData['description'];
            $class->status = $validatedData['status'];
            $class->updated_at = now();
            $class->save();

            return response()->json([
                'message' => 'Class updated successfully',
                'class' => $class
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $ex) {
            return response()->json([
                'status' => 'error',
                'status_code' => 404,
                'message' => 'Class not found!',
            ], 404);
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
    public function destroy($id)
    {
        try {
            $class = Classes::findOrFail($id);
            $class->delete();

            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Class deleted successfully!',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $ex) {
            return response()->json([
                'status' => 'error',
                'status_code' => 404,
                'message' => 'Class not found!',
            ], 404);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
