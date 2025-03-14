@extends('admin.layouts.app')
@section('content')
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
              <h4 class="card-title">Thêm mới phòng bệnh</h4>
              <form class="forms-sample" action="{{route('admin.rooms.add',$rooms->department_id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="department_id" value="{{$rooms->department_id}}"/>
                <div class="form-group">
                    <label for="exampleInputName1">Số phòng</label>
                    <input type="text" value="{{old('room_number')}}" name="room_number" class="form-control" id="exampleInputPassword4" placeholder="Số phòng">
                    @error('room_number')
                        <div class="text-danger text-sm">{{$message}}</div>
                    @enderror
                    </div>
                <div class="form-group">
                  <label for="exampleInputPassword4">Số lượng giường bệnh</label>
                  <input type="number" value="{{old('capacity')}}" name="capacity" class="form-control" id="exampleInputPassword4" placeholder="Số lượng giuờng bệnh">
                  @error('capacity')
                    <div class="text-danger text-sm">{{$message}}</div>
                  @enderror
                </div>

                <div class="form-group">
                    <label for="exampleInputPassword4">Kiểu phòng</label>
                    <select name="type" class="form-control" id="exampleSelectGender">
                        <option value="">Chọn kiểu phòng</option>      
                        <option value="general">Phòng thường</option>      
                        <option value="vip">Phòng VIP</option>      
                        <option value="svip">Phòng SVIP</option>      
                    </select>
                    @error('type')
                      <div class="text-danger text-sm">{{$message}}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="exampleInputPassword4">Trạng thái phòng</label>
                    <select name="status" class="form-control" id="exampleSelectGender">
                        <option value="">Chọn trạng thái</option>      
                        <option value="available">Có sẵn</option>      
                        <option value="occupied">Đã sử dụng</option>      
                        <option value="maintenance">Bảo trì</option>      
                    </select>
                    @error('status')
                      <div class="text-danger text-sm">{{$message}}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary mr-2">Thêm</button>
                <a href="{{route('admin.roomsDetails.list',$rooms->department_id)}}" class="btn text-light btn-warning mr-2">Trở về</a>
              </form>
            </div>
          </div>
    </div>
</div>
@endsection