@extends('admin.layouts.app')


@section('content')
<div class="row">
    <div class="col-lg-12 stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="text-center text-uppercase text-lg">Quản lý phòng bệnh {{$rooms->first()->department->name ??  ''}}</h4>
          <a href="{{route('admin.rooms.addRoomForm',$rooms->first()->department->id)}}" class="btn btn-success uppercase text-md-end text-uppercase">Thêm mới</a>
          @if (session('success'))
              <div class="btn btn-success">{{session('success')}}</div>
          @endif

          @if (session('update'))
              <div class="btn btn-secondary">{{session('update')}}</div>
          @endif

          @if (session('delete'))
            <div class="btn btn-danger">{{session('delete')}}</div>
          @endif

          @if (session('alert'))
            <div class="btn btn-danger">{{session('alert')}}</div>
          @endif

          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>Số phòng</th>
                  <th>Số giường</th>
                  <th>Kiểu phòng</th>
                  <th>Trạng thái</th>
                  <th>Thao tác</th>
                </tr>
              </thead>
              <tbody>
                @if(!empty($rooms))
                    @foreach ($rooms as $item)
                      <tr>
                        <td>{{$item->room_number}}</td>
                        <td>{{$item->capacity}}</td>
                        <td>
                            @if ($item->type === 'general')
                                Phòng thường
                            @elseif ($item->type == 'vip')
                                Phòng VIP
                            @else
                                Phòng SVIP
                            @endif

                        </td>

                        <td>
                          @if($item->status === 'available')
                                Có sẵn
                            @elseif ($item->type == 'occupied')
                                Đã sử dụng
                            @else
                                Bảo trì
                            @endif
                        </td>
                        {{-- <td>{{$item->so_phong}}</td> --}}
                        <td>
                            <a href="{{route('admin.rooms.editRoomForm', ['department_id'=> $rooms->first()->department->id, 'room_id' => $item->id])}}" class="btn btn-secondary">
                                Sửa
                            </a>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-{{ $item->id }}">
                                Xóa
                            </button>
                        </td>
                    </tr>

                    <div class="modal fade z-3" id="modal-{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                           <div class="modal-content">
                           <div class="modal-header">
                               <h5 class="modal-title text-center" id="exampleModalLabel">Xóa phòng bệnh</h5>
                               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                               <span aria-hidden="true">&times;</span>
                               </button>
                           </div>
                           <div class="modal-body">
                               Bạn chắc chắn muốn xóa <span class="text-warning">{{$item->room_number}}</span>
                           </div>
                           <div class="modal-footer">
                               <button type="button" class="btn btn-secondary" data-dismiss="modal">Thoát</button>
                               <a href="{{route('admin.rooms.delete',['department_id'=> $rooms->first()->department->id, 'room_id' => $item->id])}}" type="button" class="btn btn-primary">Đồng ý</a>
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


            <a href="{{route('admin.rooms.list')}}" type="button" class="btn btn-warning text-light">Quay lại</a>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection