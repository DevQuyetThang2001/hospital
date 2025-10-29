@extends('manager.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <h2 class="mb-4 text-center fw-bold text-primary">📊 Trang Tổng Quan Quản Lý Bệnh Viện</h2>

        {{-- Thống kê nhanh --}}
        <div class="row g-3 text-center mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-light">
                    <div class="card-body">
                        <h6 class="fw-bold text-secondary">Bác sĩ</h6>
                        <h3 class="text-primary">{{ $totalDoctors }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-light">
                    <div class="card-body">
                        <h6 class="fw-bold text-secondary">Bệnh nhân</h6>
                        <h3 class="text-success">{{ $totalPatients }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-light">
                    <div class="card-body">
                        <h6 class="fw-bold text-secondary">Lịch khám hôm nay</h6>
                        <h3 class="text-warning">{{ $todayAppointments }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-light">
                    <div class="card-body">
                        <h6 class="fw-bold text-secondary">Đánh giá</h6>
                        <h3 class="text-danger">{{ $totalFeedbacks }}</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- Đánh giá trung bình --}}
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-body text-center">
                <h6 class="fw-bold text-secondary">⭐ Đánh giá trung bình của bệnh viện</h6>
                <h3 class="text-info">{{ number_format($averageRating, 1) }}/5</h3>
            </div>
        </div>

        {{-- Biểu đồ lượt khám theo tháng --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h5 class="text-center mb-3 text-primary fw-bold">📈 Số lượt khám theo tháng</h5>
                <canvas id="appointmentsChart" height="120"></canvas>
            </div>
        </div>

    </div>

    {{-- Thư viện Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('appointmentsChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode(range(1, 12)) !!},
                datasets: [{
                    label: 'Số lượt khám',
                    data: {!! json_encode(array_values($appointmentsByMonth->toArray())) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: '#36A2EB',
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    },
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Tháng'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Số lượt khám'
                        }
                    }
                }
            }
        });
    </script>
@endsection
