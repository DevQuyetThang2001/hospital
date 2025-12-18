@extends('receptionist.layouts.app')

@section('content')

    <div class="container-fluid py-4">

        <!-- TITLE -->
        <h2 class="fw-bold mb-4">📊 Dashboard lễ tân</h2>

        <!-- TOP STATS -->
        <div class="row g-3">

            <!-- Lịch hẹn hôm nay -->
            <div class="col-md-3">
                <div class="card shadow-sm border-0 p-3">
                    <h6 class="text-secondary">Lịch hẹn hôm nay</h6>
                    <h3 class="fw-bold text-primary">{{ $todayAppointments }}</h3>
                    <i class="bi bi-calendar2-check text-primary fs-3"></i>
                </div>
            </div>

            <!-- Đang chờ xác nhận -->
            <div class="col-md-3">
                <div class="card shadow-sm border-0 p-3">
                    <h6 class="text-secondary">Chờ xác nhận</h6>
                    <h3 class="fw-bold text-warning">{{ $pendingAppointments }}</h3>
                    <i class="bi bi-hourglass-split text-warning fs-3"></i>
                </div>
            </div>

            <!-- Bệnh nhân -->
            <div class="col-md-3">
                <div class="card shadow-sm border-0 p-3">
                    <h6 class="text-secondary">Tổng bệnh nhân</h6>
                    <h3 class="fw-bold text-success">{{ $patientCount }}</h3>
                    <i class="bi bi-people text-success fs-3"></i>
                </div>
            </div>

            <!-- Phòng trống -->
            <div class="col-md-3">
                <div class="card shadow-sm border-0 p-3">
                    <h6 class="text-secondary">Phòng còn trống</h6>
                    <h3 class="fw-bold text-info">{{ $emptyRooms }}</h3>
                    <i class="bi bi-hospital text-info fs-3"></i>
                </div>
            </div>

        </div>

        <!-- QUICK ACTION -->
        <div class="card shadow-sm border-0 p-4 mt-4">
            <h5 class="fw-bold mb-3">⚡ Thao tác nhanh</h5>
            <div class="d-flex gap-3">

                <a href="{{ route('receptionist.appointments.create') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-calendar-plus"></i> Đặt lịch khám
                </a>

                <a href="{{ route('receptionist.admission.create') }}" class="btn btn-warning btn-lg">
                    <i class="bi bi-hospital"></i> Nhập viện nhanh
                </a>

            </div>
        </div>

        <!-- APPOINTMENTS LIST -->
        <div class="card shadow-sm border-0 p-4 mt-4">
            <h5 class="fw-bold mb-3">📅 Lịch hẹn đang chờ xác nhận</h5>

            @if ($pendingList->isEmpty())
                <p class="text-danger">Không có lịch hẹn nào đang chờ xử lý.</p>
            @else
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Bệnh nhân</th>
                            <th>Bác sĩ</th>
                            <th>Thời gian</th>
                            <th>Ghi chú</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($pendingList as $item)
                            <tr>
                                <td>{{ $item->patient->user->name }}</td>
                                <td>{{ $item->doctor->user->name }}</td>
                                <td>{{ $item->appointment_date }}</td>
                                <td>{{ $item->notes ?? '—' }}</td>
                                <td>
                                    <form action="{{ route('receptionist.appointments.confirm', $item->id) }}"
                                        method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-success">Xác nhận</button>
                                    </form>

                                    <form action="{{ route('receptionist.appointments.reject', $item->id) }}"
                                        method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-danger">Hủy</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

    </div>

@endsection

<script>
    // XÁC NHẬN LỊCH HẸN
    document.querySelectorAll('.btn-confirm').forEach(btn => {
        btn.addEventListener('click', function() {
            const form = this.closest('form');
            Swal.fire({
                title: "Xác nhận lịch hẹn?",
                text: "Lịch hẹn sẽ được chuyển sang trạng thái ĐÃ XÁC NHẬN.",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#28a745",
                cancelButtonColor: "#d33",
                confirmButtonText: "Xác nhận"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // HỦY LỊCH HẸN
    document.querySelectorAll('.btn-reject').forEach(btn => {
        btn.addEventListener('click', function() {
            const form = this.closest('form');
            Swal.fire({
                title: "Hủy lịch hẹn?",
                text: "Bạn chắc chắn muốn hủy lịch này?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Hủy"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>


