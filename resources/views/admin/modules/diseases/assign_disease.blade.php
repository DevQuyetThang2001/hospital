@extends('admin.layouts.app')

@section('content')
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <h4 class="card-title">Gán loại bệnh cho bác sĩ: {{ $doctor->user->name }}</h4>

                    {{-- Các bệnh đã được gán --}}
                    <div class="mb-4">
                        <h5>Các loại bệnh đã được gán:</h5>

                        @if ($doctor->diseases->count() > 0)
                            <ul class="list-group">
                                @foreach ($doctor->diseases as $d)
                                    <li class="list-group-item py-2">
                                        {{ $d->name }}
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted">Chưa gán loại bệnh nào.</p>
                        @endif
                    </div>

                    <hr>

                    <form action="{{ route('admin.doctors.assignDisease', $doctor->id) }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label>Chọn loại bệnh để gán thêm</label>

                            <div class="mt-2">
                                @forelse ($diseases as $d)
                                    <div class="form-check mb-2">
                                        <input type="checkbox" class="form-check-input" name="diseases[]"
                                            value="{{ $d->id }}"
                                            {{ in_array($d->id, $doctor->diseases->pluck('id')->toArray()) ? 'checked' : '' }}>
                                        <label class="form-check-label">{{ $d->name }}</label>
                                    </div>
                                @empty
                                    <p class="text-muted">Khoa này không có loại bệnh nào.</p>
                                @endforelse
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                        <a href="{{ route('admin.doctors.list') }}" class="btn btn-secondary">Trở về</a>

                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
