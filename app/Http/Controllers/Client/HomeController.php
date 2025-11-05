<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Blog;
use App\Models\Contact;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\DoctorSchedule;

use App\Models\FeedBack;
use App\Models\Patient;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    public function index()
    {
        $doctors = Doctor::limit(2)->get();
        $feedbacks = FeedBack::limit(5)->get();
        $blogs = Blog::limit(3)->get();
        // dd($doctors); // Assuming you want to fetch all doctors

        // dd($doctors->get(1)->user->image);
        // dd($blogs);

        return view('clients.home', compact('doctors', 'feedbacks', 'blogs'));
    }

    public function appointment()
    {


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
        $schedules = DoctorSchedule::with('schedule')->where('doctor_id', $doctor->id)->get();

        return view('clients.appointmentDetail', compact('doctor', 'schedules'));
    }

    // public function appointmentStore(Request $request, Doctor $doctor)
    // {
    //     if (!Auth::check()) {
    //         return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để đặt lịch khám.');
    //     }

    //     if (Auth::user()->role != 'patient') {
    //         return back()->with('error', 'Chỉ bệnh nhân mới được đặt lịch khám.');
    //     }

    //     // Lấy hồ sơ bệnh nhân tương ứng với user hiện tại
    //     $patient = Patient::where('user_id', Auth::id())->first();

    //     if (!$patient) {
    //         return back()->with('error', 'Bạn chưa có hồ sơ bệnh nhân, chờ xác nhận từ bệnh viện.');
    //     }

    //     $request->validate([
    //         'username' => 'required|string|max:255',
    //         'email' => 'required|email|max:255',
    //         'phone' => 'required|regex:/^[0-9]{10,11}$/',
    //         'appointment_date' => 'required|date',
    //         'schedule_id' => 'required|exists:doctor_schedules,id',
    //         'notes' => 'nullable|string|max:500',
    //     ], [
    //         'username.required' => 'Tên người dùng là bắt buộc.',
    //         'email.required' => 'Email là bắt buộc.',
    //         'phone.required' => 'Số điện thoại là bắt buộc.',
    //         'phone.regex' => 'Số điện thoại phải gồm 10–11 chữ số và không chứa ký tự khác.',
    //         'appointment_date.required' => 'Ngày hẹn là bắt buộc.',
    //         'schedule_id.required' => 'Lịch khám là bắt buộc.',
    //         'notes.max' => 'Ghi chú không được vượt quá 500 ký tự.',
    //         'schedule_id.exists' => 'Lịch khám không tồn tại.',
    //         'email.email' => 'Email không hợp lệ.',
    //         'appointment_date.date' => 'Ngày hẹn không hợp lệ.',
    //     ]);

    //     $appointment = new Appointment();
    //     $appointment->patient_id = Auth::id();
    //     $appointment->doctor_id = $doctor->id;
    //     $appointment->schedule_id = $request->schedule_id;
    //     $appointment->username = $request->username;
    //     $appointment->email = $request->email;
    //     $appointment->phone = $request->phone;
    //     $appointment->appointment_date = $request->appointment_date;
    //     $appointment->notes = $request->notes;
    //     $appointment->status = 'pending'; // ✅ bắt buộc nếu ENUM

    //     $appointment->save();
    //     return back()->with('success', 'Lịch khám đã được đặt thành công!, Chờ xác nhận từ bác sĩ');
    // }

    // public function appointmentStore(Request $request, Doctor $doctor)
    // {
    //     if (!Auth::check()) {
    //         return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để đặt lịch khám.');
    //     }

    //     $user = Auth::user();

    //     if ($user->role !== 'patient') {
    //         return back()->with('error', 'Chỉ bệnh nhân mới được đặt lịch khám.');
    //     }

    //     // Lấy hồ sơ bệnh nhân tương ứng với user hiện tại
    //     $patient = Patient::where('user_id', $user->id)->first();

    //     if (!$patient) {
    //         return back()->with('error', 'Tài khoản của bạn chưa được xác nhận hồ sơ bệnh nhân.');
    //     }

    //     $request->validate(
    //         [
    //             'username' => 'required|string|max:255',
    //             'email' => 'required|email|max:255',
    //             'phone' => 'required|regex:/^[0-9]{10,11}$/',
    //             'appointment_date' => 'required|date',
    //             'schedule_id' => 'required|exists:doctor_schedules,id',
    //             'notes' => 'nullable|string|max:500',
    //         ],
    //         [
    //             'username.required' => 'Tên bệnh nhân là bắt buộc.',
    //             'email.required' => 'Email là bắt buộc.',
    //             'phone.required' => 'Số điện thoại là bắt buộc.',
    //             'phone.regex' => 'Số điện thoại phải gồm 10–11 chữ số.',
    //             'appointment_date.required' => 'Ngày hẹn là bắt buộc.',
    //             'schedule_id.required' => 'Lịch khám là bắt buộc.',
    //             'schedule_id.exists' => 'Lịch khám không tồn tại.',
    //         ],
    //     );

    //     $appointment = new Appointment();

    //     // ✅ Nếu bệnh nhân tự đặt cho chính mình
    //     $appointment->patient_id = $patient->id;

    //     // ✅ Người đặt lịch luôn là user hiện tại
    //     $appointment->booked_by = $user->id;

    //     $appointment->doctor_id = $doctor->id;
    //     $appointment->schedule_id = $request->schedule_id;
    //     $appointment->username = $request->username; // Tên bệnh nhân hiển thị
    //     $appointment->email = $request->email;
    //     $appointment->phone = $request->phone;
    //     $appointment->appointment_date = $request->appointment_date;
    //     $appointment->notes = $request->notes;
    //     $appointment->status = 'pending';

    //     $appointment->save();

    //     return back()->with('success', 'Lịch khám đã được đặt thành công! Vui lòng chờ bác sĩ xác nhận.');
    // }




    public function appointmentStore(Request $request, Doctor $doctor)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để đặt lịch khám.');
        }

        $user = Auth::user();

        if ($user->role !== 'patient') {
            return back()->with('error', 'Chỉ bệnh nhân mới được đặt lịch khám.');
        }

        // Lấy hồ sơ bệnh nhân tương ứng với user hiện tại
        $patient = Patient::where('user_id', $user->id)->first();

        if (!$patient) {
            return back()->with('error', 'Tài khoản của bạn chưa được xác nhận hồ sơ bệnh nhân.');
        }

        $request->validate(
            [
                'username' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|regex:/^[0-9]{10,11}$/',
                'appointment_date' => 'required|date',
                'schedule_id' => 'required|exists:doctor_schedules,id',
                'notes' => 'nullable|string|max:500',
            ],
            [
                'username.required' => 'Tên bệnh nhân là bắt buộc.',
                'email.required' => 'Email là bắt buộc.',
                'phone.required' => 'Số điện thoại là bắt buộc.',
                'phone.regex' => 'Số điện thoại phải gồm 10–11 chữ số.',
                'appointment_date.required' => 'Ngày hẹn là bắt buộc.',
                'schedule_id.required' => 'Lịch khám là bắt buộc.',
                'schedule_id.exists' => 'Lịch khám không tồn tại.',
            ],
        );

        // 🔹 Lấy lịch bác sĩ cụ thể
        $doctorSchedule = DoctorSchedule::findOrFail($request->schedule_id);

        // 🔹 Kiểm tra xem lịch còn chỗ không
        if ($doctorSchedule->limit_per_hour <= 0) {
            return back()->with('error', '⚠️ Lịch khám này đã đầy, vui lòng chọn khung giờ khác.');
        }

        // 🔹 Tạo lịch hẹn mới
        $appointment = new Appointment();
        $appointment->patient_id = $patient->id;
        $appointment->booked_by = $user->id;
        $appointment->doctor_id = $doctor->id;
        $appointment->schedule_id = $request->schedule_id;
        $appointment->username = $request->username;
        $appointment->email = $request->email;
        $appointment->phone = $request->phone;
        $appointment->appointment_date = $request->appointment_date;
        $appointment->notes = $request->notes;
        $appointment->status = 'pending';
        $appointment->save();

        // 🔹 Giảm giới hạn
        $doctorSchedule->decrement('limit_per_hour');

        return back()->with('success', '✅ Lịch khám đã được đặt thành công! Vui lòng chờ bác sĩ xác nhận.');
    }

    // Lọc lịch khám
    public function filter_appointment(Request $request)
    {
        $request->validate(
            [
                'department' => 'nullable|exists:departments,id',
                'day_of_week' => 'nullable|in:Monday,Tuesday,Wednesday,Thursday,Friday',
                'schedule_id' => 'nullable|exists:schedules,id',
            ],
            [
                'department.exists' => 'Phòng khám không tồn tại.',

                'day_of_week.in' => 'Ngày trong tuần không hợp lệ.',

                'schedule_id.exists' => 'Lịch khám không tồn tại.',
            ],
        );
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

    public function show(Request $request, $slug)
    {
        $blog = Blog::with(['doctor.user', 'images'])
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedBlogs = Blog::where('doctor_id', $blog->doctor_id)->where('id', '!=', $blog->id)->take(3)->get();

        return view('clients.blog-detail', compact('blog', 'relatedBlogs'));
    }

    public function about()
    {
        $doctors = Doctor::with('user')->take(6)->get();
        return view('clients.hospital-info', compact('doctors'));
    }

    public function contact()
    {
        return view('clients.contact');
    }
    public function send(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'message' => 'required|string',
            ],
            [
                'name.required' => 'Bạn phải điền đầy đủ thông tin họ tên',
                'email.required' => 'Bạn phải điền đầy đủ thông tin email',
                'email.email' => 'Bạn phải email hợp lệ',
                'message.required' => 'Bạn phải điền thông tin liên hệ',
            ],
        );

        Contact::create($request->only('name', 'email', 'phone', 'subject', 'message'));

        return back()->with('success', 'Cảm ơn bạn đã liên hệ. Chúng tôi sẽ phản hồi sớm nhất!');
    }

    public function blogList()
    {
        $blogs = Blog::with(['doctor.user', 'images'])
            ->latest()
            ->paginate(6); // chia trang 6 bài mỗi trang

        return view('clients.blogs', compact('blogs'));
    }

    public function feedback()
    {
        return view('clients.feedback');
    }

    public function send_feedback(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'text' => 'required|string|max:1000',
        ]);

        $patient = Auth::user()->patient; // Lấy bản ghi từ bảng patients qua quan hệ

        if (!$patient) {
            return back()->with('error', 'Không tìm thấy thông tin bệnh nhân.');
        }

        Feedback::create([
            'patient_id' => $patient->id,
            'rating' => $request->rating,
            'text' => $request->text,
        ]);

        return back()->with('success', 'Cảm ơn bạn đã gửi đánh giá!');
    }

    public function viewClientAppointment()
    {
        if (!Auth::check()) {
            // Trả về view, truyền appointments rỗng và một thông báo lỗi
            return view('clients.listAppointment', [
                'appointments' => collect(),
                'error' => 'Bạn cần đăng nhập để xem lịch khám.',
            ]);
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

        $user = Auth::user();

        // SỬA LỖI LỌC: Lấy patient_id từ mô hình Patient
        if (!$user->patient) {
            // Người dùng đã đăng nhập nhưng chưa có hồ sơ bệnh nhân
            return view('clients.listAppointment', [
                'appointments' => collect(),
                'error' => 'Không tìm thấy hồ sơ bệnh nhân liên kết với tài khoản này.',
            ]);
        }


        $patientIdToFilter = $user->patient->id;

        $appointments = Appointment::with(['doctor.user', 'schedule.schedule'])

            ->where('patient_id', $patientIdToFilter)
            ->orderBy('appointment_date', 'desc')
            ->get();

        // Xử lý và gán dữ liệu hiển thị (Thứ và Giờ)
        foreach ($appointments as $appointment) {
            if ($appointment->schedule && $appointment->schedule->schedule) {
                $dayEn = $appointment->schedule->day_of_week;
                // Đảm bảo Carbon được sử dụng đúng cách (đã thêm use Carbon)
                $appointment->day_vn = $dayMap[$dayEn] ?? Carbon::parse($appointment->appointment_date)->format('l');

                // Gán giờ khám vào thuộc tính ảo để dễ dùng trong view
                $appointment->start_time = $appointment->schedule->schedule->start_time;
                $appointment->end_time = $appointment->schedule->schedule->end_time;
            } else {
                // Xử lý khi thiếu dữ liệu schedule
                $dayEnFromDate = Carbon::parse($appointment->appointment_date)->format('l');
                $appointment->day_vn = $dayMap[$dayEnFromDate] ?? $dayEnFromDate;
                $appointment->start_time = '-';
                $appointment->end_time = '-';
            }
        }


        return view('clients.listAppointment', [
            'appointments' => $appointments,
            'success' => $appointments->isEmpty() ? 'Bạn chưa đặt lịch khám nào.' : null,
        ]);
    }

    public function ViewAppointmentDetail($id)
    {
        // // Bảo vệ: bắt buộc đăng nhập và có hồ sơ bệnh nhân
        // if (!Auth::check()) {
        //     abort(401, 'Bạn cần đăng nhập.');
        // }
        // $user = Auth::user();
        // if (!$user->patient) {
        //     abort(403, 'Không tìm thấy hồ sơ bệnh nhân liên kết với tài khoản này.');
        // }

        // // Eager load đầy đủ để lấy giờ khám giống danh sách
        // $appointment = Appointment::with(['doctor.user', 'doctor.department', 'patient.user', 'schedule.schedule'])
        //     ->findOrFail($id);

        // // Chỉ cho phép xem lịch của chính mình
        // if ($appointment->patient_id !== $user->patient->id) {
        //     abort(403, 'Bạn không có quyền xem lịch hẹn này.');
        // }

        // $appointments = Appointment::with(['doctor.user', 'schedule.schedule'])

        //     ->where('patient_id', $user)
        //     ->orderBy('appointment_date', 'desc')
        //     ->get();

        // // Xử lý và gán dữ liệu hiển thị (Thứ và Giờ)
        // foreach ($appointments as $appointment) {
        //     if ($appointment->schedule && $appointment->schedule->schedule) {
        //         $dayEn = $appointment->schedule->day_of_week;
        //         // Đảm bảo Carbon được sử dụng đúng cách (đã thêm use Carbon)
        //         $appointment->day_vn = $dayMap[$dayEn] ?? Carbon::parse($appointment->appointment_date)->format('l');

        //         // Gán giờ khám vào thuộc tính ảo để dễ dùng trong view
        //         $appointment->start_time = $appointment->schedule->schedule->start_time;
        //         $appointment->end_time = $appointment->schedule->schedule->end_time;
        //     } else {
        //         // Xử lý khi thiếu dữ liệu schedule
        //         $dayEnFromDate = Carbon::parse($appointment->appointment_date)->format('l');
        //         $appointment->day_vn = $dayMap[$dayEnFromDate] ?? $dayEnFromDate;
        //         $appointment->start_time = '-';
        //         $appointment->end_time = '-';
        //     }
        // }


        // $dayMap = [
        //     'Monday' => 'Thứ 2',
        //     'Tuesday' => 'Thứ 3',
        //     'Wednesday' => 'Thứ 4',
        //     'Thursday' => 'Thứ 5',
        //     'Friday' => 'Thứ 6',
        //     'Saturday' => 'Thứ 7',
        //     'Sunday' => 'Chủ nhật',
        // ];

        // // Tính toán thuộc tính hiển thị giống viewClientAppointment
        // if ($appointment->schedule && $appointment->schedule->schedule) {
        //     $dayEn = $appointment->schedule->day_of_week;
        //     $appointment->day_vn = $dayMap[$dayEn] ?? Carbon::parse($appointment->appointment_date)->format('l');
        //     $appointment->start_time = $appointment->schedule->schedule->start_time;
        //     $appointment->end_time = $appointment->schedule->schedule->end_time;
        // } else {
        //     $dayEnFromDate = Carbon::parse($appointment->appointment_date)->format('l');
        //     $appointment->day_vn = $dayMap[$dayEnFromDate] ?? $dayEnFromDate;
        //     $appointment->start_time = '-';
        //     $appointment->end_time = '-';
        // }

        // return view('clients.detail', compact('appointment'));

        if (!Auth::check()) abort(401, 'Bạn cần đăng nhập.');

        $user = Auth::user();
        if (!$user->patient) abort(403, 'Không tìm thấy hồ sơ bệnh nhân liên kết với tài khoản này.');

        $appointment = Appointment::with([
            'doctor.user',
            'doctor.department',
            'patient.user',
            'schedule.schedule'
        ])->findOrFail($id);

        if ($appointment->patient_id !== $user->patient->id)
            abort(403, 'Bạn không có quyền xem lịch hẹn này.');

        $dayMap = [
            'Monday' => 'Thứ 2',
            'Tuesday' => 'Thứ 3',
            'Wednesday' => 'Thứ 4',
            'Thursday' => 'Thứ 5',
            'Friday' => 'Thứ 6',
            'Saturday' => 'Thứ 7',
            'Sunday' => 'Chủ nhật',
        ];

        if ($appointment->doctorSchedule && $appointment->doctorSchedule->schedule) {
            $dayEn = $appointment->doctorSchedule->schedule->day_of_week;
            $appointment->day_vn = $dayMap[$dayEn] ?? Carbon::parse($appointment->appointment_date)->format('l');
            $appointment->start_time = $appointment->doctorSchedule->schedule->start_time;
            $appointment->end_time = $appointment->doctorSchedule->schedule->end_time;
        } else {
            $dayEnFromDate = Carbon::parse($appointment->appointment_date)->format('l');
            $appointment->day_vn = $dayMap[$dayEnFromDate] ?? $dayEnFromDate;
            $appointment->start_time = '-';
            $appointment->end_time = '-';
        }

        return view('clients.detail', compact('appointment'));
    }


    public function accountInfo()
    {

        $user = Auth::user();
        return view('clients.account', compact('user'));
    }

    public function updateAccountInfo(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|min:6|confirmed',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user->name = $validated['name'];

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('users', 'public');
            $user->image = $path;
        }

        $user->save();

        return back()->with('success', 'Cập nhật thông tin thành công!');
    }
}
