@extends('admin.layouts.app')


@section('content')
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <h4 class="card-title text-uppercase">Thêm bệnh mới</h4>

                    <form action="{{ route('admin.diseases.add') }}" method="POST">
                        @csrf

                        <!-- Khoa viện -->
                        <div class="form-group">
                            <label>Khoa viện</label>
                            <select name="department_id" class="form-control">
                                <option value="">Chọn khoa viện</option>
                                @foreach ($departments as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('department_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('department_id')
                                <div class="text-danger text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tên bệnh -->
                        <div class="form-group">
                            <label>Tên bệnh</label>
                            <input type="text" name="name" class="form-control"
                                placeholder="Ví dụ: Viêm họng, Đau đầu..." value="{{ old('name') }}">

                            @error('name')
                                <div class="text-danger text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                         <!-- Tên bệnh -->
                        <div class="form-group">
                            <label>Mô tả</label>
                            <input type="text" name="description" class="form-control"
                                placeholder="Mô tả triệu chứng của bệnh" value="{{ old('description') }}">

                            @error('description')
                                <div class="text-danger text-sm">{{ $message }}</div>
                            @enderror
                        </div>


                        <!-- Buttons -->
                        <button type="submit" class="btn btn-primary mr-2">Thêm</button>
                        <a href="{{ route('admin.diseases.list') }}" class="btn btn-secondary">Trở về</a>

                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
