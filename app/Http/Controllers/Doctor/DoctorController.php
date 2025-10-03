<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index()
    {
        return view('doctor.modules.dashboard');
    }

    public function listSchedules()
    {
        $doctor = Doctor::where('user_id', Auth::id())->first();


        if (!$doctor) {
            return back()->withErrors(['msg' => 'Không tìm thấy bác sĩ.']);
        }

        $lists = DoctorSchedule::with(['schedule', 'doctor'])
            ->where('doctor_id', $doctor->id)
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

        return view('doctor.modules.schedules.managerList', [
            'lists' => $lists,
            'doctorName' => $doctor->user->name ?? 'Bác sĩ không tồn tại',
        ]);
    }


    public function createSchedule()
    {
        $schudules = Schedule::all(); // This should be replaced with actual logic to fetch schedules if needed

        return view('doctor.modules.schedules.add', compact('schudules'));
    }
    public function storeSchedule(Request $request)
    {
        $doctor = Doctor::where('user_id', Auth::id())->first();

        $request->validate([
            'day_of_week' => 'required|string|in:Monday,Tuesday,Wednesday,Thursday,Friday',
            'schedule_id' => 'required|integer|exists:schedules,id',
            'limit_per_hour' => 'required|integer|min:1|max:10',
        ], [
            'day_of_week.required' => 'Vui lòng chọn ngày khám.',
            'day_of_week.in' => 'Ngày khám không hợp lệ. Vui lòng chọn từ thứ 2 đến thứ 6.',
            'schedule_id.required' => 'Vui lòng chọn lịch khám.',
            'schedule_id.exists' => 'Lịch khám không tồn tại. Vui lòng chọn lịch khám hợp lệ.',
            'limit_per_hour.max' => 'Giới hạn số bệnh nhân/giờ không được vượt quá 10.',
            'limit_per_hour.min' => 'Giới hạn số bệnh nhân/giờ phải ít nhất là 1.',
        ]);

        // Kiểm tra trùng lịch
        $isDuplicate = DoctorSchedule::where('doctor_id', $doctor->id)
            ->where('day_of_week', $request->day_of_week)
            ->where('schedule_id', $request->schedule_id)
            ->exists();

        if ($isDuplicate) {
            return redirect()->back()->withErrors([
                'schedule_id' => 'Bác sĩ đã có lịch khám vào giờ này trong ngày đã chọn.',
            ])->withInput();
        }

        DoctorSchedule::create([
            'schedule_id' => $request->schedule_id,
            'doctor_id' => $doctor->id,
            'day_of_week' => $request->day_of_week,
            'limit_per_hour' => $request->limit_per_hour,
        ]);

        return redirect()->route('doctor.schedules.list')->with('success', 'Lịch khám đã được tạo thành công.');
    }

    public function editSchedule($id)
    {
        $doctor = Doctor::where('user_id', Auth::id())->first();
        if (!$doctor) {
            return back()->withErrors(['msg' => 'Không tìm thấy bác sĩ.']);
        }

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


        $schedule = DoctorSchedule::with('schedule')
            ->where('doctor_id', $doctor->id)
            ->findOrFail($id);

        $schedule->day_of_week_vn = $dayMap[$schedule->day_of_week] ?? $schedule->day_of_week;

        $schedules = Schedule::all(); // This should be replaced with actual logic to fetch schedules if needed

        return view('doctor.modules.schedules.edit', compact('schedule', 'schedules'));
    }

    public function updateSchedule(Request $request, $id)
    {
        $doctor = Doctor::where('user_id', Auth::id())->first();
        if (!$doctor) {
            return back()->withErrors(['msg' => 'Không tìm thấy bác sĩ.']);
        }

        $request->validate([
            'day_of_week' => 'required|string|in:Monday,Tuesday,Wednesday,Thursday,Friday',
            'schedule_id' => 'required|integer|exists:schedules,id',
            'limit_per_hour' => 'required|integer|min:1|max:10', // 👈 thêm
        ], [
            'day_of_week.required' => 'Vui lòng chọn ngày khám.',
            'day_of_week.in' => 'Ngày khám không hợp lệ. Vui lòng chọn từ thứ 2 đến thứ 6.',
            'schedule_id.required' => 'Vui lòng chọn lịch khám.',
            'schedule_id.exists' => 'Lịch khám không tồn tại. Vui lòng chọn lịch khám hợp lệ.',
            'limit_per_hour.max' => 'Giới hạn số bệnh nhân/giờ không được vượt quá 10.',
            'limit_per_hour.min' => 'Giới hạn số bệnh nhân/giờ phải ít nhất là 1.',

        ]);

        $schedule = DoctorSchedule::findOrFail($id);

        // ✅ Nếu không thay đổi gì
        if (
            $schedule->day_of_week === $request->day_of_week &&
            $schedule->schedule_id == $request->schedule_id &&
            $schedule->limit_per_hour == $request->limit_per_hour
        ) {
            return back()->with('info', 'Bạn chưa thay đổi thông tin nào. Chỉ cập nhật khi có thay đổi.');
        }

        // ✅ Kiểm tra trùng lịch
        $isDuplicate = DoctorSchedule::where('doctor_id', $doctor->id)
            ->where('day_of_week', $request->day_of_week)
            ->where('schedule_id', $request->schedule_id)
            ->where('id', '<>', $id) // Trừ bản ghi hiện tại
            ->exists();

        if ($isDuplicate) {
            return redirect()->back()->withErrors([
                'schedule_id' => 'Bác sĩ đã có lịch khám vào giờ này trong ngày đã chọn.',
            ])->withInput();
        }

        $schedule->update([
            'schedule_id' => $request->schedule_id,
            'day_of_week' => $request->day_of_week,
            'limit_per_hour' => $request->limit_per_hour,
        ]);

        return back()->with('success', 'Lịch khám đã được cập nhật thành công.');
    }

    public function deleteSchedule($id)
    {
        $doctor = Doctor::where('user_id', Auth::id())->first();
        if (!$doctor) {
            return back()->withErrors(['msg' => 'Không tìm thấy bác sĩ.']);
        }

        $schedule = DoctorSchedule::where('doctor_id', $doctor->id)->findOrFail($id);
        $schedule->delete();

        return redirect()->route('doctor.schedules.list')->with('success', 'Lịch khám đã được xóa thành công.');
    }
}
