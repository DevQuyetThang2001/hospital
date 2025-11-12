@extends('doctor.layouts.app')


@section('content')
    <div class="container-fluid py-5">

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-warning">{{ session('error') }}</div>
        @endif
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-header bg-success text-white d-flex justify-content-between">
                <h4 class="mb-0">Chi tiết hồ sơ bệnh án</h4>
                <a href="{{ route('doctor.patient.medicalRecord') }}" class="btn btn-light btn-sm">← Quay lại</a>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <h5 class="text-primary">Thông tin bệnh nhân</h5>
                    <p><strong>Họ tên:</strong> {{ $record->patient->user->name ?? $record->patient->name }}</p>
                    <p><strong>Email:</strong> {{ $record->patient->user->email ?? 'Không có email' }}</p>
                </div>

                <div class="mb-3">
                    <h5 class="text-primary">Thông tin bác sĩ</h5>
                    <p><strong>Bác sĩ:</strong> {{ $record->doctor->user->name }}</p>
                </div>

                <div class="mb-3">
                    <h5 class="text-primary">Chẩn đoán</h5>
                    <p>{{ $record->diagnosis }}</p>
                </div>

                <div class="mb-3">
                    <h5 class="text-primary">Phác đồ điều trị</h5>
                    <p>{{ $record->treatment }}</p>
                </div>

                <div class="mb-3">
                    <h5 class="text-primary">Thuốc sử dụng</h5>
                    <p>{{ $record->medications ? $record->medications : 'Chưa có thuốc sử dụng' }}</p>
                </div>

                <hr class="my-4">

                {{-- Xác nhận bác sĩ --}}
                {{-- <div class="text-end mt-4">
                    <p class="mb-1"><strong>Xác nhận của bác sĩ điều trị</strong></p>
                    <p class="mb-0 text-primary fw-bold">{{ $record->doctor->user->name }}</p>
                    <p class="text-muted"><i>Ngày xác nhận: {{ $record->created_at->format('d/m/Y') }}</i></p>
                </div> --}}

                <div class="mt-4">
                    <p class="mb-1"><strong>Các lần xác nhận phác đồ điều trị</strong></p>
                    @if ($record->treatmentHistories->isNotEmpty())
                        <ul class="list-unstyled">
                            @foreach ($record->treatmentHistories as $history)
                                <li class="mb-2">
                                    <p class="mb-0 text-primary fw-bold">
                                        {{ $history->medicalRecord->doctor->user->name ?? 'Không xác định' }}</p>
                                    <p class="text-muted">
                                        <i>Ngày xác nhận: {{ $history->created_at->format('d/m/Y H:i') }}</i>
                                    </p>
                                    <p><strong>Phác đồ:</strong> {{ $history->treatment }}</p>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted"><i>Chưa có lịch sử xác nhận.</i></p>
                    @endif
                </div>


                <button type="button" class="btn btn-sm btn-secondary" data-toggle="modal"
                    data-target="#modal-{{ $record->id }}">
                    <i class="mdi mdi-delete"></i> Cập nhật phác đồ điều trị
                </button>

            </div>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="modal-{{ $record->id }}" tabindex="-1" aria-labelledby="editTreatmentLabel"
            aria-hidden="true">
            <div class="modal-dialog **modal-dialog-centered**">
                <div class="modal-content">
                    <form action="{{ route('doctor.patient.medicalRecord.updateTreatment', $record->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title" id="editTreatmentLabel">Sửa phác đồ điều trị</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="treatment" class="form-label">Phác đồ điều trị</label>
                                <textarea name="treatment" id="treatment" class="form-control" rows="5" required>{{ $record->treatment }}</textarea>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="mb-2">
                                <label for="notes" class="form-label">Ghi chú (Để trống nếu không có thay đổi)</label>
                                <textarea name="notes" id="notes" class="form-control" rows="5">{{ $record->notes }}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
