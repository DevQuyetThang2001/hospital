@extends('manager.layouts.app')
@section('content')
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Tạo lịch khám</h4>
                    <form class="forms-sample" action="{{route('manager.schedules.store')}}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="exampleSelectGender">Chọn bác sĩ</label>
                            <select name="doctor_id" class="form-control" required>
                                <option value="">-- Chọn bác sĩ --</option>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">{{ $doctor->user->name }}</option>
                                @endforeach
                            </select>

                        </div>
                        <div class="form-group">
                            <label for="exampleSelectGender">Giờ khám</label>
                            <select name="schedule_id" class="form-control" id="exampleSelectGender">
                                <option value="">Chọn giờ khám</option>
                                @foreach ($schudules as $item)
                                    <option value="{{$item->id}}">
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
                                value="{{ old('limit_per_hour', 10) }}" min="1" max="50" required>
                            @error('limit_per_hour')
                                <div class="text-danger text-sm">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleSelectGender">Ngày khám</label>
                            <select name="day_of_week" class="form-control" id="exampleSelectGender">
                                <option value="">Chọn ngày khám</option>
                                <option value="Monday" {{ old('day_of_week') == 'Monday' ? 'selected' : '' }}>Thứ 2</option>
                                <option value="Tuesday" {{ old('day_of_week') == 'Tuesday' ? 'selected' : '' }}>Thứ 3</option>
                                <option value="Wednesday" {{ old('day_of_week') == 'Wednesday' ? 'selected' : '' }}>Thứ 4
                                </option>
                                <option value="Thursday" {{ old('day_of_week') == 'Thursday' ? 'selected' : '' }}>Thứ 5
                                </option>
                                <option value="Friday" {{ old('day_of_week') == 'Friday' ? 'selected' : '' }}>Thứ 6</option>

                            </select>

                            @error('day_of_week')
                                <div class="text-danger text-sm">{{$message}}</div>
                            @enderror
                        </div>



                        <button type="submit" class="btn btn-primary mr-2">Tạo lịch khám</button>
                        <a href="{{route('manager.schedules.list')}}" class="btn btn-warning mr-2">Trở về</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection