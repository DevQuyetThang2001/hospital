@extends('manager.layouts.app')

@section('content')
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card shadow-sm">
                <div class="card-body">

                    {{-- Header --}}
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">
                            <i class="mdi mdi-hospital-building text-primary"></i>
                            Danh sách phòng khám
                        </h4>

                        <a href="{{ route('manager.clinics.create') }}" class="btn btn-primary">
                            <i class="mdi mdi-plus"></i> Thêm phòng khám
                        </a>
                    </div>

                    {{-- Success message --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Table --}}
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle">
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Tên phòng khám</th>
                                    <th>Khoa viện</th>
                                    <th>Trạng thái</th>
                                    <th>Số bác sĩ</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($clinics as $clinic)
                                    <tr>
                                        <td class="text-center">{{ $clinic->id }}</td>

                                        <td>
                                            <strong>{{ $clinic->name }}</strong>
                                        </td>

                                        <td>
                                            {{ $clinic->department->name }}
                                        </td>

                                        <td class="text-center">
                                            @if ($clinic->status)
                                                <span class="badge bg-success">Hoạt động</span>
                                            @else
                                                <span class="badge bg-secondary">Ngưng</span>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            <span
                                                class="badge {{ $clinic->quantity >= 4 ? 'bg-success' : 'bg-info' }} text-lg">
                                                {{ $clinic->quantity }} bác sĩ
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            <a href="{{ route('manager.clinics.edit', $clinic->id) }}"
                                                class="btn btn-sm btn-warning">
                                                <i class="mdi mdi-pencil"></i>
                                                Sửa
                                            </a>

                                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                                data-target="#modal-{{ $clinic->id }}">
                                                Xóa
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Modal -->
                                    <div class="modal fade z-3" id="modal-{{ $clinic->id }}" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-center" id="exampleModalLabel">Xóa phòng
                                                        khám</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    Bạn chắc chắn muốn xóa phòng khám <span
                                                        class="text-warning">{{ $clinic->name }}</span>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                        Thoát
                                                    </button>

                                                    <form action="{{ route('manager.clinics.delete', $clinic->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">
                                                            Đồng ý xóa
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">
                                            Chưa có phòng khám nào
                                        </td>
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
