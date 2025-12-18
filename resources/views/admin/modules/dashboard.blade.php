@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid py-4">

        {{-- ===================== TOP STATS ===================== --}}
        <div class="row">

            {{-- DOCTORS --}}
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Tổng bác sĩ</h6>
                            <h2 class="fw-bold">{{ $totalDoctors }}</h2>
                        </div>
                        <i class="fas fa-user-md fa-3x text-primary"></i>
                    </div>
                </div>
            </div>

            {{-- PATIENTS --}}
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Tổng bệnh nhân</h6>
                            <h2 class="fw-bold">{{ $totalPatients }}</h2>
                        </div>
                        <i class="fas fa-users fa-3x text-success"></i>
                    </div>
                </div>
            </div>

            {{-- TODAY APPOINTMENTS --}}
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Lịch hẹn hôm nay</h6>
                            <h2 class="fw-bold">{{ $todayAppointments }}</h2>
                        </div>
                        <i class="fas fa-calendar-check fa-3x text-danger"></i>
                    </div>
                </div>
            </div>

        </div>


        <div class="row mt-4">

            {{-- Bác sĩ được đặt nhiều nhất --}}
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm p-3 border-0 rounded-3" style="background: #eef7ff">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-user-md fa-2x text-primary"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-1">Bác sĩ được đặt nhiều nhất</p>
                            @if ($topDoctor)
                                <h6 class="fw-bold mb-0">
                                    {{ $topDoctor->doctor->user->name }}
                                </h6>
                                <small class="text-primary">{{ $topDoctor->total }} lượt đặt</small>
                            @else
                                <small class="text-muted">Chưa có dữ liệu</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Khoa được đặt khám nhiều nhất --}}
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm p-3 border-0 rounded-3" style="background: #e8fff3">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-hospital fa-2x text-success"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-1">Khoa khám nhiều nhất</p>
                            @if ($topDepartmentName)
                                <h6 class="fw-bold mb-0">
                                    {{ $topDepartmentName }}
                                </h6>
                                <small class="text-success">{{ $topDepartment->total }} lượt đặt</small>
                            @else
                                <small class="text-muted">Chưa có dữ liệu</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Loại bệnh được chọn nhiều nhất --}}
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm p-3 border-0 rounded-3" style="background: #fff4ee">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-virus fa-2x text-danger"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-1">Loại bệnh được chọn nhiều nhất</p>
                            @if ($topDisease)
                                <h6 class="fw-bold mb-0">
                                    {{ $topDisease->name }}
                                </h6>
                                <small class="text-danger">{{ $topDepartment->total }} lượt khám</small>
                            @else
                                <small class="text-muted">Chưa có dữ liệu</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
        {{-- ===================== CHARTS ===================== --}}
        <div class="row">

            {{-- APPOINTMENT STATUS CHART --}}
            <div class="col-md-12">
                <div class="card shadow-sm p-3 mb-4">
                    <h5 class="mb-3 fw-bold">📊 Tình trạng lịch hẹn</h5>
                    <div id="appointmentChart"></div>
                </div>
            </div>

            {{-- ROOM STATUS CHART --}}
            <div class="col-md-12">
                <div class="card shadow-sm p-3 mb-4">
                    <h5 class="mb-3 fw-bold">🏥 Trạng thái phòng bệnh</h5>
                    <div id="roomChart"></div>
                </div>
            </div>

            {{-- WEEKLY APPOINTMENT CHART --}}
            <div class="col-md-12">
                <div class="card shadow-sm p-3 mb-4">
                    <h5 class="mb-3 fw-bold">📅 Lịch hẹn theo thứ (tuần này)</h5>
                    <div id="weeklyChart"></div>
                </div>
            </div>

        </div>

    </div>
@endsection


{{-- ===================== CHART SCRIPTS ===================== --}}
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        // ================= CHART 1: Appointment Status =================
        var appointmentOptions = {
            chart: {
                type: 'bar',
                height: 330
            },
            series: [{
                name: 'Số lượng',
                data: [{{ $pending }}, {{ $confirmed }}, {{ $cancelled }}, {{ $completed }}]
            }],
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '45%'
                }
            },
            dataLabels: {
                enabled: true
            },
            xaxis: {
                categories: ['Chờ xác nhận', 'Đã xác nhận', 'Đã hủy', 'Đã khám xong'],
                labels: {
                    style: {
                        fontSize: '13px'
                    }
                }
            },
            colors: ['#f1c40f', '#2ecc71', '#e74c3c', '#3498db']
        };
        new ApexCharts(document.querySelector("#appointmentChart"), appointmentOptions).render();


        // ================= CHART 2: Room Status =================
        var roomOptions = {
            chart: {
                type: 'donut',
                height: 330
            },
            series: [{{ $roomsAvailable }}, {{ $roomsOccupied }}, {{ $roomsMaintenance }}],
            labels: ['Phòng trống', 'Đang sử dụng', 'Bảo trì'],
            dataLabels: {
                enabled: true
            },
            colors: ['#2ecc71', '#e74c3c', '#f1c40f'],
            legend: {
                position: 'bottom'
            }
        };
        new ApexCharts(document.querySelector("#roomChart"), roomOptions).render();


        // ================= CHART 3: Weekly Appointments =================
        var weeklyOptions = {
            chart: {
                type: 'line',
                height: 330
            },
            series: [{
                name: 'Lịch hẹn',
                data: [
                    {{ $appointmentsPerDay['Thứ 2'] }},
                    {{ $appointmentsPerDay['Thứ 3'] }},
                    {{ $appointmentsPerDay['Thứ 4'] }},
                    {{ $appointmentsPerDay['Thứ 5'] }},
                    {{ $appointmentsPerDay['Thứ 6'] }},
                    {{ $appointmentsPerDay['Thứ 7'] }},
                    {{ $appointmentsPerDay['Chủ nhật'] }}
                ]
            }],
            xaxis: {
                categories: ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'Chủ nhật'],
                labels: {
                    style: {
                        fontSize: '13px'
                    }
                }
            },
            stroke: {
                width: 3,
                curve: 'smooth'
            },
            markers: {
                size: 5
            },
            colors: ['#8e44ad']
        };
        new ApexCharts(document.querySelector("#weeklyChart"), weeklyOptions).render();
    </script>
@endsection
