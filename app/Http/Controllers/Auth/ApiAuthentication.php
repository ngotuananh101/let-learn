<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;

class ApiAuthentication extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Login with username and password.
     *
     * @param Request $request
     * @return json
     */
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string',
                'password' => 'required|string',
            ]);
            $credentials = request(['email', 'password']);
            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 401,
                    'message' => 'Unauthorized'
                ], 401);
            }
            $user = $request->user();
            $token = $user->createToken('Personal Access Token');
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Login successful',
                'data' => [
                    'user' => $user,
                    'access_token' => $token->plainTextToken,
                    'token_type' => 'Bearer',
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
     * Logout.
     *
     * @param Request $request
     * @return json
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Logout successful',
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
     * Logout from all devices.
     * @return json
     */
    public function logoutAll()
    {
        try {
            $user = Auth::user();
            $user->tokens()->delete();
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Logout from all devices successful',
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
     * Get the authenticated User.
     *
     * @param Request $request
     * @return json
     */
    public function user(Request $request)
    {
        try {
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'User details',
                'data' => $request->user()
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
     * Register a new user.
     *
     * @param Request $request
     * @return json
     */
    public function register(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required|string|unique:users',
                'email' => 'required|string|email|unique:users',
                'date_of_birth' => 'required|date|before:today',
                'password' => 'required|string|confirmed'
            ]);
            $user = new User([
                'username' => $request->username,
                'email' => $request->email,
                'date_of_birth' => $request->date_of_birth,
                'password' => bcrypt($request->password)
            ]);
            $user->save();
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'User created successfully',
                'data' => $user
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
