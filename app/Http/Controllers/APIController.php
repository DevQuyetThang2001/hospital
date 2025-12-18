<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;

class APIController extends Controller
{
    // API lấy bác sĩ theo khoa viện

    public function getByDepartment($departmentId)
    {
        $doctors = Doctor::where('department_id', $departmentId)
            ->with(['user:id,name', 'schedule'])
            ->get();

        $result = $doctors->map(function ($doctor) {
            return [
                'id' => $doctor->id,
                'name' => $doctor->user->name ?? 'Không có tên',
                'schedules' => $doctor->schedules->map(function ($s) {
                    return [
                        'day_of_week' => $s->pivot->day_of_week,
                        'start_time' => $s->start_time ?? null,
                        'end_time' => $s->end_time ?? null,
                    ];
                }),
            ];
        });

        return response()->json($result);
    }
}
