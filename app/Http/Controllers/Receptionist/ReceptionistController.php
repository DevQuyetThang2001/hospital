<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Mail\AppointmentConfirmedMail;
use App\Mail\AppointmentRejectedMail;
use App\Models\Appointment;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\Patient;
use App\Models\Room;
use App\Models\Schedule;
use App\Notifications\NewAppointmentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ReceptionistController extends Controller
{
    public function index()
    {

        // Lấy thứ hiện tại, ví dụ: Monday
        $today = \Carbon\Carbon::now()->format('l');

        // Lịch hẹn hôm nay dựa theo thứ
        $todayAppointments = Appointment::whereHas('schedule', function ($query) use ($today) {
            $query->where('day_of_week', $today);
        })->count();

        return view('receptionist.modules.dashboard', [
            'todayAppointments' => $todayAppointments,
            'pendingAppointments' => Appointment::where('status', 'pending')->count(),
            'patientCount' => Patient::count(),
            'emptyRooms' => Room::where('status', 'available')->count(),
            'pendingList' => Appointment::where('status', 'pending')->get()
        ]);
    }


    public function viewSchedule()
    {
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        $timeSlots = ['7:00-8:00', '9:00-10:00', '11:00-12:00', '14:00-15:00', '16:00-17:00']; // Các khung giờ khám

        // Mảng chứa lịch làm việc
        $weeklySchedules = [];

        foreach ($daysOfWeek as $day) {
            $weeklySchedules[$day] = [];

            foreach ($timeSlots as $time) {
                $weeklySchedules[$day][$time] = Doctor::whereHas('schedule', function ($query) use ($day, $time) {
                    $query->where('day_of_week', $day)->where('start_time', '<=', $time)->where('end_time', '>', $time);
                })
                    ->with([
                        'schedule' => function ($query) use ($day, $time) {
                            $query->where('day_of_week', $day)->where('start_time', '<=', $time)->where('end_time', '>', $time)->select('schedules.id', 'doctor_id', 'day_of_week', 'start_time', 'end_time');
                        },
                    ])
                    ->get();
            }
        }

        return view('receptionist.modules.schedules.list', compact('weeklySchedules', 'daysOfWeek', 'timeSlots'));
    }


    // public function listAppointments(Request $request)
    // {
    //     $dayMap = [
    //         'Monday'    => 'Thứ 2',
    //         'Tuesday'   => 'Thứ 3',
    //         'Wednesday' => 'Thứ 4',
    //         'Thursday'  => 'Thứ 5',
    //         'Friday'    => 'Thứ 6',
    //         'Saturday'  => 'Thứ 7',
    //         'Sunday'    => 'Chủ nhật',
    //     ];

    //     // Chỉ lấy lịch hẹn chờ xác nhận
    //     $appointments = Appointment::with([
    //         'patient.user',
    //         'doctor.user',
    //         'schedule.schedule'
    //     ])
    //         ->where('status', 'pending')
    //         ->orderBy('appointment_date', 'asc')
    //         ->get();

    //     foreach ($appointments as $appointment) {

    //         if ($appointment->schedule && $appointment->schedule->schedule) {
    //             $dayEn = $appointment->schedule->day_of_week;
    //             $appointment->day_vn = $dayMap[$dayEn] ?? $dayEn;
    //             $appointment->start_time = $appointment->schedule->schedule->start_time;
    //             $appointment->end_time   = $appointment->schedule->schedule->end_time;
    //         } else {
    //             $dayEnFromDate = \Carbon\Carbon::parse($appointment->appointment_date)->format('l');
    //             $appointment->day_vn   = $dayMap[$dayEnFromDate] ?? $dayEnFromDate;
    //             $appointment->start_time = 'N/A';
    //             $appointment->end_time   = 'N/A';
    //         }

    //         $appointment->status_label = 'Chờ xác nhận';
    //         $appointment->status_class = 'warning';
    //     }

    //     return view('receptionist.modules.appointments.list', compact('appointments'));
    // }



    // public function listAppointments(Request $request)
    // {
    //     $dayMap = [
    //         'Monday'    => 'Thứ 2',
    //         'Tuesday'   => 'Thứ 3',
    //         'Wednesday' => 'Thứ 4',
    //         'Thursday'  => 'Thứ 5',
    //         'Friday'    => 'Thứ 6',
    //         'Saturday'  => 'Thứ 7',
    //         'Sunday'    => 'Chủ nhật',
    //     ];

    //     // Lọc trạng thái
    //     $status = $request->query('status');

    //     // Lọc theo thứ
    //     $dayFilter = $request->query('day'); // Ví dụ: Monday, Tuesday, ...

    //     $appointments = Appointment::with([
    //         'patient.user',
    //         'doctor.user',
    //         'schedule.schedule'
    //     ])
    //         ->when($status, function ($query) use ($status) {
    //             return $query->where('status', $status);
    //         })
    //         ->when($dayFilter, function ($query) use ($dayFilter) {
    //             return $query->whereHas('schedule', function ($q) use ($dayFilter) {
    //                 $q->where('day_of_week', $dayFilter);
    //             });
    //         })
    //         ->orderBy('appointment_date', 'desc')
    //         ->get();

    //     // Xử lý ngày + giờ
    //     foreach ($appointments as $appointment) {

    //         if ($appointment->schedule && $appointment->schedule->schedule) {
    //             $dayEn = $appointment->schedule->day_of_week;
    //             $appointment->day_vn = $dayMap[$dayEn] ?? $dayEn;
    //             $appointment->start_time = $appointment->schedule->schedule->start_time;
    //             $appointment->end_time   = $appointment->schedule->schedule->end_time;
    //         } else {
    //             $dayEnFromDate = \Carbon\Carbon::parse($appointment->appointment_date)->format('l');
    //             $appointment->day_vn   = $dayMap[$dayEnFromDate] ?? $dayEnFromDate;
    //             $appointment->start_time = 'N/A';
    //             $appointment->end_time   = 'N/A';
    //         }
    //     }

    //     return view('receptionist.modules.appointments.list', compact('appointments', 'status', 'dayFilter'));
    // }

    public function listAppointments(Request $request)
    {
        $dayMap = [
            'Monday'    => 'Thứ 2',
            'Tuesday'   => 'Thứ 3',
            'Wednesday' => 'Thứ 4',
            'Thursday'  => 'Thứ 5',
            'Friday'    => 'Thứ 6',
            'Saturday'  => 'Thứ 7',
            'Sunday'    => 'Chủ nhật',
        ];

        // Lọc trạng thái (mặc định = 'pending')
        $status = $request->query('status', 'pending');

        // Lọc theo thứ
        $dayFilter = $request->query('day');

        $appointments = Appointment::with([
            'patient.user',
            'doctor.user',
            'schedule.schedule'
        ])
            // Lọc trạng thái
            ->when($status !== 'all', function ($query) use ($status) {
                return $query->where('status', $status);
            })

            // Lọc theo thứ trong tuần
            ->when($dayFilter, function ($query) use ($dayFilter) {
                return $query->whereHas('schedule', function ($q) use ($dayFilter) {
                    $q->where('day_of_week', $dayFilter);
                });
            })

            ->orderBy('appointment_date', 'desc')
            ->get();

        foreach ($appointments as $appointment) {
            if ($appointment->schedule && $appointment->schedule->schedule) {
                $dayEn = $appointment->schedule->day_of_week;
                $appointment->day_vn = $dayMap[$dayEn] ?? $dayEn;

                $appointment->start_time = $appointment->schedule->schedule->start_time;
                $appointment->end_time   = $appointment->schedule->schedule->end_time;
            } else {
                $dayEnFromDate = \Carbon\Carbon::parse($appointment->appointment_date)->format('l');
                $appointment->day_vn   = $dayMap[$dayEnFromDate] ?? $dayEnFromDate;
                $appointment->start_time = 'N/A';
                $appointment->end_time   = 'N/A';
            }
        }

        return view(
            'receptionist.modules.appointments.list',
            compact('appointments', 'status', 'dayFilter')
        );
    }

    public function confirmAppointment($id)
    {
        // Lấy lịch hẹn theo ID
        $appointment = Appointment::findOrFail($id);

        // Lấy user hiện tại (lễ tân)
        $user = Auth::user();

        // Kiểm tra trạng thái pending
        if ($appointment->status !== 'pending') {
            return back()->with('info', 'Lịch hẹn này đã được xử lý.');
        }

        // Cập nhật trạng thái xác nhận
        $appointment->status = 'confirmed';

        // Lưu lại ID lễ tân xác nhận
        $appointment->confirmed_by_receptionist_id = $user->id;

        // Lưu vào database
        $appointment->save();

        // Gửi email cho bệnh nhân nếu có email
        if ($appointment->patient && $appointment->patient->user?->email) {
            Mail::to($appointment->patient->user->email)
                ->send(new AppointmentConfirmedMail($appointment));
        }

        // Trả về thông báo thành công
        return back()->with('success', '🎉 Lễ tân đã xác nhận lịch hẹn thành công.');
    }


    public function rejectAppointment($id)
    {
        $appointment = Appointment::findOrFail($id);
        $user = Auth::user(); // phải là receptionist

        // Chỉ từ chối nếu đang ở trạng thái pending
        if ($appointment->status !== 'pending') {
            return back()->with('info', 'Lịch hẹn này đã được xử lý.');
        }

        // Cập nhật trạng thái
        $appointment->status = 'cancelled';
        $appointment->canceled_by_receptionist_id = $user->id;
        $appointment->save();

        // Gửi mail thông báo từ chối cho bệnh nhân
        if ($appointment->patient && $appointment->patient->user?->email) {
            Mail::to($appointment->patient->user->email)
                ->send(new AppointmentRejectedMail($appointment));
        }

        return back()->with('success', '✅ Lễ tân đã từ chối lịch hẹn thành công.');
    }


    // Trong AppointmentController.php hoặc tương tự

    public function createAppointment(Request $request)
    {
        // Bảng chuyển thứ sang tiếng Việt
        $dayMap = [
            'Monday'    => 'Thứ 2',
            'Tuesday'   => 'Thứ 3',
            'Wednesday' => 'Thứ 4',
            'Thursday'  => 'Thứ 5',
            'Friday'    => 'Thứ 6',
            'Saturday'  => 'Thứ 7',
            'Sunday'    => 'Chủ nhật',
        ];

        // Nhận query filter
        $departmentId = $request->query('department_id');
        $dayFilter    = $request->query('day');

        // Lấy toàn bộ lịch khám bác sĩ
        $schedules = DoctorSchedule::with([
            'doctor.user',
            'doctor.department',
            'schedule'
        ])
            // Lọc theo khoa
            ->when($departmentId, function ($q) use ($departmentId) {
                $q->whereHas('doctor', function ($d) use ($departmentId) {
                    $d->where('department_id', $departmentId);
                });
            })

            // Lọc theo thứ trong tuần
            ->when($dayFilter, function ($q) use ($dayFilter) {
                $q->where('day_of_week', $dayFilter);
            })

            ->get();

        // Gán thuộc tính day_vn cho từng lịch
        foreach ($schedules as $item) {
            $item->day_vn = $dayMap[$item->day_of_week] ?? $item->day_of_week;
        }

        // Gom nhóm lịch theo bác sĩ
        $groupedSchedules = $schedules->groupBy(function ($item) {
            return $item->doctor->id;
        });

        // Danh sách khoa & thứ để hiển thị select filter
        $departments = Department::all();

        // Mặc định lấy thứ từ DoctorSchedule (không cần lấy Schedule::all)
        $daysAvailable = DoctorSchedule::select('day_of_week')->distinct()->get();

        return view(
            'receptionist.modules.appointments.choose_schedule',
            compact('groupedSchedules', 'departments', 'daysAvailable', 'departmentId', 'dayFilter')
        );
    }

    public function detailAppointment(Doctor $doctor)
    {
        // Lấy các lịch khám của bác sĩ này
        $schedules = DoctorSchedule::with('schedule')->where('doctor_id', $doctor->id)->get();

        return view('receptionist.modules.appointments.detail', compact('doctor', 'schedules'));
    }


    // Đặt lịch hẹn bởi lễ tân
    public function appointmentStoreByReceptionist(Request $request, Doctor $doctor)
    {
        $user = Auth::user(); // phải là receptionist
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|regex:/^[0-9]{10,11}$/',
            'appointment_date' => 'required|date',
            'schedule_id' => 'required|exists:doctor_schedules,id',
            'notes' => 'nullable|string|max:500',
        ], [
            'username.required' => 'Tên bệnh nhân là bắt buộc.',
            'email.required' => 'Email là bắt buộc.',
            'phone.required' => 'Số điện thoại là bắt buộc.',
            'phone.regex' => 'Số điện thoại phải gồm 10–11 chữ số.',
            'appointment_date.required' => 'Ngày hẹn là bắt buộc.',
            'schedule_id.required' => 'Lịch khám là bắt buộc.',
            'schedule_id.exists' => 'Lịch khám không tồn tại.',
        ]);

        $doctorSchedule = DoctorSchedule::findOrFail($request->schedule_id);

        if ($doctorSchedule->limit_per_hour <= 0) {
            return back()->with('error', '⚠️ Lịch khám này đã đầy, vui lòng chọn khung giờ khác.');
        }
        $appointment = new Appointment();
        $appointment->patient_id = null; // nếu bệnh nhân chưa có tài khoản
        $appointment->booked_by = $user->id; // id lễ tân
        $appointment->doctor_id = $doctor->id;
        $appointment->schedule_id = $request->schedule_id;
        $appointment->username = $request->username;
        $appointment->email = $request->email;
        $appointment->phone = $request->phone;
        $appointment->appointment_date = $request->appointment_date;
        $appointment->notes = $request->notes;
        $appointment->status = 'confirmed';
        $appointment->save();

        // 🔹 Thông báo cho bác sĩ
        $doctor->user->notify(new NewAppointmentNotification($appointment));

        // 🔹 Giảm giới hạn lịch
        $doctorSchedule->decrement('limit_per_hour');

        return back()->with('success', '✅ Lịch khám đã được đặt thành công cho bệnh nhân!');
    }




    public function accountInfo()
    {
        $user = Auth::user(); // Lấy user hiện tại (lễ tân)
        return view('receptionist.modules.account.profile', compact('user'));
    }

    public function updateAccountInfo(Request $request)
    {
        // Validate với thông báo tiếng Việt
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới.',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
            'new_password.confirmed' => 'Xác nhận mật khẩu mới không khớp.',
        ]);

        $user = Auth::user();

        // Kiểm tra mật khẩu hiện tại
        if (!Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Mật khẩu hiện tại không chính xác.'])
                ->withInput();
        }

        // Cập nhật mật khẩu mới
        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Đổi mật khẩu thành công!');
    }
}
