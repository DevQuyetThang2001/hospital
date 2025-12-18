<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Clinics;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\FeedBack;
use App\Models\Patient;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ManagerController extends Controller
{
    public function index()
    {
        // Tổng số bác sĩ
        $totalDoctors = Doctor::count();

        // Tổng số bệnh nhân
        $totalPatients = Patient::count();

        // Tổng số đánh giá
        $totalFeedbacks = Feedback::count();

        // Đánh giá trung bình
        $averageRating = Feedback::avg('rating') ?? 0;

        // Tổng số lịch hôm nay
        $todayAppointments = Appointment::whereDate('appointment_date', Carbon::today())->count();

        // Tổng số lịch trong tuần
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $weekAppointments = Appointment::whereBetween('appointment_date', [$startOfWeek, $endOfWeek])->count();

        // Tổng số lịch tất cả
        $totalAppointments = Appointment::count();

        // Danh sách lịch hôm nay (join để lấy giờ khám)
        $todayList = Appointment::with(['doctor', 'patient', 'schedule'])
            ->whereDate('appointment_date', Carbon::today())
            ->join('schedules', 'appointments.schedule_id', '=', 'schedules.id')
            ->orderBy('schedules.start_time', 'asc')
            ->select('appointments.*')
            ->get();

        // Dữ liệu biểu đồ lượt khám theo tháng
        $appointmentsByMonth = Appointment::selectRaw('MONTH(appointment_date) as month, COUNT(*) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        // Truyền tất cả biến sang view
        return view('manager.modules.dashboard', compact(
            'totalDoctors',
            'totalPatients',
            'totalFeedbacks',
            'averageRating',
            'todayAppointments',
            'weekAppointments',
            'totalAppointments',
            'todayList',
            'appointmentsByMonth'
        ));
    }

    public function listSchedules()
    {
        // $doctor = Doctor::where('user_id', Auth::id())->first();

        // if (!$doctor) {
        //     return back()->withErrors(['msg' => 'Không tìm thấy bác sĩ.']);
        // }

        // $lists = DoctorSchedule::with(['schedule', 'doctor'])
        //     ->where('doctor_id', $doctor->id)
        //     ->get();

        // // Ánh xạ ngày tiếng Anh -> tiếng Việt
        // $dayMap = [
        //     'Monday' => 'Thứ 2',
        //     'Tuesday' => 'Thứ 3',
        //     'Wednesday' => 'Thứ 4',
        //     'Thursday' => 'Thứ 5',
        //     'Friday' => 'Thứ 6',
        //     'Saturday' => 'Thứ 7',
        //     'Sunday' => 'Chủ nhật',
        // ];

        // foreach ($lists as $item) {
        //     $item->day_of_week_vn = $dayMap[$item->day_of_week] ?? $item->day_of_week;
        // }

        // return view('manager.modules.schedules.managerList', [
        //     'lists' => $lists,
        //     'doctorName' => $doctor->user->name ?? 'Bác sĩ không tồn tại',
        // ]);
        // Lấy toàn bộ lịch khám (bao gồm thông tin bác sĩ và khung giờ)
        $lists = DoctorSchedule::with(['schedule', 'doctor.user'])
            ->orderByRaw("FIELD(day_of_week, 'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday')")
            ->get();

        // Ánh xạ ngày tiếng Anh -> tiếng Việt
        $dayMap = [
            'Monday' => 'Thứ 2',
            'Tuesday' => 'Thứ 3',
            'Wednesday' => 'Thứ 4',
            'Thursday' => 'Thứ 5',
            'Friday' => 'Thứ 6',
            'Saturday' => 'Thứ 7',
            'Sunday' => 'Chủ nhật',
        ];

        foreach ($lists as $item) {
            $item->day_of_week_vn = $dayMap[$item->day_of_week] ?? $item->day_of_week;
        }

        // dd($item);
        return view('manager.modules.schedules.managerList', [
            'lists' => $lists,
        ]);
    }

    public function createSchedule()
    {
        $doctors = Doctor::all();
        $schudules = Schedule::all();

        $clinics = Clinics::withCount([
            'doctorSchedules as doctor_count' => function ($q) {
                $q->select(DB::raw('count(distinct doctor_id)'));
            }
        ])->where('status', 1)->get(); // chỉ lấy phòng đang hoạt động

        return view(
            'manager.modules.schedules.add',
            compact('doctors', 'schudules', 'clinics')
        );
    }
    // public function storeSchedule(Request $request)
    // {
    //     $doctor = Doctor::where('user_id', Auth::id())->first();

    //     $request->validate([
    //         'day_of_week' => 'required|string|in:Monday,Tuesday,Wednesday,Thursday,Friday',
    //         'schedule_id' => 'required|integer|exists:schedules,id',
    //         'limit_per_hour' => 'required|integer|min:1|max:10',
    //     ], [
    //         'day_of_week.required' => 'Vui lòng chọn ngày khám.',
    //         'day_of_week.in' => 'Ngày khám không hợp lệ. Vui lòng chọn từ thứ 2 đến thứ 6.',
    //         'schedule_id.required' => 'Vui lòng chọn lịch khám.',
    //         'schedule_id.exists' => 'Lịch khám không tồn tại. Vui lòng chọn lịch khám hợp lệ.',
    //         'limit_per_hour.max' => 'Giới hạn số bệnh nhân/giờ không được vượt quá 10.',
    //         'limit_per_hour.min' => 'Giới hạn số bệnh nhân/giờ phải ít nhất là 1.',
    //     ]);

    //     // Kiểm tra trùng lịch
    //     $isDuplicate = DoctorSchedule::where('doctor_id', $doctor->id)
    //         ->where('day_of_week', $request->day_of_week)
    //         ->where('schedule_id', $request->schedule_id)
    //         ->exists();

    //     if ($isDuplicate) {
    //         return redirect()->back()->withErrors([
    //             'schedule_id' => 'Bác sĩ đã có lịch khám vào giờ này trong ngày đã chọn.',
    //         ])->withInput();
    //     }

    //     DoctorSchedule::create([
    //         'schedule_id' => $request->schedule_id,
    //         'doctor_id' => $doctor->id,
    //         'day_of_week' => $request->day_of_week,
    //         'limit_per_hour' => $request->limit_per_hour,
    //     ]);

    //     return redirect()->route('manager.schedules.list')->with('success', 'Lịch khám đã được tạo thành công.');
    // }

    // public function storeSchedule(Request $request)
    // {
    //     $request->validate(
    //         [
    //             'doctor_id' => 'required|integer|exists:doctors,id',
    //             'day_of_week' => 'required|string|in:Monday,Tuesday,Wednesday,Thursday,Friday',
    //             'schedule_id' => 'required|integer|exists:schedules,id',
    //             'limit_per_hour' => 'required|integer|min:1|max:10',
    //             'clinic_id' => 'required|integer|exists:clinics,id'
    //         ],
    //         [
    //             'doctor_id.required' => 'Vui lòng chọn bác sĩ.',
    //             'doctor_id.exists' => 'Bác sĩ không tồn tại.',
    //             'day_of_week.required' => 'Vui lòng chọn ngày khám.',
    //             'day_of_week.in' => 'Ngày khám không hợp lệ. Vui lòng chọn từ thứ 2 đến thứ 6.',
    //             'schedule_id.required' => 'Vui lòng chọn lịch khám.',
    //             'schedule_id.exists' => 'Lịch khám không tồn tại.',
    //             'limit_per_hour.max' => 'Giới hạn số bệnh nhân/giờ không được vượt quá 10.',
    //             'limit_per_hour.min' => 'Giới hạn số bệnh nhân/giờ phải ít nhất là 1.',
    //             'clinic_id.required' => 'Vui lòng chọn phòng khám.',
    //             'clinic_id.exists' => 'Phòng khám không tồn tại.',
    //         ],
    //     );

    //     // Kiểm tra trùng lịch
    //     $isDuplicate = DoctorSchedule::where('doctor_id', $request->doctor_id)->where('day_of_week', $request->day_of_week)->where('schedule_id', $request->schedule_id)->exists();

    //     if ($isDuplicate) {
    //         return redirect()
    //             ->back()
    //             ->withErrors([
    //                 'schedule_id' => 'Bác sĩ đã có lịch khám vào giờ này trong ngày đã chọn.',
    //             ])
    //             ->withInput();
    //     }

    //     DoctorSchedule::create([
    //         'schedule_id' => $request->schedule_id,
    //         'doctor_id' => $request->doctor_id,
    //         'day_of_week' => $request->day_of_week,
    //         'limit_per_hour' => $request->limit_per_hour,
    //     ]);

    //     return redirect()->route('manager.schedules.list')->with('success', 'Lịch khám đã được tạo thành công.');
    // }


    public function storeSchedule(Request $request)
    {
        $request->validate(
            [
                'doctor_id' => 'required|integer|exists:doctors,id',
                'clinic_id' => 'required|integer|exists:clinics,id',
                'day_of_week' => 'required|string|in:Monday,Tuesday,Wednesday,Thursday,Friday',
                'schedule_id' => 'required|integer|exists:schedules,id',
                'limit_per_hour' => 'required|integer|min:1|max:10',
            ],
            [
                'doctor_id.required' => 'Vui lòng chọn bác sĩ.',
                'clinic_id.required' => 'Vui lòng chọn phòng khám.',
                'clinic_id.exists' => 'Phòng khám không tồn tại.',
                'day_of_week.required' => 'Vui lòng chọn ngày khám.',
                'day_of_week.in' => 'Ngày khám không hợp lệ.',
                'schedule_id.required' => 'Vui lòng chọn lịch khám.',
                'schedule_id.exists' => 'Lịch khám không tồn tại.',
                'limit_per_hour.min' => 'Giới hạn số bệnh nhân/giờ phải ít nhất là 1.',
                'limit_per_hour.max' => 'Giới hạn số bệnh nhân/giờ không được vượt quá 10.',
            ]
        );

        // 🔹 Lấy phòng khám
        $clinic = Clinics::find($request->clinic_id);

        // 🔹 Đếm số bác sĩ hiện tại trong phòng
        $currentDoctorCount = DoctorSchedule::where('clinic_id', $clinic->id)
            ->distinct('doctor_id')
            ->count('doctor_id');

        // ❌ Nếu vượt quá quantity
        if ($currentDoctorCount >= $clinic->quantity) {
            return redirect()
                ->back()
                ->withErrors([
                    'clinic_id' => "Phòng khám {$clinic->name} đã đủ {$clinic->quantity} bác sĩ."
                ])
                ->withInput();
        }

        // ❌ Kiểm tra trùng lịch
        $isDuplicate = DoctorSchedule::where('doctor_id', $request->doctor_id)
            ->where('clinic_id', $request->clinic_id)
            ->where('day_of_week', $request->day_of_week)
            ->where('schedule_id', $request->schedule_id)
            ->exists();

        if ($isDuplicate) {
            return redirect()
                ->back()
                ->withErrors([
                    'schedule_id' => 'Bác sĩ đã có lịch khám vào thời gian này.'
                ])
                ->withInput();
        }

        DoctorSchedule::create([
            'schedule_id' => $request->schedule_id,
            'doctor_id' => $request->doctor_id,
            'clinic_id' => $request->clinic_id,
            'day_of_week' => $request->day_of_week,
            'limit_per_hour' => $request->limit_per_hour,
        ]);

        return redirect()
            ->route('manager.schedules.list')
            ->with('success', 'Lịch khám đã được tạo thành công.');
    }

    public function editSchedule($id)
    {
        // Lấy thông tin lịch khám cần sửa (bao gồm bác sĩ & khung giờ)
        $schedule = DoctorSchedule::with(['schedule', 'doctor.user', 'clinic'])->findOrFail($id);

        // Ánh xạ ngày tiếng Anh -> tiếng Việt
        $dayMap = [
            'Monday' => 'Thứ 2',
            'Tuesday' => 'Thứ 3',
            'Wednesday' => 'Thứ 4',
            'Thursday' => 'Thứ 5',
            'Friday' => 'Thứ 6',
            'Saturday' => 'Thứ 7',
            'Sunday' => 'Chủ nhật',
        ];

        $schedule->day_of_week_vn = $dayMap[$schedule->day_of_week] ?? $schedule->day_of_week;

        // Lấy toàn bộ bác sĩ và khung giờ để chọn lại trong form
        $doctors = Doctor::with('user')->get();
        $schedules = Schedule::all();

        // 5️⃣ Danh sách phòng khám + số bác sĩ hiện có
        $clinics = Clinics::withCount([
            'doctorSchedules as doctor_count' => function ($q) {
                $q->select(DB::raw('count(distinct doctor_id)'));
            }
        ])
            ->where('status', 1)
            ->get();

        return view('manager.modules.schedules.edit', compact('schedule', 'schedules', 'doctors', 'clinics'));
    }

    // public function updateSchedule(Request $request, $id)
    // {
    //     $request->validate(
    //         [
    //             'day_of_week' => 'required|string|in:Monday,Tuesday,Wednesday,Thursday,Friday',
    //             'schedule_id' => 'required|integer|exists:schedules,id',
    //             'limit_per_hour' => 'required|integer|min:1|max:10',
    //             'doctor_id' => 'required|integer|exists:doctors,id',
    //             'clinic_id'      => 'required|exists:clinics,id',

    //         ],
    //         [
    //             'day_of_week.required' => 'Vui lòng chọn ngày khám.',
    //             'day_of_week.in' => 'Ngày khám không hợp lệ. Vui lòng chọn từ thứ 2 đến thứ 6.',
    //             'schedule_id.required' => 'Vui lòng chọn lịch khám.',
    //             'schedule_id.exists' => 'Lịch khám không tồn tại. Vui lòng chọn lịch khám hợp lệ.',
    //             'limit_per_hour.max' => 'Giới hạn số bệnh nhân/giờ không được vượt quá 10.',
    //             'limit_per_hour.min' => 'Giới hạn số bệnh nhân/giờ phải ít nhất là 1.',
    //             'doctor_id.required' => 'Vui lòng chọn bác sĩ.',
    //             'clinic_id.required' => 'Vui lòng chọn phòng khám.',
    //             'clinic_id.exists'   => 'Phòng khám không tồn tại.',
    //         ],
    //     );

    //     $schedule = DoctorSchedule::findOrFail($id);

    //     if ($schedule->day_of_week === $request->day_of_week && $schedule->schedule_id == $request->schedule_id && $schedule->limit_per_hour == $request->limit_per_hour) {
    //         return back()->with('info', 'Bạn chưa thay đổi thông tin nào. Chỉ cập nhật khi có thay đổi.');
    //     }

    //     // kiểm tra trùng lịch
    //     $isDuplicate = DoctorSchedule::where('doctor_id', $request->doctor_id)->where('day_of_week', $request->day_of_week)->where('schedule_id', $request->schedule_id)->where('id', '<>', $id)->exists();

    //     if ($isDuplicate) {
    //         return redirect()
    //             ->back()
    //             ->withErrors([
    //                 'schedule_id' => 'Bác sĩ đã có lịch khám vào giờ này trong ngày đã chọn.',
    //             ])
    //             ->withInput();
    //     }


    //     if ($schedule->clinic_id != $request->clinic_id) {

    //         $doctorCount = DoctorSchedule::where('clinic_id', $request->clinic_id)
    //             ->distinct('doctor_id')
    //             ->count('doctor_id');

    //         if ($doctorCount >= 4) {
    //             return back()
    //                 ->withErrors([
    //                     'clinic_id' => 'Phòng khám này đã đủ 4 bác sĩ, không thể thêm.',
    //                 ])
    //                 ->withInput();
    //         }
    //     }


    //     if (!$schedule->isDirty()) {
    //         return back()->with('info', 'Không có thông tin cần thay đổi'); // Không báo gì
    //     }

    //     $schedule->update([
    //         'doctor_id' => $request->doctor_id,
    //         'schedule_id' => $request->schedule_id,
    //         'day_of_week' => $request->day_of_week,
    //         'limit_per_hour' => $request->limit_per_hour,
    //         'clinic_id' => $request->clinic_id,
    //     ]);

    //     return back()->with('success', 'Lịch khám đã được cập nhật thành công.');
    // }

    public function updateSchedule(Request $request, $id)
    {
        $request->validate(
            [
                'doctor_id'      => 'required|exists:doctors,id',
                'clinic_id'      => 'required|exists:clinics,id',
                'day_of_week'    => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday',
                'schedule_id'    => 'required|exists:schedules,id',
                'limit_per_hour' => 'required|integer|min:1|max:10',
            ],
            [
                'doctor_id.required' => 'Vui lòng chọn bác sĩ.',
                'clinic_id.required' => 'Vui lòng chọn phòng khám.',
                'clinic_id.exists'   => 'Phòng khám không tồn tại.',
                'day_of_week.required' => 'Vui lòng chọn ngày khám.',
                'day_of_week.in' => 'Ngày khám không hợp lệ.',
                'schedule_id.required' => 'Vui lòng chọn lịch khám.',
                'schedule_id.exists' => 'Lịch khám không tồn tại.',
                'limit_per_hour.min' => 'Giới hạn bệnh nhân/giờ ít nhất là 1.',
                'limit_per_hour.max' => 'Giới hạn bệnh nhân/giờ tối đa là 10.',
            ]
        );

        $schedule = DoctorSchedule::findOrFail($id);

        $schedule->fill([
            'doctor_id'      => $request->doctor_id,
            'clinic_id'      => $request->clinic_id,
            'day_of_week'    => $request->day_of_week,
            'schedule_id'    => $request->schedule_id,
            'limit_per_hour' => $request->limit_per_hour,
        ]);

        if (! $schedule->isDirty()) {
            return back()->with('info', 'Không có thông tin nào được thay đổi.');
        }

        $isDuplicate = DoctorSchedule::where('doctor_id', $request->doctor_id)
            ->where('day_of_week', $request->day_of_week)
            ->where('schedule_id', $request->schedule_id)
            ->where('id', '<>', $id)
            ->exists();

        if ($isDuplicate) {
            return back()
                ->withErrors(['schedule_id' => 'Bác sĩ đã có lịch khám vào khung giờ này.'])
                ->withInput();
        }


        if ($schedule->isDirty('clinic_id')) {

            $doctorCount = DoctorSchedule::where('clinic_id', $request->clinic_id)
                ->distinct('doctor_id')
                ->count('doctor_id');

            if ($doctorCount >= 4) {
                return back()
                    ->withErrors(['clinic_id' => 'Phòng khám này đã đủ 4 bác sĩ.'])
                    ->withInput();
            }
        }

        $schedule->save();

        return back()->with('success', 'Lịch khám đã được cập nhật thành công.');
    }
    public function deleteSchedule($id)
    {
        $schedule = DoctorSchedule::where('doctor_id', $id)->findOrFail($id);
        $schedule->delete();

        return redirect()->route('manager.schedules.list')->with('success', 'Lịch khám đã được xóa thành công.');
    }

    public function doctorSchedules()
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

        return view('manager.modules.schedules.list', compact('weeklySchedules', 'daysOfWeek', 'timeSlots'));
    }

    public function accountInfo()
    {
        $user = Auth::user();
        $manager = $user->manager;

        return view('manager.modules.account.profile', compact('user', 'manager'));
    }


    public function updateAccountInfo(Request $request)
    {
        $user = Auth::user();
        $manager = $user->manager;

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới.',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
            'new_password.confirmed' => 'Mật khẩu mới và xác nhận mật khẩu không khớp.',
        ]);

        // Kiểm tra mật khẩu hiện tại
        if (!password_verify($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
        }

        // Cập nhật mật khẩu mới
        $user->password = bcrypt($request->new_password);
        $user->save();

        return back()->with('success', 'Mật khẩu đã được cập nhật thành công.');
    }
}
