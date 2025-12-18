@extends('receptionist.layouts.app')

@section('content')
    <div class="container-fluid py-5">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-primary">Danh sách nhập viện</h2>

            <a href="{{ route('receptionist.admission.create') }}" class="btn btn-add-admission">
                <i class="fas fa-hospital-user me-2"></i> Thêm nhập viện
            </a>
        </div>


        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        {{-- Bộ lọc --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">

                <form method="GET" class="row g-3">

                    {{-- Lọc theo phòng --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Số phòng</label>
                        <select name="room_id" class="form-control">
                            <option value="">-- Tất cả --</option>
                            @foreach ($rooms as $room)
                                <option value="{{ $room->id }}" {{ $roomFilter == $room->id ? 'selected' : '' }}>
                                    Phòng {{ $room->room_number }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tìm theo tên bệnh nhân --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Tên bệnh nhân</label>
                        <input type="text" name="search" class="form-control" value="{{ $searchName }}"
                            placeholder="Nhập tên bệnh nhân...">
                    </div>

                    {{-- Lọc theo ngày bắt đầu --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Từ ngày</label>
                        <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
                    </div>

                    {{-- Lọc theo ngày kết thúc --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Đến ngày</label>
                        <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
                    </div>

                    <div class="col-12 d-flex justify-content-end mt-2">
                        <button class="btn btn-primary me-2 px-4">Lọc</button>
                        <a href="{{ route('receptionist.admissions.index') }}" class="btn btn-outline-secondary px-4">
                            Reset
                        </a>
                    </div>

                </form>

            </div>
        </div>

        {{-- Table --}}
        <div class="card shadow-sm">
            <div class="card-body p-0">

                <table class="table table-hover table-striped mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th>Bệnh nhân</th>
                            <th>Phòng</th>
                            <th>Ngày nhập viện</th>
                            <th>Ngày xuất viện</th>
                            <th>Ghi chú</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($admissions as $ad)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                {{-- Tên bệnh nhân (ưu tiên patients.name → user.name) --}}
                                <td>{{ $ad->patient->name ?? ($ad->patient->user->name ?? 'N/A') }}</td>

                                {{-- Số phòng --}}
                                <td>{{ $ad->room->room_number ?? 'N/A' }}</td>

                                {{-- Ngày nhập viện --}}
                                <td>{{ \Carbon\Carbon::parse($ad->admission_date)->format('d/m/Y H:i') }}</td>

                                {{-- Ngày xuất viện --}}
                                <td>
                                    {{ $ad->discharge_date ? \Carbon\Carbon::parse($ad->discharge_date)->format('d/m/Y H:i') : '-' }}
                                </td>

                                <td>{{ $ad->notes ?? '-' }}</td>
                                <td>

                                    <a href="{{ route('receptionist.admission.edit', $ad->id) }}"
                                        class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Sửa
                                    </a>


                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>

    </div>
@endsection

{{-- CSS --}}
<style>
    .btn-add-admission {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: #fff !important;
        font-weight: 600;
        padding: 10px 22px;
        border-radius: 14px;
        border: none;
        box-shadow: 0 4px 10px rgba(32, 201, 151, 0.25);
        transition: all 0.25s ease-in-out;
        display: inline-flex;
        align-items: center;
    }

    .btn-add-admission:hover {
        background: linear-gradient(135deg, #20c997, #28a745);
        box-shadow: 0 6px 16px rgba(32, 201, 151, 0.35);
        transform: translateY(-2px);
        color: #fff !important;
    }

    .table-hover tbody tr:hover {
        background-color: #f3faff !important;
    }

    .table th,
    .table td {
        vertical-align: middle;
    }
</style>
