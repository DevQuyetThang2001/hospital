<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Mail\AppointmentRejectedMail;
use App\Models\Appointment;
use App\Models\Blog;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\Image;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Schedule;
use App\Models\TreatmentHistory;
use App\Models\User;
use App\Mail\AppointmentConfirmedMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class DoctorController extends Controller
{
    public function index()
    {

        $doctor = Auth::user()->doctor;

        return view('doctor.modules.dashboard', [
            'patientsCount' => Patient::where('doctor_id', $doctor->id)->count(),
            'todayAppointments' => Appointment::where('doctor_id', $doctor->id)
                ->whereDate('appointment_date', today())->count(),
            'upcomingAppointments' => Appointment::where('doctor_id', $doctor->id)
                ->whereDate('appointment_date', '>', today())->count(),
            'pendingConfirmations' => User::where('role', 'patient')
                ->whereNotIn('id', Patient::pluck('user_id'))->count(),
            'todayAppointmentsList' => Appointment::where('doctor_id', $doctor->id)
                ->whereDate('appointment_date', today())->take(5)->get(),
        ]);
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

        if (
            $schedule->day_of_week === $request->day_of_week &&
            $schedule->schedule_id == $request->schedule_id &&
            $schedule->limit_per_hour == $request->limit_per_hour
        ) {
            return back()->with('info', 'Bạn chưa thay đổi thông tin nào. Chỉ cập nhật khi có thay đổi.');
        }

        // kiểm tra trùng lịch
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

    public function blogs()
    {
        $doctor = Doctor::where('user_id', Auth::id())->first();

        if (!$doctor) {
            return back()->withErrors(['msg' => 'Không tìm thấy thông tin bác sĩ.']);
        }

        // Lấy các bài viết của bác sĩ này
        $blogs = Blog::with(['doctor.user', 'images'])
            ->where('doctor_id', $doctor->id)
            ->latest()
            ->get();

        return view('doctor.modules.blogs.list', compact('blogs'));
    }



    public function createBlog()
    {
        return view('doctor.modules.blogs.add');
    }


    public function storeBlog(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $doctor = Auth::user()->doctor;

        $blog = Blog::create([
            'title' => $request->title,
            'description' => $request->description,
            'doctor_id' => $doctor->id,
        ]);


        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('uploads/blogs', 'public');
                $blog->images()->create(['image' => $path]);
            }
        }


        return redirect()->route('doctor.blogs.list')->with('success', 'Bài viết đã được tạo thành công.');
    }

    public function editBlog(Request $request, $id)
    {

        $doctor = Doctor::where('user_id', Auth::id())->first();
        if (!$doctor) {
            return back()->withErrors(['msg' => 'Không tìm thấy bác sĩ.']);
        }

        // Lấy bài viết cần sửa
        $blog = Blog::where('id', $id)->where('doctor_id', $doctor->id)->first();
        if (!$blog) {
            return back()->withErrors(['msg' => 'Không tìm thấy bài viết.']);
        }

        return view('doctor.modules.blogs.edit', compact('blog'));
    }

    public function updateBlog(Request $request, $id)
    {
        $doctor = Doctor::where('user_id', Auth::id())->first();
        if (!$doctor) {
            return back()->withErrors(['msg' => 'Không tìm thấy bác sĩ.']);
        }

        $blog = Blog::where('id', $id)->where('doctor_id', $doctor->id)->first();
        if (!$blog) {
            return back()->withErrors(['msg' => 'Không tìm thấy bài viết.']);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $blog->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        // Nếu có ảnh mới
        if ($request->hasFile('image')) {
            $oldImage = Image::where('blog_id', $blog->id)->first();

            // Xóa ảnh cũ
            if ($oldImage && file_exists(storage_path('app/public/' . $oldImage->image))) {
                unlink(storage_path('app/public/' . $oldImage->image));
            }

            // Lưu ảnh mới vào storage/app/public/uploads/blogs
            $path = $request->file('image')->store('uploads/blogs', 'public');

            if ($oldImage) {
                $oldImage->update(['image' => $path]);
            } else {
                Image::create([
                    'blog_id' => $blog->id,
                    'image' => $path,
                ]);
            }
        }

        return redirect()->route('doctor.blogs.list')->with('update', 'Bài viết đã được cập nhật thành công!');
    }




    public function deleteBlog($id)
    {
        $doctor = Doctor::where('user_id', Auth::id())->first();
        if (!$doctor) {
            return back()->withErrors(['msg' => 'Không tìm thấy bác sĩ.']);
        }

        // Lấy bài viết cần sửa
        $blog = Blog::where('id', $id)->where('doctor_id', $doctor->id)->first();
        if (!$blog) {
            return back()->withErrors(['msg' => 'Không tìm thấy bài viết.']);
        }


        $blog->delete();

        return redirect()->route('doctor.blogs.list')->with('delete', 'Xóa thành công bài viết');
    }


    // public function viewAppointment(Request $request)
    // {

    //     $dayMap = [
    //         'Monday' => 'Thứ 2',
    //         'Tuesday' => 'Thứ 3',
    //         'Wednesday' => 'Thứ 4',
    //         'Thursday' => 'Thứ 5',
    //         'Friday' => 'Thứ 6',
    //         'Saturday' => 'Thứ 7',
    //         'Sunday' => 'Chủ nhật',
    //     ];

    //     $doctor = Auth::user()->doctor;

    //     if (!$doctor) {
    //         return back()->with('error', 'Không tìm thấy thông tin bác sĩ.');
    //     }

    //     // 1. Cập nhật Eager Loading:
    //     // Dùng chuỗi 'schedule.schedule'
    //     // (Appointment.schedule -> DoctorSchedule.schedule -> Schedule)
    //     $appointments = Appointment::with([
    //         'patient.user',
    //         'schedule.schedule' // *** ĐÃ SỬA: SỬ DỤNG TÊN MỐI QUAN HỆ ĐÃ ĐỊNH NGHĨA ***
    //     ])
    //         ->where('doctor_id', $doctor->id)
    //         ->orderBy('appointment_date', 'desc')
    //         ->get();

    //     // 2. Cập nhật cách truy cập dữ liệu trong vòng lặp:
    //     foreach ($appointments as $appointment) {

    //         // Kiểm tra mối quan hệ cấp 1 (DoctorSchedule) và cấp 2 (Schedule)
    //         if ($appointment->schedule && $appointment->schedule->schedule) {

    //             // Lấy Thứ trong tuần (Day of Week) từ DoctorSchedule (Mối quan hệ cấp 1)
    //             $dayEn = $appointment->schedule->day_of_week;
    //             $appointment->day_vn = $dayMap[$dayEn] ?? $dayEn;

    //             // Lấy Giờ khám (Start/End Time) từ Schedule (Mối quan hệ cấp 2)
    //             $appointment->start_time = $appointment->schedule->schedule->start_time;
    //             $appointment->end_time = $appointment->schedule->schedule->end_time;
    //         } else {
    //             // Xử lý trường hợp không tìm thấy lịch khám
    //             $dayEnFromDate = \Carbon\Carbon::parse($appointment->appointment_date)->format('l');
    //             $appointment->day_vn = $dayMap[$dayEnFromDate] ?? $dayEnFromDate;
    //             $appointment->start_time = 'N/A';
    //             $appointment->end_time = 'N/A';
    //         }
    //     }

    //     return view('doctor.modules.appointment.list', compact('appointments'));
    // }



    // Xác nhận tài khoản bệnh nhân để có thể đặt lịch

    // public function confirm_account_patient($user_id)
    // {
    //     $user = User::findOrFail($user_id);

    //     if (Patient::where('user_id', $user_id)->exists()) {
    //         return back()->with('info', 'Tài khoản này đã là bệnh nhân.');
    //     }

    //     $patient = new Patient();
    //     $patient->user_id = $user->id;
    //     $patient->gender = $user->gender ?? 'unknown';
    //     $patient->address = $user->address ?? '';
    //     $patient->phone = $user->phone ?? '';
    //     $patient->date_of_birth = $user->date_of_birth ?? null;
    //     $patient->save();

    //     return back()->with('success', 'Đã xác nhận tài khoản bệnh nhân thành công.');
    // }

    public function viewAppointment(Request $request)
    {
        // Map các ngày tiếng Anh sang tiếng Việt
        $dayMap = [
            'Monday'    => 'Thứ 2',
            'Tuesday'   => 'Thứ 3',
            'Wednesday' => 'Thứ 4',
            'Thursday'  => 'Thứ 5',
            'Friday'    => 'Thứ 6',
            'Saturday'  => 'Thứ 7',
            'Sunday'    => 'Chủ nhật',
        ];

        // Lấy thông tin bác sĩ hiện tại (dựa trên tài khoản đăng nhập)
        $doctor = Auth::user()->doctor;

        if (!$doctor) {
            return back()->with('error', 'Không tìm thấy thông tin bác sĩ.');
        }

        // Lấy danh sách lịch hẹn có liên quan đến bác sĩ này
        // Eager load các quan hệ: bệnh nhân -> user, doctorSchedule -> schedule
        $appointments = Appointment::with([
            'patient.user',       // Thông tin bệnh nhân và tài khoản user
            'schedule.schedule'   // DoctorSchedule -> Schedule
        ])
            ->where('doctor_id', $doctor->id)
            ->orderBy('appointment_date', 'desc')
            ->get();

        // Xử lý từng lịch hẹn để hiển thị dữ liệu thân thiện
        foreach ($appointments as $appointment) {
            // Nếu có dữ liệu lịch khám (DoctorSchedule và Schedule)
            if ($appointment->schedule && $appointment->schedule->schedule) {
                $dayEn = $appointment->schedule->day_of_week;
                $appointment->day_vn = $dayMap[$dayEn] ?? $dayEn;
                $appointment->start_time = $appointment->schedule->schedule->start_time;
                $appointment->end_time   = $appointment->schedule->schedule->end_time;
            } else {
                // Nếu không có lịch cụ thể, lấy ngày từ appointment_date
                $dayEnFromDate = \Carbon\Carbon::parse($appointment->appointment_date)->format('l');
                $appointment->day_vn     = $dayMap[$dayEnFromDate] ?? $dayEnFromDate;
                $appointment->start_time = 'N/A';
                $appointment->end_time   = 'N/A';
            }

            // Chuẩn hóa trạng thái hiển thị
            switch ($appointment->status) {
                case 'pending':
                    $appointment->status_label = 'Chờ xác nhận';
                    $appointment->status_class = 'warning';
                    break;
                case 'confirmed':
                    $appointment->status_label = 'Đã xác nhận';
                    $appointment->status_class = 'success';
                    break;
                case 'completed':
                    $appointment->status_label = 'Hoàn thành';
                    $appointment->status_class = 'primary';
                    break;
                case 'cancelled':
                    $appointment->status_label = 'Đã hủy';
                    $appointment->status_class = 'danger';
                    break;
                default:
                    $appointment->status_label = 'Không xác định';
                    $appointment->status_class = 'secondary';
                    break;
            }
        }

        // Trả dữ liệu ra view
        return view('doctor.modules.appointment.list', compact('appointments'));
    }



    // Post xac nhan tai khoan cua benh nhan dang ky o website
    public function confirm($user_id)
    {
        $user = User::findOrFail($user_id);

        // Nếu đã là bệnh nhân thì bỏ qua
        if (Patient::where('user_id', $user_id)->exists()) {
            return back()->with('info', 'Tài khoản này đã là bệnh nhân.');
        }

        // Tạo bản ghi mới, chỉ liên kết user_id
        $patient = new Patient();
        $patient->user_id = $user->id;
        $patient->save();

        return back()->with('success', 'Đã xác nhận tài khoản bệnh nhân thành công.');
    }

    public function confirmAppointment($id)
    {
        // Log::info('🟢 Hàm confirmAppointment được gọi!');

        $appointment = Appointment::findOrFail($id);

        if ($appointment->status !== 'pending') {
            return back()->with('info', 'Lịch hẹn này đã được xử lý.');
        }

        $appointment->status = 'confirmed';
        $appointment->save();



        // Log::info('Patient: ' . json_encode($appointment->patient->user->email));

        if ($appointment->patient && $appointment->patient->user && $appointment->patient->user->email) {
            $patientEmail = $appointment->patient->user->email;
            Log::info('Gửi mail tới bệnh nhân: ' . $patientEmail);
            Mail::to($patientEmail)
                ->send(new AppointmentConfirmedMail($appointment));
        }

        return back()->with('success', '✅ Đã xác nhận lịch hẹn thành công.');
    }


    public function rejectAppointment($id)
    {
        $appointment = Appointment::findOrFail($id);

        if ($appointment->status !== 'pending') {
            return back()->with('info', 'Lịch hẹn này đã được xử lý.');
        }

        // Cập nhật trạng thái
        $appointment->status = 'cancelled';
        $appointment->save();

        // ✅ Hoàn lại suất khám cho lịch của bác sĩ
        if ($appointment->schedule_id) {
            $schedule = DoctorSchedule::find($appointment->schedule_id);
            if ($schedule) {
                $schedule->increment('limit_per_hour');
            }
        }

        // ✅ Gửi mail thông báo bị từ chối
        if ($appointment->patient && $appointment->patient->user->email) {
            Mail::to($appointment->patient->user->email)->send(new AppointmentRejectedMail($appointment));
        }

        return back()->with('error', '❌ Đã từ chối lịch hẹn và hoàn lại suất khám.');
    }


    public function completeAppointment($id)
    {

        $appointment = Appointment::findOrFail($id);
        if ($appointment->status !== 'confirmed') {
            return back()->with('info', 'Lịch hẹn này chưa được xác nhận hoặc đã hoàn thành.');
        }
        $appointment->status = 'completed';

        $appointment->save();
        return back()->with('success', 'Đã hoàn thành lịch hẹn thành công.');
    }


    public function list_patient_account()
    {
        // Lấy danh sách các khoa (nếu cần hiển thị chọn khoa)
        $departments = Department::all();

        // Lấy danh sách user có role 'patient' nhưng chưa được xác nhận trong bảng patients
        $users = User::where('role', 'patient')
            ->whereDoesntHave('patient') // cách viết Eloquent đẹp hơn
            ->get();

        return view('doctor.modules.patients.list', compact('departments', 'users'));
    }

    public function show_form_patient()
    {

        $doctor = Auth::user()->doctor; // bác sĩ đang đăng nhập
        $departments = Department::where('id', $doctor->department_id)->get(); // chỉ khoa của bác sĩ
        $users = User::where('role', 'patient')
            ->whereNotIn('id', function ($query) {
                $query->select('user_id')->from('patients');
            })
            ->get();

        return view('doctor.modules.patients.show', compact('departments', 'users', 'doctor'));
    }






    // Post xac nhan  benh nhan dang ky offline
    public function confirm_patient(Request $request)
    {
        $validated = $request->validate([
            'department_id' => 'required',
            'gender' => 'required',
            'date_of_birth' => 'required|date',
            'address' => 'required|string|max:255',
            'phone' => 'required|max:11|unique:patients,phone',
            'user_id' => 'nullable|exists:users,id', // bác sĩ có thể bỏ trống nếu bệnh nhân chưa có tài khoản
            'name' => 'required_without:user_id', // nếu không có user_id thì phải nhập tên
        ], [
            'department_id.required' => "Bắt buộc phải chọn khoa viện",
            'gender.required' => "Bắt buộc phải chọn giới tính",
            'date_of_birth.required' => "Bắt buộc phải nhập ngày sinh",
            'phone.required' => 'Bắt buộc phải điền số điện thoại',
            'phone.unique' => 'Số điện thoại đã tồn tại',
            'phone.max' => 'Số điện thoại phải hợp lệ với 11 chữ số',
            'address.required' => 'Bắt buộc phải điền địa chỉ',
            'name.required' => 'Bắt buộc phải nhập họ tên',
        ]);

        // ✅ Lấy thông tin bác sĩ đang đăng nhập
        $doctor = Auth::user()->doctor;
        if (!$doctor) {
            return back()->with('error', 'Không tìm thấy thông tin bác sĩ.');
        }

        // ✅ Tạo bệnh nhân mới
        $patient = new Patient();
        $patient->name = $validated['name'];
        $patient->department_id = $validated['department_id'];
        $patient->user_id = $validated['user_id'] ?? null; // Có thể null
        $patient->gender = $validated['gender'];
        $patient->date_of_birth = $validated['date_of_birth'];
        $patient->address = $validated['address'];
        $patient->phone = $validated['phone'];
        $patient->doctor_id = $doctor->id; // bác sĩ nào xác nhận
        $patient->save();

        return back()->with('success', 'Xác nhận thông tin bệnh nhân thành công.');
    }


    // Hồ sơ bệnh án
    public function viewMedicalRecord()
    {
        $doctor = Auth::user()->doctor;

        if (!$doctor) {
            return back()->with('error', 'Không tìm thấy thông tin bác sĩ.');
        }

        $records = MedicalRecord::where('doctor_id', $doctor->id)
            ->with(['patient.user']) // lấy cả thông tin user nếu bệnh nhân có tài khoản
            ->latest()
            ->paginate(10);

        return view('doctor.modules.medical_record.list', compact('records'));
    }

    public function addMedicalRecord()
    {
        $doctor = Auth::user()->doctor;

        if (!$doctor) {
            return back()->with('error', 'Không tìm thấy thông tin bác sĩ.');
        }

        $departmentId = $doctor->department_id;

        // Lấy danh sách bệnh nhân cùng khoa để chọn khi khám offline
        $patients = Patient::where('department_id', $departmentId)
            ->with('user')
            ->get();

        // Lấy các lịch hẹn chưa có hồ sơ bệnh án (tránh trùng)
        $appointments = Appointment::where('doctor_id', $doctor->id)
            ->whereDoesntHave('medicalRecord')
            ->with('patient.user')
            ->get();

        return view('doctor.modules.medical_record.add', compact('patients', 'appointments'));
    }

    public function storeMedicalRecord(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'diagnosis' => 'required|string',
            'treatment' => 'nullable|string',
            'medications' => 'nullable|string',
        ]);

        $doctor = Auth::user()->doctor;

        // Tạo hồ sơ bệnh án mới
        $record = MedicalRecord::create([
            'patient_id' => $request->patient_id,
            'doctor_id' => $doctor->id,
            'appointment_id' => $request->appointment_id, // có thể null nếu khám offline
            'diagnosis' => $request->diagnosis,
            'treatment' => $request->treatment,
            'medications' => $request->medications,
        ]);

        return redirect()->route('doctor.patient.medicalRecord')
            ->with('success', '✅ Hồ sơ bệnh án đã được tạo thành công.');
    }

    public function showMedicalRecord($id)
    {
        $doctor = Auth::user()->doctor;

        // Chỉ cho phép xem hồ sơ thuộc về bác sĩ hiện tại
        $record = MedicalRecord::where('id', $id)->where('doctor_id', $doctor->id)->with(['patient.user', 'doctor.user'])->firstOrFail();

        return view('doctor.modules.medical_record.detail', compact('record'));
    }




    public function updateTreatment(Request $request, $id)
    {
        // 1. Validation: Đảm bảo treatment được điền (không rỗng)
        $request->validate([
            'treatment' => 'required|string',
            'note' => 'nullable|string',
        ]);

        $record = MedicalRecord::findOrFail($id);

        // 2. KIỂM TRA LOGIC MỚI: Nếu treatment KHÔNG THAY ĐỔI và note cũng KHÔNG ĐƯỢC ĐIỀN
        // (Lưu ý: Nếu note được điền, có thể bạn vẫn muốn cho phép lưu lịch sử)

        $oldTreatment = $record->treatment;
        $newTreatment = $request->treatment;
        $newNote = $request->note;

        // Kiểm tra: Nếu nội dung treatment MỚI giống hệt nội dung CŨ 
        // VÀ trường note MỚI không có giá trị
        if ($newTreatment === $oldTreatment && empty($newNote)) {
            // Redirect lại với thông báo lỗi
            return redirect()->route('doctor.patient.medicalRecord.show', $id)
                ->with('error', 'Phác đồ điều trị và Ghi chú phải có thay đổi để cập nhật.');
        }

        // 3. Thực hiện lưu lịch sử (đã sửa lỗi || null thành ?? null)
        TreatmentHistory::create([
            'medical_record_id' => $record->id,
            'treatment' => $newTreatment,
            'note' => $newNote ?? null,
        ]);

        // 4. Cập nhật bản ghi chính
        $record->treatment = $newTreatment;
        $record->save();

        // 5. Redirect thành công
        return redirect()->route('doctor.patient.medicalRecord.show', $id)
            ->with('success', 'Đã cập nhật phác đồ điều trị và lưu lịch sử thành công!');
    }


    public function accountInfo()
    {
        $user = Auth::user();
        $doctor = Doctor::where('user_id', $user->id)->first();

        if (!$doctor) {
            abort(404, 'Không tìm thấy thông tin bác sĩ.');
        }

        return view('doctor.modules.account.profile', compact('user', 'doctor'));
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
