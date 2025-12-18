<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReceptionistNotificationController extends Controller
{
    public function getNotifications()
    {
        $user = Auth::user();

        $notifications = $user->notifications()->latest()->take(5)->get();

        $data = $notifications->map(function ($notification) {
            return [
                'id' => $notification->id,
                'message' => $notification->data['message'] ?? 'Thông báo mới',
                'appointment_date' => $notification->data['appointment_date'] ?? null,
                'time' => $notification->created_at->diffForHumans(),
            ];
        });

        return response()->json($data);
    }

    public function getUnreadCount()
    {
        $count = Auth::user()->unreadNotifications->count();
        return response()->json(['count' => $count]);
    }

    public function markAsRead()
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();

        return response()->json(['status' => 'success']);
    }
}
