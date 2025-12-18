@extends('manager.layouts.app')



@section('content')

    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Sửa phòng khám</h4>

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
                    <form action="{{ route('manager.clinics.update', $clinic->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Bác sĩ</label>
                            <select name="doctor_id" class="form-control" disabled>
                                @foreach ($departments as $item)
                                    <option value="{{ $item->id }}"
                                        {{ $item->id == $clinic->department_id ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tên phòng khám</label>
                            <input type="text" name="name" class="form-control" value="{{ $clinic->name }}" >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Trạng thái</label>
                            <select name="status" class="form-control" required>
                                <option value="1" {{ $clinic->status == 1 ? 'selected' : '' }}>Hoạt động</option>
                                <option value="0" {{ $clinic->status == 0 ? 'selected' : '' }}>Không hoạt động</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Số lượng bác sĩ</label>
                            <input type="text" name="quantity" class="form-control" value="{{ $clinic->quantity }}"
                                required>
                        </div>



                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                        <a href="{{ route('manager.clinics.list') }}" class="btn btn-warning mr-2">Trở về</a>
                    </form>

                </div>
            </div>
        </div>
    </div>




@endsection
