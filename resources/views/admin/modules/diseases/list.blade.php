@extends('admin.layouts.app')

@section('content')
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Danh sách bệnh</h4>
                    <a href="{{ route('admin.diseases.addDiseaseForm') }}" class="btn btn-success mb-3">Thêm bệnh mới</a>
                    @session('success')
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endsession
                    @session('delete')
                        <div class="alert alert-success">
                            {{ session('delete') }}
                        </div>
                    @endsession
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên bệnh</th>
                                    <th>Mô tả</th>
                                    <th>Khoa viện</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($diseases as $key => $disease)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $disease->name }}</td>
                                        <td>{{ $disease->description }}</td>
                                        <td>{{ $disease->department->name }}</td>
                                        <td>
                                            <a href="{{ route('admin.diseases.editDiseaseForm', ['id' => $disease->id]) }}"
                                                class="btn btn-sm btn-secondary">Sửa</a>
                                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                                data-target="#modal-{{ $disease->id }}">
                                                Xóa
                                            </button>
                                        </td>
                                    </tr>


                                    <!-- Modal -->
                                    <div class="modal fade z-3" id="modal-{{ $disease->id }}" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-center" id="exampleModalLabel">Xóa loại bệnh
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    Bạn chắc chắn muốn xóa <span
                                                        class="text-warning">{{ $disease->name }}</span>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Thoát</button>
                                                    <a href="{{ route('admin.diseases.delete', $disease->id) }}" type="button"
                                                        class="btn btn-primary">Đồng
                                                        ý</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
