@extends('receptionist.layouts.app')

@section('content')
    <div class="container-fluid">
        <h2 class="mb-4">Lịch khám bác sĩ</h2>

        <form method="GET" class="row mb-4">

            <div class="col-md-4">
                <label>Khoa</label>
                <select name="department_id" class="form-control" onchange="this.form.submit()">
                    <option value="">-- Tất cả khoa --</option>
                    @foreach ($departments as $dept)
                        <option value="{{ $dept->id }}" {{ $departmentId == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label>Thứ</label>
                <select name="day" class="form-control" onchange="this.form.submit()">
                    <option value="">-- Tất cả các ngày --</option>
                    @php
                        $dayMap = [
                            'Monday' => 'Thứ 2',
                            'Tuesday' => 'Thứ 3',
                            'Wednesday' => 'Thứ 4',
                            'Thursday' => 'Thứ 5',
                            'Friday' => 'Thứ 6',
                            'Saturday' => 'Thứ 7',
                            'Sunday' => 'Chủ nhật',
                        ];
                    @endphp

                    @foreach ($daysAvailable as $d)
                        <option value="{{ $d->day_of_week }}" {{ $dayFilter == $d->day_of_week ? 'selected' : '' }}>
                            {{ $dayMap[$d->day_of_week] }}
                        </option>
                    @endforeach
                </select>
            </div>

        </form>

        @foreach ($groupedSchedules as $doctorId => $schedules)
            @php
                $doctor = $schedules->first()->doctor;
            @endphp

            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Bác sĩ {{ $doctor->user->name ?? 'Không có tên' }} -
                        {{ $doctor->department->name ?? 'Chưa có khoa' }}</h5>

                    @if ($schedules->isEmpty())
                        <p>Chưa có lịch khám</p>
                    @else
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Thứ</th>
                                    <th>Giờ bắt đầu</th>
                                    <th>Giờ kết thúc</th>
                                    <th>Chi tiết</th>
                                    <th>Phòng khám</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($schedules as $schedule)
                                    <tr>
                                        <td>{{ $schedule->day_vn }}</td>
                                        <td>{{ $schedule->schedule->start_time }}</td>
                                        <td>{{ $schedule->schedule->end_time }}</td>
                                        <td>{{ $schedule->clinic->name }}</td>
                                        <td>
                                            <a href="{{ route('receptionist.appointment.detail', $doctor->id) }}"
                                                class="btn btn-sm btn-primary">
                                                Xem chi tiết
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endsection
