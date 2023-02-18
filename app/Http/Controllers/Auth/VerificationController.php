<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class VerificationController extends Controller
{
    // constructor
    public function __construct()
    {
    }

    /**
     * Handle the email verification request.
     * @param EmailVerificationRequest $request
     * @return JsonResponse
     */
    public function handleVerifyEmail(EmailVerificationRequest $request): JsonResponse
    {
        try {
            dd($request);
            $request->fulfill();
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Email verified successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ]);
        }
    }

    /**
     * Resend verify email
     *  @param Request $request
     *  @return void
     */
    public function resendVerifyEmail(Request $request): JsonResponse
    {
        try {
            $request->user()->sendEmailVerificationNotification();
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Email verification link sent on your email id.'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ]);
        }
    }
}
