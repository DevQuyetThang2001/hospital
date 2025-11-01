@extends('doctor.layouts.app')

@section('content')
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Xác nhận bệnh nhân</h4>

                    {{-- Thông báo thành công hoặc lỗi --}}
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                  

                    {{-- Form xác nhận bệnh nhân --}}
                    <form class="forms-sample" action="{{ route('doctor.patients.store') }}" method="POST">
                        @csrf

                        {{-- Họ tên bệnh nhân --}}
                        <div class="form-group">
                            <label for="name">Họ và tên bệnh nhân</label>
                            <input type="text" name="name" id="name" class="form-control"
                                placeholder="Nhập họ và tên bệnh nhân..." value="{{ old('name') }}">
                            @error('name')
                                <div class="text-danger text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Giới tính --}}
                        <div class="form-group">
                            <label for="gender">Giới tính</label>
                            <select name="gender" id="gender" class="form-control">
                                <option value="">-- Chọn giới tính --</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Nam</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Nữ</option>
                            </select>
                            @error('gender')
                                <div class="text-danger text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Ngày sinh --}}
                        <div class="form-group">
                            <label for="date_of_birth">Ngày sinh</label>
                            <input type="date" name="date_of_birth" id="date_of_birth" class="form-control"
                                value="{{ old('date_of_birth') }}">
                            @error('date_of_birth')
                                <div class="text-danger text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Số điện thoại --}}
                        <div class="form-group">
                            <label for="phone">Số điện thoại</label>
                            <input type="text" name="phone" id="phone" class="form-control"
                                placeholder="Nhập số điện thoại..." value="{{ old('phone') }}">
                            @error('phone')
                                <div class="text-danger text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Địa chỉ --}}
                        <div class="form-group">
                            <label for="address">Địa chỉ</label>
                            <input type="text" name="address" id="address" class="form-control"
                                placeholder="Nhập địa chỉ bệnh nhân..." value="{{ old('address') }}">
                            @error('address')
                                <div class="text-danger text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Khoa viện --}}
                        <div class="form-group">
                            <label for="department_id">Khoa viện</label>
                            <select name="department_id" id="department_id" class="form-control">
                                <option value="">-- Chọn khoa viện --</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}"
                                        {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <div class="text-danger text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tài khoản người dùng (nếu có) --}}
                        <div class="form-group">
                            <label for="user_id">Tài khoản (nếu bệnh nhân có tài khoản)</label>
                            <select name="user_id" id="user_id" class="form-control">
                                <option value="">-- Không có tài khoản --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} - {{ $user->email }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="text-danger text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nút thao tác --}}
                        <button type="submit" class="btn btn-primary mr-2">Xác nhận bệnh nhân</button>
                        <a href="{{ route('doctor.index') }}" class="btn btn-warning">Trở về</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
