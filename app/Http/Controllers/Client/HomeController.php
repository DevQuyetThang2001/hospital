<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\DoctorSchedule;

use App\Models\FeedBack;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{
    public function index()
    {

        $doctors = Doctor::limit(2)->get();
        $feedbacks = FeedBack::limit(5)->get();
        // dd($doctors); // Assuming you want to fetch all doctors

        // dd($doctors->get(1)->user->image);

        return view('clients.home', compact('doctors','feedbacks'));
    }

    public function appointment()
    {
        // Logic for handling appointment view

        $schedules = DoctorSchedule::with('doctor', 'schedule')->get();

        // Gom nhóm lịch theo bác sĩ
        $groupedSchedules = $schedules->groupBy(function ($item) {
            return $item->doctor->id;
        });

        // dd($groupedSchedules);


        $departments = Department::all();
        $schedulesAll = Schedule::all();

        // dd($schedules);

        return view('clients.appointment', compact('groupedSchedules', 'departments', 'schedulesAll'));
    }


    public function appointmentDetail(Doctor $doctor)
    {

        // Lấy các lịch khám của bác sĩ này
        $schedules = DoctorSchedule::with('schedule')
            ->where('doctor_id', $doctor->id)
            ->get();



        return view('clients.appointmentDetail', compact('doctor', 'schedules'));
    }

    public function appointmentStore(Request $request, Doctor $doctor)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để đặt lịch khám.');
        }
        if (Auth::user()->role != 'patient') {
            return back()->with('error', 'Chỉ bệnh nhân mới được đặt lịch khám.');
        }

        // $patient = Patient::where('user_id', Auth::id())->first();
        // if (!$patient) {
        //     return back()->with('error', 'Bạn chưa có hồ sơ bệnh nhân, chờ xác nhận từ bệnh viện.');
        // }

        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'appointment_date' => 'required|date',
            'schedule_id' => 'required|exists:doctor_schedules,id',
            'notes' => 'nullable|string|max:500',
        ], [
            'username.required' => 'Tên người dùng là bắt buộc.',
            'email.required' => 'Email là bắt buộc.',
            'appointment_date.required' => 'Ngày hẹn là bắt buộc.',
            'schedule_id.required' => 'Lịch khám là bắt buộc.',
            'notes.max' => 'Ghi chú không được vượt quá 500 ký tự.',
            'schedule_id.exists' => 'Lịch khám không tồn tại.',
            'email.email' => 'Email không hợp lệ.',
            'appointment_date.date' => 'Ngày hẹn không hợp lệ.',
        ]);

        $appointment = new Appointment();
        $appointment->patient_id = Auth::id();
        $appointment->doctor_id = $doctor->id;
        $appointment->schedule_id = $request->schedule_id;
        $appointment->username = $request->username;
        $appointment->email = $request->email;
        $appointment->appointment_date = $request->appointment_date;
        $appointment->notes = $request->notes;
        $appointment->status = 'pending'; // ✅ bắt buộc nếu ENUM


        $appointment->save();
        return back()->with('success', 'Lịch khám đã được đặt thành công!, Chờ xác nhận từ bác sĩ');
    }



    // Lọc lịch khám
    public function filter_appointment(Request $request)
    {
        $request->validate([
            'department' => 'nullable|exists:departments,id',
            'day_of_week' => 'nullable|in:Monday,Tuesday,Wednesday,Thursday,Friday',
            'schedule_id' => 'nullable|exists:schedules,id',
        ], [

            'department.exists' => 'Phòng khám không tồn tại.',

            'day_of_week.in' => 'Ngày trong tuần không hợp lệ.',

            'schedule_id.exists' => 'Lịch khám không tồn tại.',
        ]);
        $departments = Department::all();
        $schedulesAll = Schedule::all();

        $query = DoctorSchedule::with(['doctor', 'doctor.department', 'schedule']);

        if ($request->filled('department')) {
            $query->whereHas('doctor', function ($q) use ($request) {
                $q->where('department_id', $request->department);
            });
        }

        if ($request->filled('day_of_week')) {
            $query->where('day_of_week', $request->day_of_week);
        }

        if ($request->filled('schedule_id')) {
            $query->where('schedule_id', $request->schedule_id);
        }

        $schedules = $query->get();

        $groupedSchedules = $schedules->groupBy('doctor.id');

        return view('clients.appointment', compact('groupedSchedules', 'departments', 'schedulesAll'));
    }
}
