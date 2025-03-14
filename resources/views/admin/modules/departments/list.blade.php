@extends('admin.layouts.app')


@section('content')
<div class="row">
    <div class="col-lg-12 stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="text-center text-uppercase text-lg">Quản lý khoa viện</h4>
          <a href="{{route('admin.departments.addDepartmentForm')}}" class="btn btn-success uppercase text-md-end text-uppercase">Thêm mới</a>
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
                  <th>Mô tả</th>
                  <th>Trạng thái</th>
                  <th>Ngày tạo</th>
                  <th>Thao tác</th>
                </tr>
              </thead>
              <tbody>
                @if(!empty($data))
                    @foreach ($data as $item)
                    <tr>
                        <td>{{$item->name}}</td>
                        <td>{{$item->description}}</td>
                        <td>
                            @if ($item->status === 1)
                                <div class="badge badge-success">ON</div>
                            @else
                            <div class="badge badge-danger">OFF</div>
                            @endif
                        </td> 
                        <td>{{$item->created_at}}</td>
                        <td>
                            <a href="{{route('admin.departments.editDepartmentForm', $item->id)}}" class="btn btn-secondary">
                              Sửa
                            </a>
                        </td>
                    </tr>

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