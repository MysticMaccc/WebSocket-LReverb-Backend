<?php

namespace App\Http\Controllers;

use App\Events\NotificationCountUpdated;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        try {
            $notificationData = Notification::orderBy('created_at', 'desc')->get();

            if ($notificationData->isEmpty()) {
                return response()->json([
                    'response' => false
                ], 400);
            }

            return response()->json([
                'response' => true,
                'data' => $notificationData
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'response' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function countNotification()
    {
        try {
            $notificationCount = Notification::count();

            return response()->json([
                'response' => true,
                'data' => $notificationCount
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'response' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'message' => 'required|min:5|max:1000',
            ]);

            $store = Notification::create([
                'message' => $request['message'],
            ]);

            if (!$store) {
                return response()->json([
                    'response' => false,
                    'message' => 'Store failed!'
                ], 400);
            }

            $notificationCount = Notification::count();
            $notificationData = Notification::orderBy('created_at', 'desc')->get();
            $user = Auth::user();
            broadcast(new NotificationCountUpdated($user, $notificationCount, $notificationData));

            return response()->json([
                'response' => true,
                'message' => 'Notification created successfully!'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'response' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $notificationData = Notification::findOrFail($id);

            $destroy = $notificationData->delete();

            if (!$destroy) {
                return response()->json([
                    'response' => false,
                    'message' => 'Destroy failed!'
                ], 400);
            }

            return response()->json([
                'response' => true,
                'message' => 'Notification deleted successfully!'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'response' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
