<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Avatar;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\Patient;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index(){

        if (!Auth::check() || Auth::user()->role !== 'admin') {
           return redirect()->route('auth.index');
        }
        return view('admin.modules.dashboard');
    }

    // --------- USERS ---------- 
    public function users(){
        $data = User::all();
        // dd($data);
        // $avatar = User::with('avatars')->get()


        return view('admin.modules.users.list', compact('data'));
    }

    public function add_user(){
        return view('admin.modules.users.add');
    }

    public function create_user(Request $request){
        $user = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5',
            'role' => 'required',
            'image' => 'required'
        ],[
            'name.required' => "Bắt buộc phải điền họ tên",
            'email.required' => "Bắt buộc phải điền email",
            'email.email' => "Email phải hợp lệ",
            'email.unique' => "Email đã tồn tại",
            'password.required' => "Bắt buộc phải điền mật khẩu",
            'password.min' => "Mật khẩu phải nhiều hơn 5 ký tự",
            'role.required' => "Vai trò của người dùng phải được chỉ định" 
        ]);


        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->role = $request->role;
        $user->image = $request->file('image')->store('avatars', 'public'); // Lưu vào thư mục storage/app/public/avatars
        $user->save();
    
        
        return redirect()->route('admin.users.list')->with('success',"Thêm thành công tài khoản");
    }

    public function edit_user($id){
        $user = User::find($id);
        // dd($user);
        return view('admin.modules.users.edit',compact('user'));
    }

    public function update_user($id, Request $request){
        $user = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'role' => 'required'
        ],[
            'name.required' => "Bắt buộc phải điền họ tên",
            'email.required' => "Bắt buộc phải điền email",
            'email.email' => "Email phải hợp lệ",
            'email.unique' => "Email đã tồn tại",
            'role.required' => "Vai trò của người dùng phải được chỉ định" 
        ]);
        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->active = $request->active;

        if($request->hasFile('image')){
            $imagePath = $request->file('image')->store('avatars', 'public');
            if($user->image){
                $user->image = $imagePath;
            }else {
                $user->image = $imagePath;
            }
        }

    
        $user->save();
        // dd($user);

        return redirect()->route('admin.users.list')->with('update','Sửa thành công tài khoản');
    }


    public function delete_user($id){
        $user = User::find($id);
        $user->delete();


        return back()->with('delete',"Xóa thành công");
    }



    // -------- Quản lý tài khoản ---------



    // -------- DEPARTMENTS ---------

    public function departments(){
        $data = Department::all();
        return view('admin.modules.departments.list', compact('data'));
    }

    public function add_department(){
        return view('admin.modules.departments.add');
    }

    public function create_department(Request $request){
        $department = $request->validate([
            'name' => 'required|unique:departments',
            'description' => 'required',
            'status' => 'required',
        ],[
            'name.required' => "Bắt buộc phải điền tên khoa viện",
            'name.unique' => "Tên khoa viện đã tồn tại",
            'description.required' => "Bắt buộc phải điền mô tả",
            'status.required' => "Bắt buộc phải chọn trạng thái",
        ]);


        $department = new Department();
        $department->name = $request->name;
        $department->description = $request->description;
        $department->status = $request->status;
        $department->save();

        // dd($department);


        return redirect()->route('admin.departments.list')->with('success','Thêm thành công khoa viện');
    }
    


    public function edit_department($id){
        $department = Department::find($id);
        return view('admin.modules.departments.edit',compact('department'));
    }


    public function update_department($id, Request $request){
        $department = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'status' => 'required',
        ],[
            'name.required' => "Bắt buộc phải điền tên khoa viện",
            'description.required' => "Bắt buộc phải điền mô tả",
            'status.required' => "Bắt buộc phải chọn trạng thái",
        ]);
        $department = Department::find($id);
        $department->name = $request->name;
        $department->description = $request->description;
        $department->status = $request->status;


        if($department->isDirty()){
            $department->save();
            return redirect()->route('admin.departments.list')->with('update','Sửa thành công khoa viện');
        }

        return redirect()->route('admin.departments.list');

    }
    


    // -------- Quản lý khoa viện ----------



    // ----------- DOCTORS -----------

    public function doctors(){
        $data = Doctor::all();

        // dd(count($data));
        return view('admin.modules.doctors.list',compact('data'));
    }


    public function add_doctor(){

        $user = User::where('role', 'doctor')
        ->whereNotIn('id', function($query) {
            $query->select('user_id')
                  ->from('doctors');
        })
        ->get();
        $department = Department::where('status',1)->get();
        // dd($user);
        // dd($department);

        return view('admin.modules.doctors.add',compact('user','department'));
    }


    public function create_doctor(Request $request){
        $doctor = $request->validate([
            'department_id' => 'required',
            'user_id' => 'required',
            'specialization' => 'required',
            'experience_years' => 'required',
            'license_number' => 'required',
        ],[
            'department_id.required' => "Bắt buộc phải chọn khoa viện",
            'user_id.required' => "Bắt buộc phải chọn tài khoản",
            'specialization.required' => "Bắt buộc phải điền chuyên môn",
            'experience_years.required' => "Bắt buộc phải điền số năm kinh nghiệm",
            'license_number.required' => "Bắt buộc phải điền chứng chỉ hành nghề",
        ]);


        $doctor = new Doctor();
        $doctor->user_id = $request->user_id;
        $doctor->department_id = $request->department_id;
        $doctor->specialization = $request->specialization;
        $doctor->experience_years = $request->experience_years;
        $doctor->license_number = $request->license_number;
        $doctor->save();

        // dd($doctor);


        return redirect()->route('admin.doctors.list')->with('success','Thêm thành công bác sĩ');
    }



    public function edit_doctor($id){
        $doctor = Doctor::find($id);
        $departments = Department::all();



        // dd($doctor);
        return view('admin.modules.doctors.edit',compact('doctor','departments'));
    }


    public function update_doctor($id,Request $request){

        $doctor = $request->validate([
            'specialization' => 'required',
            'license_number' => 'required',
            'experience_years' => 'required',
            'department_id' => 'required'
        ],[
            'specialization.required' => "Không được để trống chuyên môn",
            'license_number.required' => "Không được để trống chứng chỉ hành nghề",
            'experience_years.required' => "Không được để trống số năm kinh nghiệm",
            'department_id.required' => "Không được để trống số khoa viện",
        ]);
        $doctor = Doctor::find($id);
        $doctor->specialization = $request->specialization;
        $doctor->license_number = $request->license_number;
        $doctor->experience_years = $request->experience_years;
        $doctor->department_id = $request->department_id;
        $doctor->save();

        if($doctor->isDirty()){
            $doctor->save();
            return redirect()->route('admin.doctors.list')->with('update','Sửa thành công bác sĩ');
        }

        return redirect()->route('admin.doctors.list');



    }

    public function delete_doctor($id){
        $doctor = Doctor::find($id);
        $doctor->delete();


        return back()->with('delete',"Xóa thành công");
    }



    // ----------- QUAN LY PHONG BENH -----------

    public function rooms(){
        $rooms = Room::select('department_id', DB::raw('COUNT(*) as so_phong'))
        ->groupBy('department_id')
        ->get();

        $departmentsWithoutRooms = Department::whereNotIn('id', function($query) {
            $query->select('department_id')
                  ->from('rooms');
        })->get();

        // dd($rooms);
        // dd($departmentsWithoutRooms);
        return view('admin.modules.rooms.list',compact('rooms','departmentsWithoutRooms'));
    }

    public function add(){
        // $department = Department::all();
        $departmentsWithoutRooms = Department::whereNotIn('id', function($query) {
            $query->select('department_id')
                  ->from('rooms');
        })->get();

       
        return view('admin.modules.rooms.add_form',compact('departmentsWithoutRooms'));
    }

    public function add_room_empty_department(Request $request){
        $room = $request->validate([
            'room_number' => 'required|unique:rooms',
            'type' => 'required',
            'status' => 'required',
            'capacity' => 'required',
            'department_id' => 'required'
        ],[
            'room_number.required' => "Bắt buộc phải điền số phòng bệnh",
            'room_number.unique' => "Số phòng đã tồn tại",
            'type.required' => "Bắt buộc phải chọn kiểu phòng bệnh",
            'status.required' => "Bắt buộc phải chọn trạng thái",
            'capacity.required' => 'Bắt buộc phải điền số giường bệnh',
            'department_id.required' => 'Bắt buộc phải chọn khoa viện',
        ]);


        $room = new Room();
        $room->room_number = $request->room_number;
        $room->type = $request->type;
        $room->status = $request->status;
        $room->capacity = $request->capacity;
        $room->department_id = $request->department_id;
        $room->save();

        return redirect()->route('admin.rooms.list')->with('success',"Thêm thành công phòng bệnh");

    }


    public function roomsDetails($id){
        $rooms = Room::where('department_id', $id)->get();

        // Kiểm tra nếu không có phòng nào
        // if ($rooms->isEmpty()) {    
        //     return redirect()->back()->with('alert', 'Không tìm thấy phòng');
        // }

        // dd($rooms);
        return view('admin.modules.rooms.list-room', compact('rooms'));
    }

    public function add_room($id){
        $rooms = Room::where('department_id',$id)->first();
        return view('admin.modules.rooms.add',compact('rooms'));
    }




    public function create_room(Request $request){
        $room = $request->validate([
            'room_number' => 'required|unique:rooms',
            'type' => 'required',
            'status' => 'required',
            'capacity' => 'required',
        ],[
            'room_number.required' => "Bắt buộc phải điền số phòng bệnh",
            'room_number.unique' => "Số phòng đã tồn tại",
            'type.required' => "Bắt buộc phải chọn kiểu phòng bệnh",
            'status.required' => "Bắt buộc phải chọn trạng thái",
            'capacity.required' => 'Bắt buộc phải điền số giường bệnh',
        ]);


        $room = new Room();
        $room->room_number = $request->room_number;
        $room->type = $request->type;
        $room->status = $request->status;
        $room->capacity = $request->capacity;
        $room->department_id = $request->department_id;
        $room->save();

        return redirect()->route('admin.roomsDetails.list',$request->department_id)->with('success',"Thêm thành công phòng bệnh");

    }


    public function edit_room($department_id,$room_id){

        $room = Room::where('id', $room_id)
                ->where('department_id', $department_id)
                ->firstOrFail();

        // dd($room);

        return view('admin.modules.rooms.edit', compact('room'));
    }


    public function update_room(Request $request,$department_id, $room_id){
        // DB::enableQueryLog();
        $request->validate([
            'room_number' => 'required',
            'type' => 'required',
            'status' => 'required',
            'capacity' => 'required',
        ],[
            'room_number.required' => "Bắt buộc phải điền số phòng bệnh",
            'type.required' => "Bắt buộc phải chọn kiểu phòng bệnh",
            'status.required' => "Bắt buộc phải chọn trạng thái",
            'capacity.required' => 'Bắt buộc phải điền số giường bệnh',
        ]);


        $room = Room::where('department_id', $department_id)->find($room_id);

        $room->room_number = $request->room_number;
        $room->type = $request->type;
        $room->capacity = $request->capacity;
        $room->status = $request->status;
        $room->save();

        if($room->isDirty()){
            $room->save();
            return redirect()->route('admin.roomsDetails.list', ['id' => $department_id])->with('update', 'Cập nhật phòng thành công.');
        }

        return redirect()->route('admin.roomsDetails.list', ['id' => $department_id]);

    }


    public function delete_room($department_id, $room_id){
        $room = Room::where('department_id', $department_id)->find($room_id);
        if(!$room){
            return redirect()->back()->with('alert','Không tìm thấy phòng cần xóa');
        }

        $room->delete();
        return redirect()->route('admin.roomsDetails.list',$department_id)->with('delete','Xóa thành công phòng bệnh');
    }







      // ----------- QUAN LY BENH NHAN -----------



    public function patients(){
        $data = Patient::all();
        return view('admin.modules.patients.list',compact('data'));
    }


    public function add_patient(){
        $department = Department::all();
        $user = User::where('role','patient')->whereNotIn('id', function($query) {
            $query->select('user_id')
                  ->from('patients');
        })->get();

        // dd($user);
        return view('admin.modules.patients.add',compact('department','user'));
    }


    public function create_patient(Request $request){
        $patient = $request->validate([
            'department_id' => 'required',
            'user_id' => 'required',
            'gender' => 'required',
            'date_of_birth' => 'required',
            'address' => 'required',
            'phone' => 'required|unique:patients|max:11'
        ],[
            'department_id.required' => "Bắt buộc phải chọn khoa viện",
            'user_id.required' => "Bắt buộc phải chọn tài khoản",
            'gender.required' => "Bắt buộc phải chọn giới tính",
            'date_of_birth.required' => "Bắt buộc phải ngày sinh",
            'phone.required' => 'Bắt buộc phải điền số điện thoại',
            'phone.unique' => 'Số điện thoại đã tồn tại',
            'phone.max' => 'Số điện thoại phải hợp lệ với 11 chữ số',
            'address.required' => 'Bắt buộc phải điền địa chỉ',
        ]);


        $patient = new Patient();
        $patient->department_id = $request->department_id;
        $patient->user_id = $request->user_id;
        $patient->gender = $request->gender;
        $patient->date_of_birth = $request->date_of_birth;
        $patient->address = $request->address;
        $patient->phone = $request->phone;
        $patient->save();

        // dd($patient);

        return redirect()->route('admin.patients.list')->with('success',"Xác nhận thành công bệnh nhân");

    }

    public function edit_patient($id){
        $departments = Department::all();
        $patient = Patient::find($id);
        

        return view('admin.modules.patients.edit', compact('patient','departments'));
    }


    public function update_patient($id, Request $request){


        $patient = $request->validate([
            'date_of_birth' => 'required',
            'gender' => 'required',
            'phone' => 'required|numeric',
            'address' => 'required',
            'department_id' => 'required'
        ],[
            'date_of_birth.required' => "Không được để trống ngày tháng năm sinh",
            'gender.required' => "Phải chọn giới tính",
            'phone.required' => "Không được để trống số điện thoại",
            'phone.numeric' => "Số điện thoại phải là kiểu số",
            'address.required' => "Không được để trống địa chỉ",
            'department_id.required' => "Không được để trống khoa viện",
        ]);
        $patient = Patient::findOrFail($id);
        $patient->date_of_birth = $request->date_of_birth;
        $patient->gender = $request->gender;
        $patient->phone = $request->phone;
        $patient->address = $request->address;
        $patient->department_id = $request->department_id;
        // $patient->save();

        if ($patient->isDirty()) { 
            $patient->save();
            return redirect()->route('admin.patients.list')->with('update', 'Sửa thành công bệnh nhân');
        }
        
        return redirect()->route('admin.patients.list'); // Không hiển thị thông báo nếu không có thay đổi
        


    }


    public function delete_patient($id){
        $patient = Patient::findOrFail($id);
        $patient->delete();
        return redirect()->route('admin.patients.list')->with('delete','Xóa thành công bệnh nhân');
    }



    // ---- QUAN LY LICH LAM VIEC -----

    // public function schedules(){
    //     $doctorsMonday = Doctor::whereHas('schedule', function ($query) {
    //         $query->where('day_of_week', 'Monday'); // Lọc theo thứ Hai
    //     })->with(['schedule' => function ($query) {
    //         $query->where('day_of_week', 'Monday')
    //               ->select('schedules.id', 'start_time', 'end_time'); // Lấy thêm giờ làm việc
    //     }])->get();

    //     $doctorsTuesday = Doctor::whereHas('schedule', function ($query) {
    //         $query->where('day_of_week', 'Tuesday'); // Lọc theo thứ Hai
    //     })->with(['schedule' => function ($query) {
    //         $query->where('day_of_week', 'Tuesday')
    //               ->select('schedules.id', 'start_time', 'end_time'); // Lấy thêm giờ làm việc
    //     }])->get();
    
    // // // Hiển thị kết quả
    // // foreach ($doctorsMonday as $doctor) {
    // //     echo "Bác sĩ: {$doctor->user->name} - Chuyên khoa: {$doctor->specialization} \n";
    // //     foreach ($doctor->schedule as $schedule) {
    // //         echo "  Giờ làm việc: {$schedule->start_time} - {$schedule->end_time} \n";
    // //     }
    // // }

    // // echo "\n-----------------------";

    // // foreach ($doctorsTuesday as $doctor) {
    // //     echo "Bác sĩ: {$doctor->user->name} - Chuyên khoa: {$doctor->specialization} \n";
    // //     foreach ($doctor->schedule as $schedule) {
    // //         echo "  Giờ làm việc: {$schedule->start_time} - {$schedule->end_time} \n";
    // //     }
    // // }

    // // dd($doctorsMonday);
       
    //     // return view("admin.modules.schedules.list");


    // return view('admin.modules.schedules.list',compact('doctorsMonday', 'doctorsTuesday'));

        
    
    // }

}
