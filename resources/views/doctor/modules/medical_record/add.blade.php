@extends('doctor.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">🩺 Thêm hồ sơ bệnh án</h4>
            </div>
            <div class="card-body p-4">

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form action="{{ route('doctor.patient.medicalRecord.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Chọn lịch hẹn (nếu có)</label>
                        <select name="appointment_id" class="form-select form-control">
                            <option value="">-- Không chọn (khám offline) --</option>
                            @foreach ($appointments as $app)
                                <option value="{{ $app->id }}">
                                    {{ $app->patient->user->name ?? 'Bệnh nhân chưa có tài khoản' }}
                                    - {{ $app->appointment_date }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Chọn bệnh nhân</label>
                        <select name="patient_id" class="form-select form-control" required>
                            <option value="">-- Chọn bệnh nhân --</option>
                            @foreach ($patients as $patient)
                                <option value="{{ $patient->id }}">
                                    {{ $patient->user->name ?? ($patient->name ?? 'Chưa có tên') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Chuẩn đoán</label>
                        <textarea name="diagnosis" class="form-control" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Phác đồ điều trị</label>
                        <textarea name="treatment" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Thuốc được kê</label>
                        <textarea name="medications" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary px-4">Lưu hồ sơ</button>
                        <a href="{{ route('doctor.patient.medicalRecord') }}" class="btn btn-secondary px-4 ms-2">Quay lại</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
