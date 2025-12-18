@extends('manager.layouts.app')
@section('content')
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <h4 class="card-title">Sửa lịch khám</h4>

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('info'))
                        <div class="alert alert-danger">
                            {{ session('info') }}
                        </div>
                    @endif

                    <form action="{{ route('manager.schedules.update', $schedule->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Bác sĩ --}}
                        <div class="mb-3">
                            <label class="form-label">Bác sĩ</label>
                            <select name="doctor_id" class="form-control" required>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}"
                                        {{ $doctor->id == $schedule->doctor_id ? 'selected' : '' }}>
                                        {{ $doctor->user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Phòng khám --}}
                        <div class="mb-3">
                            <label class="form-label">
                                Phòng khám
                                <small class="text-muted">(Tối đa 4 bác sĩ)</small>
                            </label>

                            <select name="clinic_id" class="form-control" required>
                                @foreach ($clinics as $clinic)
                                    <option value="{{ $clinic->id }}"
                                        {{ $schedule->clinic_id == $clinic->id ? 'selected' : '' }}
                                        {{ $clinic->doctor_count >= 4 && $schedule->clinic_id != $clinic->id ? 'disabled' : '' }}>
                                        {{ $clinic->name }}
                                        ({{ $clinic->doctor_count }}/4 bác sĩ)
                                    </option>
                                @endforeach
                            </select>

                            <small class="text-info">
                                Phòng đã đủ 4 bác sĩ sẽ không thể chọn thêm
                            </small>
                        </div>

                        {{-- Ngày trong tuần --}}
                        <div class="mb-3">
                            <label class="form-label">Ngày trong tuần</label>
                            <select name="day_of_week" class="form-control" required>
                                <option value="Monday" {{ $schedule->day_of_week == 'Monday' ? 'selected' : '' }}>Thứ 2
                                </option>
                                <option value="Tuesday" {{ $schedule->day_of_week == 'Tuesday' ? 'selected' : '' }}>Thứ 3
                                </option>
                                <option value="Wednesday" {{ $schedule->day_of_week == 'Wednesday' ? 'selected' : '' }}>Thứ
                                    4</option>
                                <option value="Thursday" {{ $schedule->day_of_week == 'Thursday' ? 'selected' : '' }}>Thứ 5
                                </option>
                                <option value="Friday" {{ $schedule->day_of_week == 'Friday' ? 'selected' : '' }}>Thứ 6
                                </option>
                            </select>
                        </div>

                        {{-- Khung giờ --}}
                        <div class="mb-3">
                            <label class="form-label">Khung giờ</label>
                            <select name="schedule_id" class="form-control" required>
                                @foreach ($schedules as $sch)
                                    <option value="{{ $sch->id }}"
                                        {{ $sch->id == $schedule->schedule_id ? 'selected' : '' }}>
                                        {{ $sch->start_time }} - {{ $sch->end_time }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Giới hạn bệnh nhân --}}
                        <div class="mb-3">
                            <label class="form-label">Giới hạn bệnh nhân/giờ</label>
                            <input type="number" name="limit_per_hour" class="form-control"
                                value="{{ $schedule->limit_per_hour }}" min="1" max="10" required>
                        </div>

                        {{-- Action --}}
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                        <a href="{{ route('manager.schedules.list') }}" class="btn btn-warning">Trở về</a>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
