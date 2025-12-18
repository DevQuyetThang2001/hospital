@extends('admin.layouts.app')


@extends('admin.layouts.app')

@section('content')
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <h4 class="card-title text-uppercase">Sửa loại bệnh</h4>
                    @session('success')
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endsession
                    @session('info')
                        <div class="alert alert-success">
                            {{ session('info') }}
                        </div>
                    @endsession
                    <form action="{{ route('admin.diseases.update', $disease->id) }}" method="POST">
                        @csrf

                        <!-- Khoa viện -->
                        <div class="form-group">
                            <label>Khoa viện</label>
                            <select name="department_id" class="form-control">
                                @foreach ($departments as $item)
                                    <option value="{{ $item->id }}"
                                        {{ $disease->department_id == $item->id ? 'selected' : '' }}>
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
                            <input type="text" name="name" class="form-control" value="{{ $disease->name }}">
                            @error('name')
                                <div class="text-danger text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Mô tả</label>
                            <input type="text" name="description" class="form-control" value="{{ $disease->description }}">
                            @error('description')
                                <div class="text-danger text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <button type="submit" class="btn btn-primary mr-2">Cập nhật</button>
                        <a href="{{ route('admin.diseases.list') }}" class="btn btn-secondary">Trở về</a>
                    </form>

                    <hr>
                </div>
            </div>
        </div>
    </div>
@endsection
