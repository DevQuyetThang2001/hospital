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
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row gx-5">
                <div class="col-lg-12 mb-5 mb-lg-0">
                    <div class="mb-4">
                        <h5 class="d-inline-block text-primary text-uppercase border-bottom border-5">Đặt lịch khám</h5>
                        <h1 class="display-4">Đặt lịch khám cho gia đình của bạn</h1>
                    </div>
                    <p class="mb-3">
                        Hãy lựa chọn bác sĩ phù hợp và đặt lịch khám trực tiếp cho gia đình của bạn.
                        Chúng tôi luôn sẵn sàng phục vụ.
                    </p>
                </div>
            </div>

            <form method="GET" class="row g-3 mb-4" action="{{ route('doctor.appointment.filter') }}">
                <div class="col-md-3">
                    <label for="filter_department" class="form-label">Lọc theo khoa</label>
                    <select name="department" id="filter_department" class="form-select">
                        <option value="">--Tất cả khoa--</option>
                        @foreach ($departments as $dep)
                            <option value="{{ $dep->id }}"
                                {{ request('department') == $dep->id ? 'selected' : '' }}>
                                {{ $dep->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('department')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label for="filter_day" class="form-label">Lọc theo ngày</label>
                    <select name="day_of_week" id="filter_day" class="form-select">
                        <option value="">--Tất cả ngày khám--</option>
                        <option value="Monday">Thứ 2</option>
                        <option value="Tuesday">Thứ 3</option>
                        <option value="Wednesday">Thứ 4</option>
                        <option value="Thursday">Thứ 5</option>
                        <option value="Friday">Thứ 6</option>
                    </select>
                    @error('day_of_week')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label for="filter_time" class="form-label">Lọc theo giờ khám</label>
                    <select name="schedule_id" id="filter_time" class="form-select">
                        <option value="">--Tất cả ngày khám--</option>
                        @foreach ($schedulesAll as $item)
                            <option value="{{ $item->id }}"
                                {{ request('schedule_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->start_time }} - {{ $item->end_time }}
                            </option>
                        @endforeach
                    </select>
                    @error('schedule_id')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-2 align-self-end">
                    <button type="submit" class="btn btn-outline-primary w-100">Lọc</button>
                </div>
            </form>

            <div class="row">
                @if ($groupedSchedules->isEmpty())
                    <div class="col-12">
                        <div class="alert alert-info text-center" role="alert">
                            Không có lịch khám nào phù hợp với tiêu chí lọc của bạn.
                        </div>
                    </div>
                @endif
                @foreach ($groupedSchedules as $doctorId => $doctorSchedules)
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm h-100">
                            @if (!empty($doctorSchedules->first()->doctor->user->name))
                                <div class="d-flex justify-content-center align-items-center"
                                    style="height: 220px; overflow: hidden;">
                                    <img src="{{ asset('storage/' . $doctorSchedules->first()->doctor->user->image) }}"
                                        class="img-fluid" style="object-fit: contain; max-height: 100%;" alt="Ảnh bác sĩ">
                                </div>
                            @else
                                <div class="d-flex justify-content-center align-items-center bg-light"
                                    style="height: 220px; overflow: hidden;">
                                    <img src="https://via.placeholder.com/220x220?text=No+Image" class="img-fluid"
                                        style="object-fit: contain; max-height: 100%;" alt="Ảnh bác sĩ">
                                </div>
                            @endif

                            <div class="card-body text-center">
                                <h5 class="card-title">{{ $doctorSchedules->first()->doctor->user->name }}</h5>
                                <p class="card-text text-muted">Chuyên khoa:
                                    {{ $doctorSchedules->first()->doctor->department->name ?? 'Chưa cập nhật' }}
                                </p>
                                <h6 class="mt-3">Lịch khám:</h6>
                                <ul class="list-group list-group-flush">


                                    @foreach ($doctorSchedules as $schedule)
                                        <li class="list-group-item">
                                            <span class="text-primary">
                                                {{ $dayMap[$schedule->day_of_week] ?? $schedule->day_of_week }}:
                                            </span>
                                            {{ $schedule->schedule->start_time }} -
                                            {{ $schedule->schedule->end_time }}
                                        </li>
                                    @endforeach
                                </ul>
                                <a href="{{route('doctor.appointment', $doctorId)}}" class="btn btn-primary mt-3 w-100">Đặt lịch
                                    khám</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Appointment End -->


    {{-- <div class="col-lg-6">
        <div class="bg-light text-center rounded p-5">
            <h1 class="mb-4">Đặt lịch khám ngay bây giờ</h1>
            <form>
                <div class="row g-3">
                    <div class="col-12 col-sm-6">
                        @if (!empty($departments))
                        <select class="form-select bg-white border-0" style="height: 55px;">
                            <option selected>Chọn khoa viện</option>
                            @foreach ($departments as $department)
                            <option>{{ $department->name }}</option>
                            @endforeach
                        </select>
                        @endif
                    </div>
                    <div class="col-12 col-sm-6">
                        <select class="form-select bg-white border-0" style="height: 55px;">
                            <option selected>Select Doctor</option>
                            <option value="1">Doctor 1</option>
                            <option value="2">Doctor 2</option>
                            <option value="3">Doctor 3</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-6">
                        <input type="text" class="form-control bg-white border-0" placeholder="Your Name"
                            style="height: 55px;">
                    </div>
                    <div class="col-12 col-sm-6">
                        <input type="email" class="form-control bg-white border-0" placeholder="Your Email"
                            style="height: 55px;">
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="date" id="date" data-target-input="nearest">
                            <input type="text" class="form-control bg-white border-0 datetimepicker-input"
                                placeholder="Date" data-target="#date" data-toggle="datetimepicker" style="height: 55px;">
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="time" id="time" data-target-input="nearest">
                            <input type="text" class="form-control bg-white border-0 datetimepicker-input"
                                placeholder="Time" data-target="#time" data-toggle="datetimepicker" style="height: 55px;">
                        </div>
                    </div>
                    <div class="col-12">
                        @if (Auth::check())
                        <button class="btn btn-primary w-100 py-3" type="submit">Đặt lịch khám</button>

                        @else
                        <a href="{{ route('login') }}" class="btn btn-primary w-100 py-3">Đăng nhập để đặt lịch khám</a>
                        @endif

                    </div>
                </div>
            </form>
        </div>
    </div> --}}

@endsection