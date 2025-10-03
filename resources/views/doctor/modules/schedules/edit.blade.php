@extends('doctor.layouts.app')
@section('content')
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Sửa lịch khám</h4>

                    @session('success')
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endsession

                    @if (@session('info'))
                        <div class="alert alert-danger">
                            {{ session('info') }}
                        </div>
                    @endif
                    <form class="forms-sample" action="{{route('doctor.schedules.update', $schedule->id)}}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="exampleSelectGender">Ngày khám</label>
                            <select name="day_of_week" class="form-control" id="exampleSelectGender">
                                <option value="">Chọn ngày khám</option>
                                <option value="Monday" {{ $schedule->day_of_week == 'Monday' ? 'selected' : '' }}>Thứ 2
                                </option>
                                <option value="Tuesday" {{ $schedule->day_of_week == 'Tuesday' ? 'selected' : '' }}>Thứ 3
                                </option>
                                <option value="Wednesday" {{ $schedule->day_of_week == 'Wednesday' ? 'selected' : '' }}>Thứ 4
                                </option>
                                <option value="Thursday" {{ $schedule->day_of_week == 'Thursday' ? 'selected' : '' }}>Thứ 5
                                </option>
                                <option value="Friday" {{ $schedule->day_of_week == 'Friday' ? 'selected' : '' }}>Thứ 6
                                </option>
                            </select>
                            @error('schedule_id')
                                <div class="text-danger text-sm">{{$message}}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleSelectGender">Giờ khám</label>
                            <select name="schedule_id" class="form-control" id="exampleSelectGender">
                                <option value="">Chọn giờ khám</option>
                                @foreach ($schedules as $item)
                                    <option {{$item->id === $schedule->schedule_id ? 'selected' : ''}} value="{{$item->id}}">
                                        {{$item->start_time}} - {{$item->end_time}}
                                    </option>
                                @endforeach
                            </select>
                            @error('schedule_id')
                                <div class="text-danger text-sm">{{$message}}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="limit_per_hour">Giới hạn số bệnh nhân/giờ</label>
                            <input type="number" name="limit_per_hour" id="limit_per_hour" class="form-control"
                                value="{{ old('limit_per_hour', $schedule->limit_per_hour) }}" min="1" max="50" required>
                            @error('limit_per_hour')
                                <div class="text-danger text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-secondary mr-2">Cập nhật</button>
                        <a href="{{route('doctor.schedules.list')}}" type="submit" class="btn btn-primary mr-2">Quay lại</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection