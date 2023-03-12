<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class LessonController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permissions:admin.lessons')->only(['index']);
    }

    /**
     * Return list of all set
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $set = Lesson::all();
            $set = $set->map(function ($item) {
                return [
                    "" . $item->id,
                    $item->name,
                    $item->description,
                    $item->user->username,
                    $item->is_public ? 'public' : 'private',
                    Carbon::parse($item->created_at)->format('d/m/Y'),
                    Carbon::parse($item->updated_at)->format('d/m/Y'),
                    $item->status,
                ];
            });


            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'data' => $set
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
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
                'type' => 'required|in:general,text,file',
                'name' => 'required|string',
                'description' => 'required|string',
                'detail' => 'required_if:type,general|array',
                'raw_data' => 'required_if:type,text|string',
                'detail_separator' => 'required_if:type,text|string',
                'term_separator' => 'required_if:type,text|string',
                'file' => 'required_if:type,file|file|mimes:xls,xlsx,csv',
            ]);
            $set = auth()->user()->sets()->create([
                'name' => $request->name,
                'description' => $request->description,
                'password' => $request->password,
            ]);
            switch ($request->type) {
                case 'general':
                    $details = $request->detail;
                    // request have more or equal 3 details
                    if (count($details) >= 3) {
                        // add set detail
                        $set->setDetails()->createMany($details);
                    } else {
                        // delete set
                        $set->delete();
                        return response()->json([
                            'status' => 'error',
                            'status_code' => 400,
                            'message' => 'Lesson must have more or equal 3 details'
                        ], 400);
                    }
                    break;
                case 'text':
                    $raw_detail = explode($request->detail_separator, $request->raw_data);
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
                    break;
                case 'file':
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
                    break;
            }

            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Lesson created successfully'
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Export set data to csv file
     *
     * @param int $id
     * @return Response
     */
    public function show(int $id)
    {
        try {
            $set = Lesson::findOrfail($id);
            $setData = $set->setDetails()->get()->toArray();
            if (empty($setData)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Lesson is empty'
                ], 400);
            }
            // write set data to file
            $file_name = 'set_' . $id . '_' . date('Ymd_His') . '.csv';
//            $file_path = storage_path('app/public/sets/' . $file_name);
//            $file = fopen($file_path, 'w');
            $file = fopen($file_name, 'w');
            fputcsv($file, ['id', 'term', 'definition', 'image', 'audio', 'video', 'status', 'created_at', 'updated_at']);
            foreach ($setData as $line) {
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
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Get the specified resource to edit.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function edit(int $id): JsonResponse
    {
        try {
            $set = Lesson::findOrFail($id);
            $set_data = $set->setDetails()->get()->toArray();
            if (empty($set_data)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Lesson is empty'
                ], 400);
            }
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'data' => [
                    'set' => $set,
                    'set_data' => $set_data
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
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
    public function update(Request $request, int $id): JsonResponse
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
            $set->update([
                'name' => $request->name,
                'description' => $request->description,
                'status' => $request->status,
                'is_public' => $request->is_public,
                'password' => $request->password,
            ]);

            // delete set details
            $set->setDetails()->delete();

            // create set details
            foreach ($request->data as $item) {
                $set->setDetails()->create([
                    'term' => $item['term'],
                    'definition' => $item['definition'],
                ]);
            }

            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Lesson updated successfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
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
    public function destroy(int $id): JsonResponse
    {
        try {
            // delete set by id
            $set = Lesson::find($id);
            if ($set) {
                $set->delete();
                return response()->json([
                    'status' => 'success',
                    'status_code' => 200,
                    'message' => 'Lesson deleted successfully'
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 404,
                    'message' => 'Lesson not found'
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
