@extends('receptionist.layouts.app')

@section('content')
    <div class="container py-5">
        <h2 class="mb-4">Chỉnh sửa thông tin nhập viện</h2>

        @if (session('info'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('receptionist.admission.update', $admission->id) }}" method="POST">
            @csrf

            {{-- Hiển thị bệnh nhân nhưng không cho sửa --}}
            <div class="mb-3">
                <label class="form-label">Bệnh nhân</label>
                <input type="text" class="form-control"
                    value="{{ $admission->patient->name ?? $admission->patient->user->name }}" disabled>
            </div>

            {{-- Sửa phòng --}}
            <div class="mb-3">
                <label class="form-label">Phòng</label>
                <select name="room_id" class="form-control" required>
                    @php
                        $typeVietnamese = [
                            'general' => 'Phòng thường',
                            'vip' => 'VIP',
                            'svip' => 'Siêu VIP',
                        ];
                    @endphp

                    @foreach ($rooms as $room)
                        <option value="{{ $room->id }}" {{ $admission->room_id == $room->id ? 'selected' : '' }}>
                            Phòng {{ $room->room_number }} ({{ $typeVietnamese[$room->type] ?? $room->type }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Sửa ngày nhập viện --}}
            <div class="mb-3">
                <label class="form-label">Ngày nhập viện</label>
                <input type="datetime-local" name="admission_date" class="form-control"
                    value="{{ date('Y-m-d\TH:i', strtotime($admission->admission_date)) }}" required>
            </div>

            {{-- Ghi chú --}}
            <div class="mb-3">
                <label class="form-label">Ghi chú</label>
                <textarea name="notes" class="form-control" rows="3">{{ $admission->notes }}</textarea>
            </div>

            <button class="btn btn-primary">Cập nhật</button>
            <a href="{{ route('receptionist.admissions.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection
