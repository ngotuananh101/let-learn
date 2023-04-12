<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /*
     * User login
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users,email',
                'password' => 'required|string|min:8',
                'remember' => 'required|boolean'
            ]);
            $credentials = request(['email', 'password']);
            if (!auth()->attempt($credentials)) {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 401);
            }else{
                $user = auth()->user();
                if (!$user->hasVerifiedEmail()) {
                    return response()->json([
                        'message' => 'Email not verified please verify your email if you have not received verification email please contact admin'
                    ], 401);
                }
                if ($user->status == 'inactive') {
                    return response()->json([
                        'message' => 'User is inactive please contact admin'
                    ], 401);
                }
                $expiresAt = $request->remember ? Carbon::now()->addWeeks(1) : Carbon::now()->addHour(6);
                $token = $user->createToken(name: 'authToken' . $user->id,expiresAt: $expiresAt);
                $user->role->permissions;
                return response()->json([
                    'info' => $user->load('school'),
                    'access_token' => $token->plainTextToken,
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /*
     * User registration
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|min:3|max:255',
                'username' => 'required|string|min:6|max:255|unique:users,username',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
                'date_of_birth' => 'required|date|before:today',
            ]);
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'date_of_birth' => $request->date_of_birth,
                'role_id' => Role::all()->where('name', 'user')->first()->id,
            ]);
            event(new Registered($user));
            return response()->json([
                'message' => 'User created successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /*
     * User verification
     * @param Request $request
     * @return \Illuminate\Foundation\Application|Redirector|RedirectResponse|Application
     */
    public function verify(Request $request): \Illuminate\Foundation\Application|Redirector|RedirectResponse|Application
    {
        try {
            $user = User::findOrFail($request->id);
            if (!hash_equals((string)$request->route('hash'), sha1($user->getEmailForVerification()))) {
                throw new AuthorizationException;
            }
            if ($user->hasVerifiedEmail()) {
                return redirect('/auth/login');
            }
            if ($user->markEmailAsVerified()) {
                event(new Verified($user));
            }
            return redirect('/auth/login');
        } catch (\Exception $e) {
            return abort(500);
        }
    }

    /*
     * User forgot password
     * @param Request $request
     * @return JsonResponse
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users,email'
            ]);
            $status = Password::sendResetLink(
                $request->only('email')
            );
            return $status === Password::RESET_LINK_SENT
                ? response()->json(['message' => __($status)], 200)
                : response()->json(['message' => __($status)], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /*
     * User reset password view
     * @param Request $request
     * @return JsonResponse
     */
    public function resetPasswordView(Request $request): Application|\Illuminate\Foundation\Application|RedirectResponse|Redirector
    {
        try {
            $request->validate([
                'token' => 'required|string',
                'email' => 'required|email|exists:users,email',
            ]);

            return redirect('/auth/reset-password?token=' . $request->token . '&email=' . $request->email);

        } catch (\Exception $e) {
            return abort(500);
        }
    }

    /*
     * User reset password
     * @param Request $request
     * @return JsonResponse
     */
    public function resetPassword(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'token' => 'required|string',
                'email' => 'required|email|exists:users,email',
                'password' => 'required|string|min:8|confirmed',
            ]);
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->forceFill([
                        'password' => Hash::make($password)
                    ])->save();
                    $user->setRememberToken(Str::random(60));
                    event(new PasswordReset($user));
                }
            );
            return $status === Password::PASSWORD_RESET
                ? response()->json(['message' => __($status)], 200)
                : response()->json(['message' => __($status)], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /*
     * User logout
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'type' => 'required|string|in:all,current'
            ]);
            if ($request->type == 'all') {
                $request->user()->tokens()->delete();
            } else {
                $request->user()->currentAccessToken()->delete();
            }
            return response()->json([
                'message' => 'Logged out successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
