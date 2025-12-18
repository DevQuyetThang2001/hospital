<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorNotificationController extends Controller
{
    public function getNotifications()
    {
        $doctorId = Auth::user()->doctor->id;

        $notifications = Auth::user()->notifications()
            ->where('data->doctor_id', $doctorId) // chỉ lấy thông báo của bác sĩ này
            ->latest()
            ->take(5)
            ->get();

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

        // Đánh dấu tất cả thông báo chưa đọc là đã đọc
        $user->unreadNotifications->markAsRead();

        return response()->json(['status' => 'success']);
    }
}
