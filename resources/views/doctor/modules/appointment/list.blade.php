@extends('doctor.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="text-center text-uppercase text-lg mb-4">Danh sách lịch hẹn của tôi</h4>

                    {{-- Hiển thị thông báo --}}
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('update'))
                        <div class="alert alert-secondary">{{ session('update') }}</div>
                    @endif
                    @if (session('info'))
                        <div class="alert alert-info">{{ session('info') }}</div>
                    @endif
                    @if (session('delete'))
                        <div class="alert alert-danger">{{ session('delete') }}</div>
                    @endif



                    <div class="table-responsive">
                        <table class="table table-striped align-middle text-center">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Tên bệnh nhân</th>
                                    <th>Người đặt lịch (Chính bệnh nhân hoặc người thân)</th>
                                    <th>Email</th>
                                    <th>Số điện thoại</th>
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
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $item->username }}</td>
                                        <td>{{ $item->bookedBy->name }}</td>
                                        <td>{{ $item->email ?? ($item->patient->user->email ?? '-') }}</td>
                                        <td>{{ $item->phone ?? ($item->patient->phone ?? '-') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->appointment_date)->format('d/m/Y') }}</td>
                                        <td>{{ $item->day_vn ?? '-' }}</td>
                                        <td>
                                            {{ $item->schedule->schedule->start_time ?? '-' }} -
                                            {{ $item->schedule->schedule->end_time ?? '-' }}
                                        </td>

                                        {{-- Trạng thái --}}
                                        <td>
                                            @switch($item->status)
                                                @case('pending')
                                                    <span class="badge badge-warning">Chờ xác nhận</span>
                                                @break

                                                @case('confirmed')
                                                    <span class="badge badge-success">Đã xác nhận</span>
                                                @break

                                                @case('cancelled')
                                                    <span class="badge badge-danger">Đã hủy</span>
                                                @break

                                                @case('completed')
                                                    <span class="badge badge-primary">Hoàn thành</span>
                                                @break

                                                @default
                                                    <span class="badge badge-secondary">Không rõ</span>
                                            @endswitch
                                        </td>

                                        {{-- Ghi chú --}}
                                        <td>{{ $item->notes ?? '-' }}</td>

                                        <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>

                                        {{-- Thao tác --}}
                                        <td>
                                            @if ($item->status === 'pending')
                                                <a href="" class="btn btn-sm btn-success">Xác nhận</a>
                                                <a href="" class="btn btn-sm btn-danger">Hủy</a>
                                            @elseif ($item->status === 'confirmed')
                                                <a href="" class="btn btn-sm btn-primary">Hoàn thành</a>
                                            @else
                                                <button class="btn btn-sm btn-secondary" disabled>Không khả dụng</button>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-muted text-center">Không có lịch hẹn nào</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
