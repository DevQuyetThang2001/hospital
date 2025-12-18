@extends('clients.layouts.app')

@section('content')
    <div class="container py-5">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Chi tiết lịch hẹn khám bệnh</h4>
                <a href="{{ route('client.appointment.exportPDF', $appointment->id) }}" class="btn btn-light btn-sm">
                    <i class="bi bi-file-earmark-pdf"></i> Xuất PDF
                </a>
            </div>
            <div class="card-body p-4">

                {{-- Thông tin bệnh nhân --}}
                <h5 class="text-primary mb-3">👤 Thông tin bệnh nhân</h5>
                <p><strong>Họ tên:</strong>
                    {{ $appointment->username ?? ($appointment->patient->user->name ?? 'N/A') }}
                </p>
                @if ($appointment->booked_by)
                    <p><strong>Người đặt hộ</strong>
                        {{ $appointment->patient->user->name ?? 'N/A' }}
                    </p>
                @endif
                <p><strong>Số điện thoại:</strong> {{ $appointment->patient->user->phone ?? 'N/A' }}</p>
                <p><strong>Email:</strong> {{ $appointment->patient->user->email ?? 'N/A' }}</p>

                <hr>

                {{-- Thông tin bác sĩ --}}
                <h5 class="text-primary mb-3">🩺 Thông tin bác sĩ</h5>
                <p><strong>Tên bác sĩ:</strong> {{ $appointment->doctor->user->name ?? 'N/A' }}</p>
                <p><strong>Khoa:</strong> {{ $appointment->doctor->department->name ?? 'N/A' }}</p>
                <p><strong>Chuyên ngành:</strong> {{ $appointment->doctor->specialization ?? 'N/A' }}</p>
                <p><strong>Email liên hệ:</strong> {{ $appointment->doctor->user->email ?? 'N/A' }}</p>

                <hr>

                {{-- Thông tin lịch khám --}}
                <h5 class="text-primary mb-3">📅 Thông tin lịch hẹn</h5>
                <p><strong>Ngày khám:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}
                </p>
                <p><strong>Ngày khám:</strong>
                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}
                    ({{ $appointment->day_vn }})
                </p>
                <p><strong>Giờ khám:</strong>
                    {{ $appointment->schedule->schedule->start_time ?? '-' }} -
                    {{ $appointment->schedule->schedule->end_time ?? '-' }}
                </p>
                </p>
                <p><strong>Phòng khám:</strong> {{ $appointment->schedule->clinic->name ?? 'Chưa xếp phòng' }}</p>

                @php
                    $statusLabels = [
                        'pending' => '⏳ Chờ xác nhận',
                        'confirmed' => '✅ Đã xác nhận',
                        'cancelled' => '❌ Đã từ chối',
                        'completed' => '🏁 Hoàn thành',
                    ];
                    $statusColors = [
                        'pending' => 'bg-warning text-dark',
                        'confirmed' => 'bg-success text-white',
                        'cancelled' => 'bg-danger text-white',
                        'completed' => 'bg-primary text-white',
                    ];
                @endphp

                <p><strong>Trạng thái:</strong>
                    <span
                        class="px-3 py-1 rounded-pill {{ $statusColors[$appointment->status] ?? 'bg-secondary text-white' }}">
                        {{ $statusLabels[$appointment->status] ?? 'Không rõ' }}
                    </span>
                </p>

                <hr>

                {{-- Ghi chú --}}
                @if ($appointment->note)
                    <h5 class="text-primary mb-3">📝 Ghi chú từ bệnh nhân</h5>
                    <p>{{ $appointment->note }}</p>
                @endif

                <div class="text-center mt-4">
                    <p class="text-muted fst-italic">
                        Vui lòng mang giấy hẹn này đến lễ tân bệnh viện để được xác nhận trước khi khám.
                    </p>
                </div>

            </div>
        </div>



        <a href="{{ route('client.hospital.listAppointment') }}"
            class="btn btn-primary d-block align-items-center px-4 py-2 rounded-pill shadow-sm mt-3 mx-auto">
            <i class="bi bi-arrow-left me-2"></i> Quay lại danh sách lịch hẹn
        </a>
    </div>
@endsection
