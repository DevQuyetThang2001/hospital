@extends('doctor.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-primary">
                <i class="bi bi-hospital me-2"></i>Dashboard Bác sĩ
            </h2>
            <span class="text-muted">Chào mừng, <strong>{{ Auth::user()->name }}</strong> 👋</span>
        </div>

        <!-- Thống kê nhanh -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm border-0 stat-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="icon bg-primary text-white me-3">
                            <i class="bi bi-person-lines-fill fs-3"></i>
                        </div>
                        <div>
                            <h5 class="fw-semibold mb-0">{{ $patientsCount ?? 0 }}</h5>
                            <small class="text-muted">Bệnh nhân của bạn</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0 stat-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="icon bg-success text-white me-3">
                            <i class="bi bi-calendar-check fs-3"></i>
                        </div>
                        <div>
                            <h5 class="fw-semibold mb-0">{{ $todayAppointments ?? 0 }}</h5>
                            <small class="text-muted">Lịch khám hôm nay</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0 stat-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="icon bg-warning text-white me-3">
                            <i class="bi bi-clock-history fs-3"></i>
                        </div>
                        <div>
                            <h5 class="fw-semibold mb-0">{{ $upcomingAppointments ?? 0 }}</h5>
                            <small class="text-muted">Lịch sắp tới</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0 stat-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="icon bg-danger text-white me-3">
                            <i class="bi bi-chat-dots fs-3"></i>
                        </div>
                        <div>
                            <h5 class="fw-semibold mb-0">{{ $pendingConfirmations ?? 0 }}</h5>
                            <small class="text-muted">Tài khoản chờ xác nhận</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lịch khám hôm nay -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold text-primary mb-0"><i class="bi bi-calendar3 me-2"></i>Lịch khám hôm nay</h5>
                <a href="" class="btn btn-outline-primary btn-sm">
                    Xem tất cả
                </a>
            </div>
            <div class="card-body">
                @if (isset($todayAppointmentsList) && $todayAppointmentsList->count())
                    <div class="list-group">
                        @foreach ($todayAppointmentsList as $appt)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $appt->patient->user->name ?? 'Bệnh nhân chưa xác định' }}</strong><br>
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i>{{ $appt->time }}
                                    </small>
                                </div>
                                <span class="badge bg-success">Đã xác nhận</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center m-0">Không có lịch khám nào hôm nay.</p>
                @endif
            </div>
        </div>

        <!-- Khu vực nhanh -->
        <div class="row g-4">
            <div class="col-md-4">
                <a href="" class="quick-card text-decoration-none">
                    <div class="card shadow-sm border-0 p-4 text-center h-100">
                        <i class="bi bi-person-check display-5 text-success mb-2"></i>
                        <h6 class="fw-semibold">Xác nhận bệnh nhân</h6>
                        <small class="text-muted">Duyệt các tài khoản mới đăng ký</small>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="" class="quick-card text-decoration-none">
                    <div class="card shadow-sm border-0 p-4 text-center h-100">
                        <i class="bi bi-file-medical display-5 text-primary mb-2"></i>
                        <h6 class="fw-semibold">Hồ sơ y tế</h6>
                        <small class="text-muted">Xem và cập nhật bệnh án</small>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="" class="quick-card text-decoration-none">
                    <div class="card shadow-sm border-0 p-4 text-center h-100">
                        <i class="bi bi-capsule display-5 text-danger mb-2"></i>
                        <h6 class="fw-semibold">Thêm toa thuốc</h6>
                        <small class="text-muted">Ghi nhanh thuốc cho bệnh nhân</small>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection

<style>
    .icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .stat-card {
        border-radius: 15px;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 18px rgba(0,0,0,0.1);
    }
    .quick-card .card {
        transition: all 0.3s ease-in-out;
        border-radius: 15px;
    }
    .quick-card:hover .card {
        transform: translateY(-5px);
        background: #f8f9fa;
    }
</style>