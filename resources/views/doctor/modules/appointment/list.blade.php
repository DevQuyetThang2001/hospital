@extends('doctor.layouts.app')

@section('content')
    <div class="container-fluid px-4">
        <div class="card shadow border-0 mt-4">
            <div class="card-header bg-primary text-white text-center py-3 rounded-top">
                <h4 class="mb-0 text-uppercase fw-bold">
                    <i class="fas fa-calendar-check me-2"></i>Danh sách lịch hẹn của tôi
                </h4>
            </div>

            <div class="card-body bg-light">
                {{-- Thông báo --}}
                @foreach (['success', 'update', 'info', 'delete'] as $msg)
                    @if (session($msg))
                        <div class="alert alert-{{ $msg === 'delete' ? 'danger' : ($msg === 'update' ? 'secondary' : $msg) }} alert-dismissible fade show"
                            role="alert">
                            {{ session($msg) }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
                        </div>
                    @endif
                @endforeach

                {{-- Bảng danh sách --}}
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-center bg-white rounded shadow-sm">
                        <thead class="table-primary text-dark">
                            <tr>
                                <th>#</th>
                                <th>Bệnh nhân</th>
                                <th>Người đặt lịch</th>
                                <th>Email</th>
                                <th>Điện thoại</th>
                                <th>Ngày hẹn</th>
                                <th>Thứ</th>
                                <th>Giờ khám</th>
                                <th>Trạng thái</th>
                                <th>Ghi chú</th>
                                <th>Ngày tạo</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($appointments as $key => $item)
                                <tr class="align-middle">
                                    <td class="fw-semibold">{{ $key + 1 }}</td>
                                    <td class="fw-semibold text-primary">
                                        <i class="fas fa-user me-1"></i>
                                        {{ $item->username ?? ($item->patient->user->name ?? '-') }}
                                    </td>
                                    <td>{{ $item->bookedBy->name ?? 'Chính bệnh nhân' }}</td>
                                    <td>{{ $item->email ?? ($item->patient->user->email ?? '-') }}</td>
                                    <td>{{ $item->phone ?? ($item->patient->phone ?? '-') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->appointment_date)->format('d/m/Y') }}</td>
                                    <td>{{ $item->day_vn ?? '-' }}</td>
                                    <td>
                                        <span class="fw-bold text-success">
                                            {{ $item->schedule->schedule->start_time ?? '-' }}
                                        </span>
                                        -
                                        <span class="fw-bold text-danger">
                                            {{ $item->schedule->schedule->end_time ?? '-' }}
                                        </span>
                                    </td>

                                    {{-- Trạng thái --}}
                                    <td>
                                        @switch($item->status)
                                            @case('pending')
                                                <span class="badge bg-warning text-dark px-3 py-2"><i
                                                        class="fas fa-hourglass-half me-1"></i> Chờ xác nhận</span>
                                            @break

                                            @case('confirmed')
                                                <span class="badge bg-success px-3 py-2"><i class="fas fa-check-circle me-1"></i> Đã
                                                    xác nhận</span>
                                            @break

                                            @case('cancelled')
                                                <span class="badge bg-danger px-3 py-2"><i class="fas fa-times-circle me-1"></i> Đã
                                                    hủy</span>
                                            @break

                                            @case('completed')
                                                <span class="badge bg-primary px-3 py-2"><i class="fas fa-check-double me-1"></i>
                                                    Hoàn thành</span>
                                            @break

                                            @default
                                                <span class="badge bg-secondary px-3 py-2">Không rõ</span>
                                        @endswitch
                                    </td>

                                    {{-- Ghi chú --}}
                                    <td class="text-muted small">{{ $item->notes ?? '-' }}</td>

                                    {{-- Ngày tạo --}}
                                    <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>

                                    {{-- Thao tác --}}
                                    <td>
                                        @if ($item->status === 'pending')
                                            <form action="{{ route('doctor.listAppointment.confirm', $item->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-sm btn-success rounded-pill shadow-sm">
                                                    <i class="fas fa-check-circle me-1"></i> Xác nhận
                                                </button>
                                            </form>
                                            <form action="{{ route('doctor.listAppointment.reject', $item->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-sm btn-outline-danger rounded-pill shadow-sm">
                                                    <i class="fas fa-times-circle me-1"></i> Hủy
                                                </button>
                                            </form>
                                        @elseif ($item->status === 'confirmed')
                                            <form action="{{ route('doctor.listAppointment.complete', $item->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-sm btn-primary rounded-pill shadow-sm">
                                                    <i class="fas fa-check-double me-1"></i> Hoàn thành
                                                </button>
                                            </form>
                                        @else
                                            <button class="btn btn-sm btn-secondary rounded-pill" disabled>Không khả
                                                dụng</button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-muted text-center py-4">
                                            <i class="fas fa-calendar-times fa-2x d-block mb-2 text-secondary"></i>
                                            <p class="mb-0">Không có lịch hẹn nào</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endsection
