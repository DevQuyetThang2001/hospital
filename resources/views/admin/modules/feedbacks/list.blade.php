@extends('admin.layouts.app')


@section('content')
    <div class="row">
        <div class="col-lg-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="text-center text-uppercase text-lg">Quản lý đánh giá</h4>
                    @if (session('success'))
                        <div class="btn btn-success">{{session('success')}}</div>
                    @endif

                    @if (session('update'))
                        <div class="btn btn-secondary">{{session('update')}}</div>
                    @endif

                    @if (session('delete'))
                        <div class="btn btn-danger">{{session('delete')}}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Bệnh nhân</th>
                                    <th>Nội dung</th>
                                    <th>Xếp hạng</th>
                                    <th>Ngày tạo</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($data))
                                    @foreach ($data as $item)
                                        <tr>
                                            <td>{{$item->patient->user->name}}</td>
                                            <td>{{$item->text}}</td>
                                            <td>{{$item->rating}}</td>
                                            <td>{{$item->created_at}}</td>
                                            <td>
                                                <a href="{{route('admin.doctor.editDoctorForm', $item->id)}}"
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
                                                        <h5 class="modal-title text-center" id="exampleModalLabel">Xóa tài khoản
                                                        </h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Bạn chắc chắn muốn xóa <span
                                                            class="text-warning">{{$item->text}}</span>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Thoát</button>
                                                        <a href="{{route('admin.feedbacks.delete', $item->id)}}" type="button"
                                                            class="btn btn-primary">Đồng ý</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <h1 class="text-center">Không có dữ liệu</h1>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection