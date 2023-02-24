<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Set;
use App\Models\User;
use App\Models\Folder;
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
                'file' => 'required_if:type,file|mimes:xls,xlsx,csv|nullable',
            ]);
            $type = $request->input('type');
            // Tao set moi
            $set = new Set();
            $set->name = $request->name;
            $set->description = $request->description;
            $set->user_id = auth()->user()->id;
            $set->save();

            if ($type == 'text') {
                $text = $request->text;
                $termSeparator = $request->term_separator;
                $setSeparator = $request->set_separator;
                $details = explode($setSeparator, $text);
                foreach ($details as $detail) {
                    try {
                        $detail = explode($termSeparator, $detail);
                        $term = $detail[0];
                        $definition = $detail[1];
                        $set->setDetails()->create([
                            'term' => $term,
                            'definition' => $definition,
                        ]);
                    } catch (\Throwable $th) {
                        continue;
                    }
                }
            } else if ($type == 'file') {
                $file = $request->file('file');
                if ($file->getClientOriginalExtension() == 'csv') {
                    $rows = array_map('str_getcsv', file($file));
                    $headerRow = array_shift($rows);
                    if (count($headerRow) < 2 || $headerRow[0] != 'Term' || $headerRow[1] != 'Definition') {
                        return response()->json(['error' => 'File must have "Term" and "Definition" headers'], 400);
                    }
                    foreach ($rows as $row) {
                        $term = $row[0];
                        $definition = $row[1];
                        if (isset($row[0]) && isset($row[1])) {
                            $set->setDetails()->create([
                                'term' => $term,
                                'definition' => $definition,
                            ]);
                        }
                    }
                } else {
                    $rows = Excel::toArray([], $file);
                    if (count($rows[0][0]) > 1 && $rows[0][0][0] == 'Term' && $rows[0][0][1] == 'Definition') {
                        $rows[0] = array_slice($rows[0], 1);
                    }
                    foreach ($rows[0] as $row) {
                        $term = $row[0];
                        $definition = $row[1];
                        if (isset($row[0]) && isset($row[1])) {
                            $set->setDetails()->create([
                                'term' => $term,
                                'definition' => $definition,
                            ]);
                        }
                    }
                }
            }
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Set created successfully',
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
            $set = Set::findOrfail($id);
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
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $set = Set::findOrFail($id);
            // check set is deleted
            if ($set->status == 'inactive') {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 404,
                    'message' => 'Set not found!'
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
     * @param  int  $id
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
            $set = Set::findOrFail($id);
            $set->name = $request->name;
            $set->description = $request->description;
            $set->status = $request->status;
            $set->is_public = $request->is_public;
            $set->password = $request->password;
            $set->save();

            $setdetail = $set->setDetails()->get();
            foreach ($setdetail as $detail) {
                // remove all set details
                $detail->delete();
            }

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
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            $set = Set::findOrFail($id);
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
            $folder = Folder::findOrFail($id);
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
}
