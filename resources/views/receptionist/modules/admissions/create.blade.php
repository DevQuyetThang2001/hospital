@extends('receptionist.layouts.app')

@section('content')
    <div class="container-fluid py-5">
        <h2>Nhập viện bệnh nhân</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('receptionist.admission.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Chọn bệnh nhân</label>
                <select name="patient_id" class="form-control" required>
                    <option value="">-- Chọn bệnh nhân --</option>
                    @foreach ($patients as $patient)
                        <option value="{{ $patient->id }}">
                            {{ optional($patient->user)->name ?? $patient->name }}
                            ({{ optional($patient->user)->email ?? 'Không có email' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Chọn phòng</label>
                <select name="room_id" class="form-control" required>
                    <option value="">-- Chọn phòng --</option>
                    @foreach ($rooms as $room)
                        <option value="{{ $room->id }}">
                            🏥 P{{ $room->room_number }} |
                            {{ $room->type === 'general' ? 'Phòng thường' : strtoupper($room->type) }} |
                            🛏 {{ $room->capacity }} giường |
                            {{ $room->status === 'available' ? '🟢 Trống' : ($room->status === 'occupied' ? '🔴 Có người' : '🟡 Bảo trì') }}
                        </option>
                    @endforeach

                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Ngày nhập viện</label>
                <input type="datetime-local" name="admission_date" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Ngày xuất viện (nếu có)</label>
                <input type="datetime-local" name="discharge_date" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Ghi chú</label>
                <textarea name="notes" class="form-control" rows="3"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Nhập viện</button>
        </form>
    </div>
@endsection
