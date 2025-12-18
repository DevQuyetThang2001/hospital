<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Diseases;
use Illuminate\Http\Request;

class DiseasesController extends Controller
{
    public function index()
    {
        $diseases = Diseases::with('department')->get();
        return view('admin.modules.diseases.list', compact('diseases'));
    }

    public function add()
    {
        $departments = Department::where('status', 1)->get();
        return view('admin.modules.diseases.add', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'description' => 'required|string',
        ], [
            'name.required' => 'Vui lòng nhập tên loại bệnh.',
            'department_id.required' => 'Vui lòng chọn khoa.',
            'department_id.exists' => 'Khoa không tồn tại.',
            'description.string' => 'Mô tả phải là chuỗi ký tự.',
            'description.required' => 'Vui lòng nhập mô tả.',
        ]);

        Diseases::create([
            'name' => $request->name,
            'department_id' => $request->department_id,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.diseases.list')->with('success', 'Thêm loại bệnh thành công.');
    }

    public function edit($id)
    {
        $disease = Diseases::findOrFail($id);
        $departments = Department::all();
        return view('admin.modules.diseases.edit', compact('disease', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'department_id' => 'required',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ],[
            'department_id.required' => 'Vui lòng chọn khoa.',
            'name.required' => 'Vui lòng nhập tên loại bệnh.',
            'description.required' => 'Vui lòng nhập mô tả.',
            'description.string' => 'Mô tả phải là chuỗi ký tự.',
        ]);

        $disease = Diseases::findOrFail($id);
        $disease->department_id = $request->department_id;
        $disease->name = $request->name;
        $disease->description = $request->description;

        if(!$disease->isDirty()) {
            return redirect()->back()->with('info', 'Không có thay đổi nào được thực hiện.');
        }

        $disease->save();

        return redirect()->route('admin.diseases.list')->with('success', 'Cập nhật thành công');
    }

    public function delete($id)
    {
        $disease = Diseases::findOrFail($id);
        $disease->delete();

        return redirect()->route('admin.diseases.list')->with('success', 'Xóa loại bệnh thành công.');
    }
}
