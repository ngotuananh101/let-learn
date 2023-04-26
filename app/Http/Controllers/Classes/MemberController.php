<?php

namespace App\Http\Controllers\Classes;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    // get member list by class id
    function getMember(Request $request, $class_id): JsonResponse
    {
        try {
            $class = Classes::findOrFail($class_id);
            $members = $class->member()->get();
            return response()->json([
                'class_name' => $class->name,
                'data' => $members->map(function ($member) {
                    return [
                        'id' => $member->id,
                        'name' => $member->name,
                        'email' => $member->email,
                        'avatar' => 'https://gravatar.com/avatar/' . md5($member->email) . '?s=200&d=identicon',
                        'role' => $member->role->name,
                    ];
                })
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ] , 500);
        }
    }
}
