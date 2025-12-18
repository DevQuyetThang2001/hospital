@extends('manager.layouts.app')


@section('content')
    <div class="row">
        <div class="col-lg-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="text-center text-uppercase text-lg">Quản lý lịch khám</h4>
                    <a href="{{ route('manager.schedules.create') }}"
                        class="btn btn-success uppercase text-md-end text-uppercase">Tạo lịch khám</a>
                    @if (session('success'))
                        <div class="btn btn-success">{{ session('success') }}</div>
                    @endif

                    @if (session('update'))
                        <div class="btn btn-secondary">{{ session('update') }}</div>
                    @endif

                    @if (session('delete'))
                        <div class="btn btn-danger">{{ session('delete') }}</div>
                    @endif
                    @if (session('msg'))
                        <div class="btn btn-danger">{{ session('msg') }}</div>
                    @endif


                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Ngày khám</th>
                                    <th>Giờ khám</th>
                                    <th>Bác sĩ</th>
                                    <th>Số lượng bệnh nhân / giờ khám</th>
                                    <th>Phòng khám</th>
                                    <th>Ngày tạo</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($lists))
                                    @foreach ($lists as $item)
                                        <tr>
                                            <td>
                                                {{ $item->day_of_week_vn }}
                                            </td>
                                            <td>{{ $item->schedule->start_time }} - {{ $item->schedule->end_time }}</td>
                                            <td>{{ $item->doctor->user->name }}</td>
                                            <td>{{ $item->limit_per_hour }}</td>
                                            <td>{{ $item->clinic ? $item->clinic->name : "Chưa có"  }}</td>
                                            <td>{{ $item->created_at }}</td>
                                            <td>
                                                <a href="{{ route('manager.schedules.edit', $item->id) }}"
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
                                                        <h5 class="modal-title text-center" id="exampleModalLabel">Xóa lịch
                                                            khám</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>

                                                    <div class="modal-body">
                                                        Bạn chắc chắn muốn xóa lịch
                                                        <span class="text-warning">
                                                            {{ $item->day_of_week_vn }} ({{ $item->schedule->start_time }}
                                                            - {{ $item->schedule->end_time }})
                                                        </span> của bác sĩ
                                                        <strong>{{ $item->doctor->user->name ?? 'Không rõ' }}</strong>?
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Thoát</button>

                                                        <!-- Form xóa lịch khám -->
                                                        <form action="{{ route('manager.schedules.delete', $item->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Đồng ý</button>
                                                        </form>
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
