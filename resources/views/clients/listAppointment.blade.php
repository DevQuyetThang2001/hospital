@extends('clients.layouts.app')

@section('content')

    <!-- Header -->
    <div class="container-fluid bg-primary py-5 mb-5">
        <div class="container py-5 text-center">
            <h1 class="display-3 text-white mb-3">Lịch Khám Của Tôi</h1>
            <p class="text-white-50">Quản lý các lịch hẹn và theo dõi trạng thái khám của bạn</p>
        </div>
    </div>

    <div class="container py-5">

        {{-- Thông báo --}}
        @if (isset($error) && $error)
            <div class="d-flex justify-content-center mb-4">
                <div class="alert alert-danger text-center w-75 shadow-sm">
                    {{ $error }}
                    <br>
                    <a href="{{ route('login') }}" class="font-bold text-decoration-underline">Vui lòng đăng nhập</a>
                </div>
            </div>
        @elseif(isset($success) && $success)
            <div class="d-flex justify-content-center mb-4">
                <div class="alert alert-info text-center w-75 shadow-sm">
                    {{ $success }}
                    <br>
                    Hãy đặt lịch khám ngay hôm nay!
                </div>
            </div>
        @endif

        @if (!$appointments->isEmpty())
            <div class="row g-4">
                @foreach ($appointments as $item)
                    <div class="col-lg-6">
                        <div class="bg-light rounded p-4 shadow-sm h-100">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-warning text-dark',
                                    'confirmed' => 'bg-success text-white',
                                    'cancelled' => 'bg-danger text-white',
                                    'completed' => 'bg-primary text-white',
                                ];

                                $statusLabels = [
                                    'pending' => 'Chờ xác nhận',
                                    'confirmed' => 'Đã xác nhận',
                                    'cancelled' => 'Đã từ chối',
                                    'completed' => 'Hoàn thành',
                                ];
                            @endphp

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0 fw-bold">
                                    👨‍⚕️ Bác sĩ: {{ $item->doctor->user->name ?? 'N/A' }}
                                </h5>

                                <span
                                    class="px-3 py-1 rounded-pill {{ $statusColors[$item->status] ?? 'bg-secondary text-white' }}">
                                    {{ $statusLabels[$item->status] ?? 'Không rõ' }}
                                </span>
                            </div>

                            <div class="mb-2 text-muted">
                                <p class="mb-1"><strong>Khoa:</strong>
                                    {{ $item->doctor->department->name ?? 'Chưa cập nhật' }}</p>
                                <p class="mb-1"><strong>Chuyên ngành:</strong>
                                    {{ $item->doctor->specialization ?? 'Chưa cập nhật' }}</p>
                                <p class="mb-1"><strong>Email:</strong> {{ $item->doctor->user->email ?? 'Không có' }}</p>
                                <p class="mb-3"><strong>SĐT:</strong> {{ $item->doctor->user->phone ?? 'Không có' }}</p>
                            </div>

                            <hr class="my-2">

                            <p class="mb-1"><strong>Ngày khám:</strong>
                                {{ \Carbon\Carbon::parse($item->appointment_date)->format('d/m/Y') }}
                                ({{ $item->day_vn ?? '-' }})
                            </p>
                            <p class="mb-1"><strong>Giờ khám:</strong> {{ $item->start_time ?? '-' }} -
                                {{ $item->end_time ?? '-' }}</p>

                            <div class="text-end mt-3">
                                <a href="{{ route('client.appointment.detail',$item->id) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-info-circle me-1"></i> Chi tiết
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="d-flex justify-content-center">
                <div class="bg-light rounded p-5 shadow-sm text-center w-75">
                    <h5 class="mb-3">Bạn chưa có lịch khám nào.</h5>
                    <a href="" class="btn btn-primary">Đặt lịch khám ngay</a>
                </div>
            </div>
        @endif

    </div>

@endsection
