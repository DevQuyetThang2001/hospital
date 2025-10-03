@extends('admin.layouts.app')


@section('content')
  <div class="row">
    <div class="col-lg-12 stretch-card">
    <div class="card">
      <div class="card-body">
      <h4 class="text-center text-uppercase text-lg">Quản lý tài khoản</h4>
      <a href="{{route('admin.users.addUserForm')}}" class="btn btn-success uppercase text-md-end text-uppercase">Thêm
        mới</a>
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
          <th>Ảnh đại diện</th>
          <th>Tên tài khoản</th>
          <th>Email</th>
          <th>Trạng thái</th>
          <th>Ngày tạo</th>
          <th>Vai trò</th>
          <th>Thao tác</th>
          </tr>
        </thead>
        <tbody>
          @if(!empty($data))
          @foreach ($data as $item)
          <tr>
          <td>
          @php
             $imagePath = 'storage/' . $item->image;
          @endphp

          @if (!empty($item->image) && file_exists(public_path($imagePath)))
            <img style="width: 80px; height: 80px; object-fit: cover" src="{{ asset($imagePath) }}" alt="image">
          @else
            <img src="https://placehold.co/400" style="width: 80px; height: 80px; object-fit: cover" />
          @endif
          </td>
          <td>{{$item->name}}</td>
          <td>{{$item->email}}</td>
          <td>
          @if ($item->active === 1)
        <div class="badge badge-success">Online</div>
      @else
      <div class="badge badge-danger">Offline</div>
    @endif
          </td>
          <td>{{$item->created_at}}</td>
          <td>
          @if ($item->role == 'admin')
        Admin
      @elseif($item->role == 'doctor')
      Bác sĩ

    @elseif($item->role == 'nurse')
      Y tá

    @elseif($item->role == 'receptionist')
      Lễ tân
    @else
      Bệnh nhân
    @endif
          </td>
          <td>
          <a href="{{route('admin.users.editUserForm', $item->id)}}" class="btn btn-secondary">
          Sửa <i class="typcn typcn-edit btn-icon-append"></i>
          </a>
          {{-- <a href="{{route('admin.users.delete', $item->id)}}" class="btn btn-danger">Xóa</a> --}}
          <!-- Button trigger modal -->

          @if ($item->role === 'admin')
        <button disabled type="button" class="btn btn-primary" data-toggle="modal"
        data-target="#modal-{{ $item->id }}">
        Xóa
        </button>
      @else
      <button type="button" class="btn btn-primary" data-toggle="modal"
      data-target="#modal-{{ $item->id }}">
      Xóa
      </button>
    @endif
          </td>
          </tr>

          <!-- Modal -->
          <div class="modal fade z-3" id="modal-{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
          aria-hidden="true">
          <div class="modal-dialog">
          <div class="modal-content">
          <div class="modal-header">
          <h5 class="modal-title text-center" id="exampleModalLabel">Xóa tài khoản</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
          </div>
          <div class="modal-body">
          Bạn chắc chắn muốn xóa <span class="text-warning">{{$item->name}}</span>
          </div>
          <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Thoát</button>
          <a href="{{route('admin.users.delete', $item->id)}}" type="button" class="btn btn-primary">Đồng
          ý</a>
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