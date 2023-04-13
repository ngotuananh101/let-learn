<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\NotificationMail;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class NotificationController extends Controller
{
    /**
     * Display all notifications.
     */
    public function index(): JsonResponse
    {
        try {
            // get all notifications
            $notifications = Notification::all();
            return response()->json([
                'notifications' => $notifications
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send a notification.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'type' => 'required|string|in:email,push',
                'to' => 'required|string',
                'title' => 'required|string',
                'message' => 'required|string',
                'url' => 'nullable|string',
                'schedule' => 'nullable|string'
            ]);
            // save notification
            $notification = Notification::create([
                'type' => $request->type,
                'from' => auth()->user()->email,
                'to' => $request->to,
                'title' => $request->title,
                'message' => $request->message,
                'url' => $request->url,
                'schedule' => $request->schedule ? Carbon::make($request->schedule)->toDateTimeString() : null,
            ]);
            // send notification
            return match ($request->type) {
                'email' => $this->sendEmailNotification($notification),
                'push' => $this->sendPushNotification($notification),
                default => response()->json([
                    'message' => 'Invalid notification type.'
                ], 400),
            };
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send push notification.
     */
    public function sendPushNotification(Notification $notification): JsonResponse
    {
        try {
            $base_url = 'https://onesignal.com/api/v1/notifications';
            $header = [
                'Authorization' => 'Basic ' . env('ONESIGNAL_REST_API_KEY'),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ];
            $data = [
                'app_id' => env('ONESIGNAL_APP_ID'),
                'included_segments' => [$notification->to],
                'headings' => ['en' => $notification->title],
                'contents' => ['en' => $notification->message],
                'url' => $notification->url,
            ];
            if ($notification->schedule) {
                $data['send_after'] = $notification->schedule;
            }
            $client = new \GuzzleHttp\Client();
            $response = $client->request('POST', $base_url, [
                'headers' => $header,
                'json' => $data
            ]);
            return response()->json([
                'message' => 'Notification sent successfully.',
                'data' => $notification
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        } catch (GuzzleException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send email notification.
     */
    public function sendEmailNotification(Notification $notification): JsonResponse
    {
        try {
            // send email to all users
            if (!filter_var($notification->to, FILTER_VALIDATE_EMAIL)) {
                // get all users emails
                $users = User::all();
                $emails = $users->map(function ($user) {
                    return $user->email;
                });
                $notification->to = $emails;
                Mail::send(new NotificationMail($notification));
                return response()->json([
                    'message' => 'Notification sent successfully.',
                    'data' => $notification
                ]);
            }else{
                // get user
                $user = User::where('email', $notification->to)->first();
                if ($user) {
                    Mail::send(new NotificationMail($notification));
                    return response()->json([
                        'message' => 'Notification sent successfully.',
                        'data' => $notification
                    ]);
                } else {
                    return response()->json([
                        'message' => 'User not found.'
                    ], 404);
                }
            }
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete forum notification.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $notification = Notification::findOrFail($id);
            $notification->delete();
            return response()->json([
                'message' => 'Notification deleted successfully.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
