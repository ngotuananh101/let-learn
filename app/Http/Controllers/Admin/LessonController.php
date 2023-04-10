<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LessonController extends Controller
{
    //
    public function index(): JsonResponse
    {
        try {
            $lessons = Lesson::all()->load(['school', 'classes']);
            return response()->json([
                'lessons' => $lessons,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|unique:lessons,name',
                'description' => 'required|string|max:100',
                'details' => 'required|array',
            ]);
            if($request->has('password') && $request->password) {
                $request->validate([
                    'password' => 'required|string|min:6',
                ]);
                $request->merge([
                    'password' => bcrypt($request->password),
                ]);
            }
            $lesson = auth()->user()->lessons()->create($request->all());
            $lesson->details()->createMany($request->details);

            return response()->json([
                'message' => 'Lesson created successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function show(Request $request, $id): JsonResponse
    {
        try {
            $lesson = Lesson::findOrFail($id)->load(['details']);
            return response()->json([
                'lesson' => $lesson,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function update(Request $request, $id){
        try {
            $request->validate([
                'name' => 'required|string',
                'description' => 'required|string|max:100',
                'details' => 'required|array',
            ]);
            if($request->has('password') && $request->password) {
                $request->validate([
                    'password' => 'required|string|min:6',
                ]);
                $request->merge([
                    'password' => bcrypt($request->password),
                ]);
            }
            $lesson = Lesson::findOrFail($id);
            $lesson->update($request->all());
            if ($request->input('removed_ids')) {
                $lesson->details()->whereIn('id', $request->input('removed_ids'))->delete();
            }
            // update lesson detail
            foreach ($request->input('details') as $item) {
                $lesson->details()->updateOrCreate(
                    ['id' => $item['id']],
                    [
                        'term' => $item['term'],
                        'definition' => $item['definition'],
                    ]
                );
            }
            return response()->json([
                'message' => 'Lesson updated successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
