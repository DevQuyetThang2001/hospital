<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\Patient;
use App\Models\Room;
use Illuminate\Http\Request;

class AdmissionsController extends Controller
{
    // public function index()
    // {
    //     $admissions = Admission::with('patient', 'room')->orderBy('admission_date', 'desc')->get();
    //     return view('receptionist.modules.admissions.list', compact('admissions'));
    // }


    public function index(Request $request)
    {
        // Nhận các giá trị lọc
        $roomFilter = $request->room_id;
        $searchName = $request->search;
        $dateFrom = $request->date_from;
        $dateTo = $request->date_to;

        // Query cơ bản
        $query = Admission::with(['patient.user', 'room'])
            ->orderBy('admission_date', 'desc');

        // Lọc theo số phòng
        if ($roomFilter) {
            $query->where('room_id', $roomFilter);
        }

        // Tìm theo tên bệnh nhân
        if ($searchName) {
            $query->whereHas('patient', function ($q) use ($searchName) {
                $q->where('name', 'LIKE', "%{$searchName}%")
                    ->orWhereHas('user', function ($u) use ($searchName) {
                        $u->where('name', 'LIKE', "%{$searchName}%");
                    });
            });
        }

        // Lọc theo ngày nhập viện (khoảng thời gian)
        if ($dateFrom) {
            $query->whereDate('admission_date', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('admission_date', '<=', $dateTo);
        }

        // Lấy kết quả
        $admissions = $query->get();

        // Lấy danh sách phòng để hiển thị filter
        $rooms = Room::orderBy('room_number')->get();

        return view(
            'receptionist.modules.admissions.list',
            compact('admissions', 'rooms', 'roomFilter', 'searchName', 'dateFrom', 'dateTo')
        );
    }


    public function create()
    {
        $patients = Patient::with('user')->get();
        $rooms = Room::all();


        // dd($patients, $rooms);

        return view('receptionist.modules.admissions.create', compact('patients', 'rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'room_id' => 'required|exists:rooms,id',
            'admission_date' => 'required|date',
            'discharge_date' => 'nullable|date|after_or_equal:admission_date',
            'notes' => 'nullable|string|max:500',
        ]);

        Admission::create([
            'patient_id' => $request->patient_id,
            'room_id' => $request->room_id,
            'admission_date' => $request->admission_date,
            'discharge_date' => $request->discharge_date,
            'notes' => $request->notes,
        ]);

        return redirect()->route('receptionist.admission.create')->with('success', '✅ Nhập viện thành công!');
    }

    public function edit($id)
    {
        $admission = Admission::with('patient.user', 'room')->findOrFail($id);
        $rooms = Room::orderBy('room_number')->get();
        $patients = Patient::with('user')->get();

        return view('receptionist.modules.admissions.edit', compact('admission', 'rooms', 'patients'));
    }

    public function update(Request $request, $id)
    {
        $admission = Admission::findOrFail($id);

        // Validate
        $request->validate([
            'room_id' => 'nullable|exists:rooms,id',
            'admission_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $hasChanges = false;

        // Cập nhật phòng (nếu có chọn)
        if ($request->filled('room_id') && $request->room_id != $admission->room_id) {
            $admission->room_id = $request->room_id;
            $hasChanges = true;
        }

        // Cập nhật ngày nhập viện (nếu có nhập)
        if ($request->filled('admission_date') && $request->admission_date != $admission->admission_date) {
            $admission->admission_date = $request->admission_date;
            $hasChanges = true;
        }

        // Cập nhật ghi chú
        if ($request->filled('notes') && $request->notes != $admission->notes) {
            $admission->notes = $request->notes;
            $hasChanges = true;
        }

        // Nếu không thay đổi gì, trả về thông báo
        if (!$hasChanges) {
            return redirect()->back()->with('info', 'Không có thay đổi nào để cập nhật.');
        }

        $admission->save();

        return redirect()->route('receptionist.admissions.index')
            ->with('success', 'Cập nhật nhập viện thành công!');
    }
}
