<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Clinics;
use App\Models\Department;
use Illuminate\Http\Request;

class ClinicController extends Controller
{
    public function index()
    {
        $clinics = Clinics::all();
        return view('manager.modules.clinics.list', compact('clinics'));
    }


    public function createClinic()
    {
        $departments = Department::where('status', '1')->where('name', 'Khoa Khám Bệnh')->get();
        return view('manager.modules.clinics.add', compact('departments'));
    }

    public function storeClinic(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'status' => 'required|in:0,1',
            'quantity' => 'required|numeric|between:0,4',
        ], [
            'name.required' => 'Tên phòng khám không được để trống.',
            'department_id.required' => 'Vui lòng chọn khoa.',
            'department_id.exists' => 'Khoa không tồn tại.',
            'status.required' => 'Trạng thái không được để trống.',
            'status.in' => 'Trạng thái không hợp lệ.',
            'quantity.required' => 'Số lượng không được để trống.',
            'quantity.numeric' => 'Số lượng phải là một số.',
            'quantity.between' => 'Số lượng phải nằm trong khoảng từ 0 đến 4.',
        ]);

        Clinics::create([
            'name' => $request->name,
            'department_id' => $request->department_id,
            'status' => $request->status,
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('manager.clinics.list')->with('success', 'Phòng khám đã được tạo thành công.');
    }

    public function editClinic($id)
    {
        $clinic = Clinics::findOrFail($id);
        $departments = Department::where('status', '1')->where('name', 'Khoa Khám Bệnh')->get();
        return view('manager.modules.clinics.edit', compact('clinic', 'departments'));
    }

    public function updateClinic(Request $request, $id)
    {
        $clinic = Clinics::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:0,1',
            'quantity' => 'required|integer|between:0,4',
        ], [
            'name.required' => 'Tên phòng khám không được để trống.',
            'status.required' => 'Trạng thái không được để trống.',
            'status.in' => 'Trạng thái không hợp lệ.',
            'quantity.required' => 'Số lượng bác sĩ không được để trống.',
            'quantity.integer' => 'Số lượng bác sĩ phải là số nguyên.',
            'quantity.between' => 'Số lượng bác sĩ tối đa là 4.',
        ]);

        // 👉 Gán dữ liệu mới (CHƯA lưu DB)
        $clinic->fill([
            'name' => $request->name,
            'status' => $request->status,
            'quantity' => $request->quantity,
        ]);

        if (!$clinic->isDirty()) {
            return back()->with('info', 'Không có thông tin cần thay đổi'); // Không báo gì
        }

        // 👉 Có thay đổi thì mới lưu
        $clinic->save();

        return redirect()
            ->route('manager.clinics.list')
            ->with('success', 'Phòng khám đã được cập nhật thành công.');
    }


    public function deleteClinic($id)
    {
        $clinic = Clinics::findOrFail($id);
        $clinic->delete();

        return redirect()->route('manager.clinics.list')->with('success', 'Phòng khám đã được xóa thành công.');
    }
}
