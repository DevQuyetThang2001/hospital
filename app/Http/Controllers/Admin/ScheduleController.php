<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function schedules(){
     
    $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
    $timeSlots = ['7:00-8:00', '9:00-10:00', '11:00-12:00', '14:00-15:00', '16:00-17:00']; // Các khung giờ khám

    // Mảng chứa lịch làm việc
    $weeklySchedules = [];

    foreach ($daysOfWeek as $day) {
        $weeklySchedules[$day] = [];

        foreach ($timeSlots as $time) {
            $weeklySchedules[$day][$time] = Doctor::whereHas('schedule', function ($query) use ($day, $time) {
                    $query->where('day_of_week', $day)
                          ->where('start_time', '<=', $time)
                          ->where('end_time', '>', $time);
                })
                ->with(['schedule' => function ($query) use ($day, $time) {
                    $query->where('day_of_week', $day)
                          ->where('start_time', '<=', $time)
                          ->where('end_time', '>', $time)
                          ->select('schedules.id', 'doctor_id', 'day_of_week', 'start_time', 'end_time');
                }])
                ->get();
        }
    }
    
        return view('admin.modules.schedules.list', compact('weeklySchedules', 'daysOfWeek', 'timeSlots'));
    }
}
