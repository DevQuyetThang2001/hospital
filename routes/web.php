<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Doctor\DoctorController;
use App\Http\Controllers\Manager\ManagerController;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\PreventBackHistory;
use Illuminate\Support\Facades\Route;



// Clients


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/lịch-khám', [HomeController::class, 'appointment'])->name('appointment');
// Lọc lịch khám
Route::get('/lịch-khám/lọc-lịch-khám', [HomeController::class, 'filter_appointment'])->name('doctor.appointment.filter');
Route::get('/lịch-khám/{doctor}', [HomeController::class, 'appointmentDetail'])->name('doctor.appointment');
Route::post('/lịch-khám/{doctor}', [HomeController::class, 'appointmentStore'])->name('doctor.appointment.post');


// Bài viết

Route::get('/cẩm-năng-y-khoa', [HomeController::class, 'blogList'])->name('client.blog.list');
Route::get('/cẩm-năng-y-khoa/{slug}', [HomeController::class, 'show'])->name('client.blog.show');

// Thông tin bệnh viện

Route::get('/thông-tin-bệnh-viện',[HomeController::class,'about'])->name('client.hospital.info');


// Liên hệ

Route::get('/liên-hệ',[HomeController::class,'contact'])->name('client.hospital.contact');

Route::post('liên-hệ',[HomeController::class,'send'])->name('client.hospital.contact.send');
// -------------------------------------------------

// Đánh giá

Route::get('/đánh-giá',[HomeController::class,'feedback'])->name('client.hospital.feedback');
Route::post('/đánh-giá',[HomeController::class,'send_feedback'])->name('client.hospital.feedback.store');


// Lịch khám của bạn
Route::get('/lịch-khám-của-bạn',[HomeController::class,'viewClientAppointment'])->name('client.hospital.listAppointment');
// ----------------- ROLE ADMIN --------------------
// Route::get('/dashboard', function () {
//     return 'Welcome to Dashboard!';
// })->middleware('auth');
Route::middleware(['auth', PreventBackHistory::class, CheckRole::class . ':admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.index');

    // users
    Route::get('/admin/dashboard/users', [AdminController::class, 'users'])->name('admin.users.list');
    Route::get('/admin/dashboard/add_user', [AdminController::class, 'add_user'])->name('admin.users.addUserForm');
    Route::post('/admin/dashboard/add_user', [AdminController::class, 'create_user'])->name('admin.users.add');
    Route::get('/admin/dashboard/edit_user/{id}', [AdminController::class, 'edit_user'])->name('admin.users.editUserForm');
    Route::post('/admin/dashboard/edit_user/{id}', [AdminController::class, 'update_user'])->name('admin.users.edit');
    Route::get('/admin/dashboard/delete_user/{id}', [AdminController::class, 'delete_user'])->name('admin.users.delete');

    // departments

    Route::get('/admin/dashboard/departments', [AdminController::class, 'departments'])->name('admin.departments.list');
    Route::get('/admin/dashboard/add_department', [AdminController::class, 'add_department'])->name('admin.departments.addDepartmentForm');
    Route::post('/admin/dashboard/add_department', [AdminController::class, 'create_department'])->name('admin.departments.add');
    Route::get('/admin/dashboard/edit_department/{id}', [AdminController::class, 'edit_department'])->name('admin.departments.editDepartmentForm');
    Route::post('/admin/dashboard/edit_department/{id}', [AdminController::class, 'update_department'])->name('admin.departments.edit');


    // doctors
    Route::get('/admin/dashboard/doctors', [AdminController::class, 'doctors'])->name('admin.doctors.list');
    Route::get('/admin/dashboard/add_doctor', [AdminController::class, 'add_doctor'])->name('admin.doctors.addDoctorForm');
    Route::post('/admin/dashboard/add_doctor', [AdminController::class, 'create_doctor'])->name('admin.doctors.add');
    Route::get('/admin/dashboard/edit_doctor/{id}', [AdminController::class, 'edit_doctor'])->name('admin.doctor.editDoctorForm');
    Route::post('/admin/dashboard/edit_doctor/{id}', [AdminController::class, 'update_doctor'])->name('admin.doctor.edit');
    Route::get('/admin/dashboard/delete_doctor/{id}', [AdminController::class, 'delete_doctor'])->name('admin.doctors.delete');




    // rooms

    Route::get('/admin/dashboard/rooms', [AdminController::class, 'rooms'])->name('admin.rooms.list');
    Route::get('/admin/dashboard/rooms/add', [AdminController::class, 'add'])->name('admin.rooms.addForm');
    Route::post('/admin/dashboard/rooms/add', [AdminController::class, 'add_room_empty_department'])->name('admin.rooms.addRoomEmptyDepartment');
    Route::get('/admin/dashboard/rooms/{id}', [AdminController::class, 'roomsDetails'])->name('admin.roomsDetails.list');
    Route::get('/admin/dashboard/rooms/{id}/add_room', [AdminController::class, 'add_room'])->name('admin.rooms.addRoomForm');
    Route::post('/admin/dashboard/rooms/{id}/add_room', [AdminController::class, 'create_room'])->name('admin.rooms.add');
    Route::get('/admin/dashboard/rooms/{department_id}/edit_room/{room_id}', [AdminController::class, 'edit_room'])->name('admin.rooms.editRoomForm');
    Route::post('/admin/dashboard/rooms/{department_id}/edit_room/{room_id}', [AdminController::class, 'update_room'])->name('admin.rooms.edit');
    Route::get('/admin/dashboard/rooms/{department_id}/delete/{room_id}', [AdminController::class, 'delete_room'])->name('admin.rooms.delete');



    // patients

    Route::get('/admin/dashboard/patients', [AdminController::class, 'patients'])->name('admin.patients.list');
    Route::get('/admin/dashboard/add_patient', [AdminController::class, 'add_patient'])->name('admin.patients.addPatientForm');
    Route::post('/admin/dashboard/add_patient', [AdminController::class, 'create_patient'])->name('admin.patients.add');
    Route::get('/admin/dashboard/edit_patient/{id}', [AdminController::class, 'edit_patient'])->name('admin.patient.editPatientForm');
    Route::post('/admin/dashboard/edit_patient/{id}', [AdminController::class, 'update_patient'])->name('admin.patient.edit');
    Route::get('/admin/dashboard/delete_patient/{id}', [AdminController::class, 'delete_patient'])->name('admin.patient.delete');


    //View Schedules

    Route::get('/admin/dashboard/schedules', [ScheduleController::class, 'schedules'])->name('admin.schedules.list');


    // Feedbacks

    Route::get("/admin/dashboard/feedbacks", [AdminController::class, 'feedbacks'])->name('admin.feedbacks.list');
    Route::get('/admin/dashboard/delete_feedback/{id}', [AdminController::class, 'delete_feedback'])->name('admin.feedbacks.delete');
});




// ------------ DOCTOR ROLE ---------------
Route::middleware(['auth', PreventBackHistory::class, CheckRole::class . ':doctor'])->group(function () {
    Route::get('/doctor/dashboard', [DoctorController::class, 'index'])->name('doctor.index');
    // Tao lịch khám
    Route::get('/doctor/schedules/view-all', [ScheduleController::class, 'doctorSchedules'])->name('doctor.schedules.all');
    Route::get('/doctor/schedules/list', action: [DoctorController::class, 'listSchedules'])->name('doctor.schedules.list');
    Route::get('/doctor/schedules/create', [DoctorController::class, 'createSchedule'])->name('doctor.schedules.create');
    Route::post('/doctor/schedules/create', [DoctorController::class, 'storeSchedule'])->name('doctor.schedules.store');
    Route::get('/doctor/schedules/edit/{id}', [DoctorController::class, 'editSchedule'])->name('doctor.schedules.edit');
    Route::post('/doctor/schedules/edit/{id}', [DoctorController::class, 'updateSchedule'])->name('doctor.schedules.update');
    Route::get('/doctor/schedules/delete/{id}', [DoctorController::class, 'deleteSchedule'])->name('doctor.schedules.delete');
    Route::get('/doctor/blogs', [DoctorController::class, 'blogs'])->name('doctor.blogs.list');
    // Cẩm năng y khoa
    Route::get('/doctor/blogs/create', [DoctorController::class, 'createBlog'])->name('doctor.blogs.create');
    Route::post('/doctor/blogs/create', [DoctorController::class, 'storeBlog'])->name('doctor.blogs.store');
    Route::get(
        '/doctor/blogs/edit/{id}',
        [DoctorController::class, 'editBlog']
    )->name('doctor.blogs.edit');

    Route::put(
        '/doctor/blogs/edit/{id}',
        [DoctorController::class, 'updateBlog']
    )->name('doctor.blogs.update');


    Route::delete('doctor/blogs/{id}', [DoctorController::class, 'deleteBlog'])
        ->name('doctor.blogs.delete');


    // Xem lịch khám bệnh và xác nhận

    Route::get('doctor/list_appointment',[DoctorController::class,'viewAppointment'])->name('doctor.listAppointment');

});





// ------------ MANAGER ROLE ---------------
Route::middleware(['auth', PreventBackHistory::class, CheckRole::class . ':schedule_manager'])->group(function () {
    Route::get('/manager/dashboard', [ManagerController::class, 'index'])->name('manager.index');
    // Tao lịch khám
    Route::get('/manager/schedules/view-all', [ManagerController::class, 'doctorSchedules'])->name('manager.schedules.all');
    Route::get('/manager/schedules/list',  [ManagerController::class, 'listSchedules'])->name('manager.schedules.list');
    Route::get('/manager/schedules/create', [ManagerController::class, 'createSchedule'])->name('manager.schedules.create');
    Route::post('/manager/schedules/create', [ManagerController::class, 'storeSchedule'])->name('manager.schedules.store');
    Route::get('/manager/schedules/edit/{id}', [ManagerController::class, 'editSchedule'])->name('manager.schedules.edit');
    Route::put('/manager/schedules/edit/{id}', [ManagerController::class, 'updateSchedule'])->name('manager.schedules.update');
    Route::delete('/manager/schedules/delete/{id}', [ManagerController::class, 'deleteSchedule'])->name('manager.schedules.delete');


   
});


























Route::get('đăng-nhập', [AuthController::class, 'index'])->name('login');
Route::post('đăng-nhập', [AuthController::class, 'login'])->name('auth.login');
Route::get('đăng-ký', [AuthController::class, 'create'])->name('auth.create');
Route::post('đăng-ký', [AuthController::class, 'register'])->name('auth.register');
Route::get('đăng-xuất', [AuthController::class, 'logout'])->name('auth.logout');
