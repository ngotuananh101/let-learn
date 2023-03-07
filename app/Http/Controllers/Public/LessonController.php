<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Course;
use App\Models\LessonDetail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


class LessonController extends Controller
{
    public function learn(Request $request, $lesson_id)
    {
        try {
            $reverse = request()->input('reverse', false);
            $lesson = Lesson::findOrFail($lesson_id);
            $lessonDetails = $lesson->lessonDetail()->get();

            $response = ['lesson_id' => $lesson_id, 'lesson_details' => []];

            foreach ($lessonDetails as $lessonDetail) {
                $term = $lessonDetail->term;
                $definition = $lessonDetail->definition;

                if ($reverse) {
                    $temp = $term;
                    $term = $definition;
                    $definition = $temp;
                }

                $answers = [];

                // replace special characters with a space character
                $term = preg_replace('/[\n\r\t]+/', ' ', $term);

                // check if the term is a multiple choice question
                // check if the term is a multiple choice question
                if (preg_match('/^(.*?)\s*[a-z]\.\s*(.*)/is', $term, $matches)) {
                    $question = trim($matches[1]);
                    $options_str = $matches[2];
                    $options = preg_split('/\s*[a-z]\.\s*/i', $options_str, -1, PREG_SPLIT_NO_EMPTY);
                    $answers = array_map('trim', $options);
                    $correct_answer = trim($definition);
                }
                // check if the term is a true/false question
                elseif (preg_match('/^(.*)\s*[a-z]\.\s*(True|False)/is', $term, $matches)) {
                    $question = trim($matches[1]);
                    $answers = array_map('trim', [$matches[2], $matches[3]]);
                    $correct_answer = trim($definition);
                }
                // if the term is neither multiple choice nor true/false
                else {
                    $question = $term;
                    $correct_answer = trim($definition);

                    // get three other definitions from the current lesson
                    if ($reverse) {
                        $otherDefs = $lesson->lessonDetail()->where('id', '<>', $lessonDetail->id)
                            ->inRandomOrder()->take(3)->pluck('term')->toArray();
                    }else {
                        $otherDefs = $lesson->lessonDetail()->where('id', '<>', $lessonDetail->id)
                        ->inRandomOrder()->take(3)->pluck('definition')->toArray();
                    }
                    //if reverse is true, $otherDefs will be an array of terms
                    
                    // add the correct answer to the other definitions
                    array_push($otherDefs, $correct_answer);

                    // shuffle the answer options
                    shuffle($otherDefs);

                    // set the answer options to the shuffled definitions
                    $answers = $otherDefs;
                }

                $response['lesson_details'][] = [
                    'id' => $lessonDetail->id,
                    'question' => $question,
                    'answers' => $answers,
                    'correct_answer' => $correct_answer,
                ];
            }
            return response()->json($response);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function import(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'type' => 'required|in:text,file',
                'name' => 'required|string',
                'description' => 'required|string',
                'text' => 'required_if:type,text|nullable',
                'term_separator' => 'required_if:type,text',
                'set_separator' => 'required_if:type,text',
                'file' => 'required_if:type,file|mimes:xls,xlsx,csv',
            ]);
            $type = $request->input('type');
            // Tao set moi
            $lesson = new Lesson();
            $lesson->name = $request->name;
            $lesson->description = $request->description;
            $lesson->user_id = auth()->user()->id;
            $lesson->save();
            if ($type == 'text') {
                $raw_detail = explode($request->set_separator, $request->text);
                if (count($raw_detail) < 3) {
                    // delete set
                    $lesson->delete();
                    return response()->json([
                        'status' => 'error',
                        'status_code' => 400,
                        'message' => 'Text must have more than 3 sets'
                    ], 400);
                }
                foreach ($raw_detail as $item) {
                    try {
                        $raw = explode($request->term_separator, $item);
                        $term = $raw[0];
                        $definition = $raw[1];
                        $lesson->lessonDetail()->create([
                            'term' => $term,
                            'definition' => $definition
                        ]);
                    } catch (\Throwable $th) {
                        continue;
                    }
                }
            } else if ($type == 'file') {
                $file = $request->file('file');
                $file_extension = $file->getClientOriginalExtension();
                if ($file_extension == 'xls' || $file_extension == 'xlsx') {
                    $data = Excel::toArray((object)[], $file);
                    // check data have more than 4 rows
                    if (count($data[0]) > 4) {
                        // check data have more than 2 columns
                        if (count($data[0][0]) > 2) {
                            foreach ($data[0] as $key => $item) {
                                if ($key >= 1) {
                                    $lesson->lessonDetail()->create([
                                        'term' => $item[1],
                                        'definition' => $item[2]
                                    ]);
                                }
                            }
                        } else {
                            // delete set
                            $lesson->delete();
                            return response()->json([
                                'status' => 'error',
                                'status_code' => 400,
                                'message' => 'File must have more than 2 columns'
                            ], 400);
                        }
                    } else {
                        // delete set
                        $lesson->delete();
                        return response()->json([
                            'status' => 'error',
                            'status_code' => 400,
                            'message' => 'File must have more than 4 rows'
                        ], 400);
                    }
                } else {
                    $csv = array_map('str_getcsv', file($file));
                    // check data have more than 4 rows
                    if (count($csv) > 4) {
                        // check data have 2 columns
                        if (count($csv[0]) > 2) {
                            foreach ($csv as $key => $item) {
                                if ($key >= 1) {
                                    $lesson->lessonDetail()->create([
                                        'term' => $item[1],
                                        'definition' => $item[2]
                                    ]);
                                }
                            }
                        } else {
                            // delete set
                            $lesson->delete();
                            return response()->json([
                                'status' => 'error',
                                'status_code' => 400,
                                'message' => 'File must have more than 2 columns ( no,term, definition,... )'
                            ], 400);
                        }
                    } else {
                        // delete set
                        $lesson->delete();
                        return response()->json([
                            'status' => 'error',
                            'status_code' => 400,
                            'message' => 'File must have more than 4 rows'
                        ], 400);
                    }
                }
            }
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Lesson created successfully',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function export(Request $request, $id): BinaryFileResponse|JsonResponse
    {
        try {
            $lesson = Lesson::findOrfail($id);
            $lessonData = $lesson->lessonDetail()->get()->toArray();
            if (empty($lessonData)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Lesson is empty'
                ], 400);
            }
            // write set data to file
            $file_name = 'set_' . $id . '_' . date('Ymd_His') . '.csv';
            $file = fopen($file_name, 'w');
            fputcsv($file, ['id', 'term', 'definition', 'image', 'audio', 'video', 'status', 'created_at', 'updated_at']);
            foreach ($lessonData as $line) {
                fputcsv($file, $line);
            }
            fclose($file);
            // set header
            $headers = [
                'Content-Type' => 'text/csv',
            ];
            // download file
            return response()->download($file_name, $file_name, $headers)->deleteFileAfterSend(true);
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
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'description' => 'required|string',
                'password' => 'nullable|string',
                'data' => 'required|array',
            ]);
            // add new lesson to user
            $user = auth()->user();
            $lesson = $user->lesson()->create([
                'name' => $request->name,
                'description' => $request->description,
                'password' => $request->password,
            ]);
            // add lesson details
            foreach ($request->data as $detail) {
                $lesson->lessonDetail()->create([
                    'term' => $detail['term'],
                    'definition' => $detail['definition'],
                ]);
            }
            // return json response
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Create lesson successfully!',
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
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $lesson = Lesson::findOrFail($id);
            // check lesson is deleted
            if ($lesson->status == 'inactive') {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 404,
                    'message' => 'Lesson not found!'
                ], 404);
            }
            $lessonDetail = $lesson->lessonDetail()->get();
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Get lesson successfully!',
                'data' => [
                    'lesson' => $lesson,
                    'detail' => $lessonDetail
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
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'description' => 'string',
                'status' => 'required|in:active,inactive',
                'is_public' => 'required|in:1,0',
                'password' => 'nullable|string',
                'data' => 'required|array',
            ]);
            $lesson = Lesson::findOrFail($id);
            $lesson->name = $request->name;
            $lesson->description = $request->description;
            $lesson->status = $request->status;
            $lesson->is_public = $request->is_public;
            $lesson->password = $request->password;
            $lesson->save();

            $lesson->lessonDetail()->delete();

            foreach ($request->data as $detail) {
                $lesson->lessonDetail()->create([
                    'term' => $detail['term'],
                    'definition' => $detail['definition'],
                ]);
            }

            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Update lesson successfully!',
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
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            $lesson = Lesson::findOrFail($id);
            // Soft delete lesson
            $lesson->update([
                'status' => 'inactive'
            ]);
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Delete lesson successfully!',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    //show all lesson by user id
    public function showAllLessonByUserId(): JsonResponse
    {
        try {
            $user = auth()->user();
            $lesson = $user->lesson()->where('status', 'active')->get();
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Get lesson successfully!',
                'data' => $lesson
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    // show all lesson by course id
    public function showAlllessonByCourseId($id): JsonResponse
    {
        try {
            $course = Course::findOrFail($id);
            $lesson = $course->lessons()->where('status', 'active')->get();
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Get lesson successfully!',
                'data' => $lesson
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    // show progress by lesson id
    public function showProgressByLessonId($id): JsonResponse
    {
        try {
            $lesson = Lesson::findOrFail($id);
            $lessonDetail = $lesson->lessonDetail()->get();
            $progress = 0;
            foreach ($lessonDetail as $detail) {
                if ($detail->is_learned == 1) {
                    $progress++;
                }
            }
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Get progress successfully!',
                'data' => [
                    'progress' => $progress,
                    'total' => count($lesson)
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
}
