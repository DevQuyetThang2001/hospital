@extends('admin.layouts.app')


@section('content')
<div class="row">
    <div class="col-lg-12 stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="text-center text-uppercase text-lg">Quản lý phòng bệnh</h4>
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
                  <th>Tên khoa viện</th>
                  <th>Số lượng phòng</th>
                  <th>Thao tác</th>
                </tr>
              </thead>
              <tbody>
                @if(!empty($rooms))
                    @foreach ($rooms as $item)
                      <tr>
                        <td>{{$item->department->name}}</td>
                        <td>{{$item->so_phong}}</td>
                        <td>
                            <a href="{{route('admin.roomsDetails.list', $item->department_id)}}" class="btn btn-secondary">
                              Xem chi tiết
                            </a>
                        </td>
                    </tr>
                    @endforeach
                    @else
                        <h1>Không có dữ liệu</h1>
                    @endif
              </tbody>
            </table>

            @if ($departmentsWithoutRooms->isEmpty())
              <a  href="{{route('admin.rooms.addForm')}}" class="disabled btn btn-success text-light text-uppercase font-weight-bold d-block">Thêm mới</a>
            @else
              <a  href="{{route('admin.rooms.addForm')}}" class="btn btn-success text-light text-uppercase font-weight-bold d-block">Thêm mới</a>
            @endif
            <p class="text-warning text-xl-center">(Chỉ thêm mới nếu chưa khoa viện nào có phòng)</p>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection