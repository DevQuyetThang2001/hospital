@extends('clients.layouts.app')


@section('content')
    @php
        $dayMap = [
            'Monday' => 'Thứ 2',
            'Tuesday' => 'Thứ 3',
            'Wednesday' => 'Thứ 4',
            'Thursday' => 'Thứ 5',
            'Friday' => 'Thứ 6',
        ];
    @endphp
    <div class="container py-5">
        <div class="row">
            <div class="col-md-4 text-center mb-4">
                <div class="card shadow-sm">
                    @if (!empty($doctor->user->image))
                        <img src="{{ asset('storage/' . $doctor->user->image) }}" class="card-img-top img-fluid"
                            style="height: 570px; object-fit: cover; object-position:center-top;" alt="Ảnh bác sĩ">
                    @else
                        <img src="https://via.placeholder.com/300x250?text=No+Image" class="card-img-top img-fluid"
                            alt="Ảnh bác sĩ">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $doctor->user->name }}</h5>
                        <p class="card-text">Chuyên khoa: {{ $doctor->department->name ?? 'Chưa cập nhật' }}</p>
                    </div>
                </div>

                @if (@session('success'))
                    <div class="alert alert-success mt-3">
                        {{ session('success') }}
                    </div>
                @endif
                @if (@session('error'))
                    <div class="alert alert-warning mt-3">
                        {{ session('error') }}
                    </div>
                @endif
            </div>

            <div class="col-md-8">
                <h3 class="mb-3">Lịch khám của bác sĩ</h3>
                <ul class="list-group mb-4">
                    @forelse ($schedules as $schedule)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>
                                {{ $dayMap[$schedule->day_of_week] ?? $schedule->day_of_week }}:
                                {{ $schedule->schedule->start_time }} - {{ $schedule->schedule->end_time }}
                            </span>
                        </li>
                    @empty
                        <li class="list-group-item">Chưa có lịch khám</li>
                    @endforelse
                </ul>

                <div class="card shadow-sm p-4">
                    <h4 class="mb-3">Đặt lịch khám với bác sĩ</h4>
                    <form action="{{ route('doctor.appointment.post', $doctor->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <input type="text" name="username" class="form-control"
                                placeholder="Họ tên bệnh nhân (VD: Nguyễn Văn A)" value="{{ old('username') }}">
                            @error('username')
                                <div class="text-danger text-sm">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="mb-3">
                            <input type="email" name="email" class="form-control" placeholder="Email liên hệ"
                                value="{{ old('email') }}">
                            @error('email')
                                <div class="text-danger text-sm">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <input type="text" name="phone" class="form-control" placeholder="Số điện thoại"
                                value="{{ old('phone') }}">
                            @error('phone')
                                <div class="text-danger text-sm">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="mb-3">
                            <input type="date" name="appointment_date" class="form-control"
                                value="{{ old('appointment_date') }}">
                            @error('appointment_date')
                                <div class="text-danger text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <select name="schedule_id" class="form-select">
                                <option value="" selected disabled>Chọn khung giờ</option>
                                @foreach ($schedules as $schedule)
                                    <option value="{{ $schedule->id }}">
                                        {{ $dayMap[$schedule->day_of_week] ?? $schedule->day_of_week }}:
                                        {{ $schedule->schedule->start_time }} - {{ $schedule->schedule->end_time }}
                                    </option>
                                @endforeach
                            </select>
                            @error('schedule_id')
                                <div class="text-danger text-sm">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="mb-3">
                            <input type="text" name="notes" class="form-control" placeholder="Ghi chú (nếu có)">
                        </div>

                        @if (Auth::check())
                            <button type="submit" class="btn btn-primary w-100">Đặt lịch khám</button>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary w-100">Đăng nhập để đặt lịch khám</a>
                        @endif
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
