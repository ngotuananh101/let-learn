<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\User;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SetController extends Controller
{
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
            $set = new Lesson();
            $set->name = $request->name;
            $set->description = $request->description;
            $set->user_id = auth()->user()->id;
            $set->save();

            if ($type == 'text') {
                $raw_detail = explode($request->set_separator, $request->text);
                foreach ($raw_detail as $item) {
                    try {
                        $raw = explode($request->term_separator, $item);
                        $term = $raw[0];
                        $definition = $raw[1];
                        $set->setDetails()->create([
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
                                    $set->setDetails()->create([
                                        'term' => $item[1],
                                        'definition' => $item[2]
                                    ]);
                                }
                            }
                        } else {
                            // delete set
                            $set->delete();
                            return response()->json([
                                'status' => 'error',
                                'status_code' => 400,
                                'message' => 'File must have more than 2 columns'
                            ], 400);
                        }
                    } else {
                        // delete set
                        $set->delete();
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
                                    $set->setDetails()->create([
                                        'term' => $item[1],
                                        'definition' => $item[2]
                                    ]);
                                }
                            }
                        } else {
                            // delete set
                            $set->delete();
                            return response()->json([
                                'status' => 'error',
                                'status_code' => 400,
                                'message' => 'File must have more than 2 columns ( no,term, definition,... )'
                            ], 400);
                        }
                    } else {
                        // delete set
                        $set->delete();
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
            $set = Lesson::findOrfail($id);
            $setData = $set->setDetails()->get()->toArray();
            if (empty($setData)) {
                return response()->json(['message' => 'No data found for this set']);
            }
            $fileName = 'set_' . $id . '_' . date('Ymd_His') . '.csv';
            $filePath = storage_path('app/export/set/' . $fileName);
            $file = fopen($filePath, 'w');
            fputcsv($file, ['Term', 'Definition', 'Image']);
            foreach ($setData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
            return response()->download($filePath, $fileName, ['Content-Type' => 'text/csv'])->deleteFileAfterSend(true);
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
            // add new set to user
            $set = auth()->user()->sets()->create([
                'name' => $request->name,
                'description' => $request->description,
                'password' => $request->password,
            ]);
            // add set details
            foreach ($request->data as $detail) {
                $set->setDetails()->create([
                    'term' => $detail['term'],
                    'definition' => $detail['definition'],
                ]);
            }
            // return json response
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Create set successfully!',
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
            $set = Lesson::findOrFail($id);
            // check set is deleted
            if ($set->status == 'inactive') {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 404,
                    'message' => 'Lesson not found!'
                ], 404);
            }
            $setdetail = $set->setDetails()->get();
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Get set successfully!',
                'data' => [
                    'set' => $set,
                    'detail' => $setdetail
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
            $set = Lesson::findOrFail($id);
            $set->name = $request->name;
            $set->description = $request->description;
            $set->status = $request->status;
            $set->is_public = $request->is_public;
            $set->password = $request->password;
            $set->save();

            $set->setDetails()->delete();

            foreach ($request->data as $detail) {
                $set->setDetails()->create([
                    'term' => $detail['term'],
                    'definition' => $detail['definition'],
                ]);
            }

            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Update set successfully!',
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
            $set = Lesson::findOrFail($id);
            // Soft delete set
            $set->update([
                'status' => 'inactive'
            ]);
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Delete set successfully!',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    //show all set by user id
    public function showAllSetByUserId(): JsonResponse
    {
        try {
            $user = auth()->user();
            $set = $user->sets()->where('status', 'active')->get();
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Get set successfully!',
                'data' => $set
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    // show all set by folder id
    public function showAllSetByFolderId($id): JsonResponse
    {
        try {
            $folder = Course::findOrFail($id);
            $set = $folder->sets()->where('status', 'active')->get();
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Get set successfully!',
                'data' => $set
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    // show progress by set id
    public function showProgressBySetId($id): JsonResponse
    {
        try {
            $set = Lesson::findOrFail($id);
            $setdetail = $set->setDetails()->get();
            $progress = 0;
            foreach ($setdetail as $detail) {
                if ($detail->progress == 'done') {
                    $progress++;
                }
            }
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Get progress successfully!',
                'data' => [
                    'progress' => $progress,
                    'total' => count($setdetail)
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
