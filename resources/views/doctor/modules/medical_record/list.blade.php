@extends('doctor.layouts.app')

@section('content')
    <div class="container-fluid py-5">
        <div class="card shadow border-0 rounded-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Danh sách hồ sơ bệnh án</h4>
                <a href="{{ route('doctor.patient.medicalRecord.add') }}" class="btn btn-success btn-sm">Thêm mới hồ sơ</a>
            </div>
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif


            <div class="card-body">
                @if ($records->isEmpty())
                    <p class="text-center text-muted">Hiện chưa có hồ sơ bệnh án nào.</p>
                @else
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Bệnh nhân</th>
                                <th>Chẩn đoán</th>
                                <th>Điều trị</th>
                                <th>Ngày tạo</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($records as $index => $record)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $record->patient->name ?? 'Không xác định' }}</td>
                                    <td>{{ Str::limit($record->diagnosis, 40) }}</td>
                                    <td>{{ Str::limit($record->treatment, 40) }}</td>
                                    <td>{{ $record->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('doctor.patient.medicalRecord.show', $record->id) }}"
                                            class="btn btn-sm btn-info">
                                            Xem chi tiết
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $records->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
