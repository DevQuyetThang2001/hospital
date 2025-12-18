@extends('manager.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 mx-auto grid-margin stretch-card">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title mb-4">
                        <i class="mdi mdi-hospital-building text-primary"></i>
                        Tạo phòng khám mới
                    </h4>

                    <form action="{{ route('manager.clinics.store') }}" method="POST">
                        @csrf

                        {{-- Chọn khoa --}}
                        <div class="form-group mb-3">
                            <label>Chọn khoa viện</label>
                            <select name="department_id" class="form-control">
                                <option value="">-- Chọn khoa viện --</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}"
                                        {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Tên phòng khám --}}
                        <div class="form-group mb-3">
                            <label>Tên phòng khám</label>
                            <input type="text" name="name" class="form-control" placeholder="VD: Phòng khám số 1"
                                value="{{ old('name') }}">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Trạng thái --}}
                        <div class="form-group mb-4">
                            <label>Trạng thái hoạt động</label>
                            <select name="status" class="form-control">
                                <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>
                                    Kích hoạt
                                </option>
                                <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>
                                    Vô hiệu hóa
                                </option>
                            </select>
                            @error('status')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        {{-- Tên phòng khám --}}
                        <div class="form-group mb-3">
                            <label>Số lượng bác sĩ</label>
                            <input type="number" name="quantity" class="form-control" placeholder="Số lượng"
                                value="{{ old('quantity') }}">
                            @error('quantity')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        {{-- Buttons --}}
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('manager.clinics.list') }}" class="btn btn-outline-secondary">
                                <i class="mdi mdi-arrow-left"></i> Trở về
                            </a>

                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-content-save"></i> Tạo phòng khám
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
