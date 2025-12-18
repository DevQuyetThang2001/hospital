@extends('admin.layouts.app')


@section('content')
    <div class="row">
        <div class="col-lg-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="text-center text-uppercase text-lg">Quản lý bệnh nhân</h4>
                    <a href="{{ route('admin.patients.addPatientForm') }}"
                        class="btn btn-success uppercase text-md-end text-uppercase">Xác nhận</a>
                    @if (session('success'))
                        <div class="btn btn-success">{{ session('success') }}</div>
                    @endif

                    @if (session('update'))
                        <div class="btn btn-secondary">{{ session('update') }}</div>
                    @endif

                    @if (session('delete'))
                        <div class="btn btn-danger">{{ session('delete') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Ảnh bệnh nhân</th>
                                    <th>Tên bệnh nhân</th>
                                    <th>Căn cước công dân</th>
                                    <th>Ngày sinh</th>
                                    <th>Giới tính</th>
                                    <th>Khoa viện</th>
                                    <th>Số điện thoại</th>
                                    <th>Địa chỉ</th>
                                    <th>Ngày tạo</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($data))
                                    @foreach ($data as $item)
                                        <tr>
                                            <td>
                                                @if ($item->user)
                                                    <img style="width: 80px;height: 80px;object-fit: cover"
                                                        src="{{ asset('storage/' . $item->user->image) }}"
                                                        alt="{{ $item->user->name }}" />
                                                @else
                                                    <img src="https://placehold.co/400"
                                                        style="width: 80px;height: 80px;object-fit: cover" />
                                                @endif
                                            </td>
                                            <td>{{ $item->user ? $item->user->name : $item->name }}</td>
                                            <td>{{ $item->user->CCCD ?? "Chưa cập nhật" }}</td>
                                            <td>{{ $item->date_of_birth }}</td>
                                            <td>
                                                @if ($item->gender === 'male')
                                                    Nam
                                                @else
                                                    Nữ
                                                @endif
                                            </td>
                                            <td>{{ $item->department?->name ?? 'Chưa cập nhật' }}</td>
                                            <td>{{ $item->phone }}</td>
                                            <td>{{ $item->address }}</td>
                                            <td>{{ $item->created_at }}</td>
                                            <td>
                                                <a href="{{ route('admin.patient.editPatientForm', $item->id) }}"
                                                    class="btn btn-secondary">
                                                    Sửa
                                                </a>
                                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                                    data-target="#modal-{{ $item->id }}">
                                                    Xóa
                                                </button>
                                            </td>
                                        </tr>
                                        <!-- Modal -->
                                        <div class="modal fade z-3" id="modal-{{ $item->id }}" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title text-center" id="exampleModalLabel">Xóa tài
                                                            khoản</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Bạn chắc chắn muốn xóa <span
                                                            class="text-warning">{{ $item->user->name ?? $item->name }}</span>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Thoát</button>
                                                        <a href="{{ route('admin.patient.delete', $item->id) }}"
                                                            type="button" class="btn btn-primary">Đồng ý</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <h1>Không có dữ liệu</h1>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
