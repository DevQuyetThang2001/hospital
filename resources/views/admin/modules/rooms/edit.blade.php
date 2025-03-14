@extends('admin.layouts.app')
@section('content')
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
              <h4 class="card-title">Sửa thông tin phòng bệnh  <span class="text-secondary">{{$room->room_number}}</span> </h4>
              <form action="{{ route('admin.rooms.edit', ['department_id' => $room->department_id, 'room_id' => $room->id]) }}" class="forms-sample" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                  <label for="exampleInputName1">Số phòng</label>
                  <input type="text" name="room_number" value="{{$room->room_number}}" class="form-control" id="exampleInputName1" placeholder="Chuyên môn">
                  @error('room_number')
                      <div class="text-danger text-sm">{{$message}}</div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail3">Kiểu phòng</label>
                  <select name="type" class="form-control" id="exampleSelectGender">
                    <option value="">Chọn kiểu phòng</option>   
                    <option {{$room->type=== 'general' ? 'selected' : ''}} value="general">Phòng thường</option>     
                    <option {{$room->type=== 'vip' ? 'selected' : ''}} value="vip">Phòng VIP</option>     
                    <option {{$room->type=== 'svip' ? 'selected' : ''}} value="svip">Phòng SVIP</option>     
                </select>
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword4">Số lượng giường bệnh</label>
                  <input type="number" name="capacity" value="{{$room->capacity}}" class="form-control" id="exampleInputPassword4" placeholder="Số lượng giường bệnh">
                  @error('capacity')
                  <div class="text-danger text-sm">{{$message}}</div>
              @enderror
                </div>
                <div class="form-group">
                  <label for="exampleSelectGender">Trạng thái phòng bệnh</label>
                    <select name="status" class="form-control" id="exampleSelectGender">
                        <option value="">Chọn trạng thái</option>   
                        <option {{$room->status=== 'available' ? 'selected' : ''}} value="available">Có sẵn</option>     
                        <option {{$room->status=== 'occupied' ? 'selected' : ''}} value="occupied">Đã sử dụng</option>     
                        <option {{$room->status=== 'maintenance' ? 'selected' : ''}} value="maintenance">Đang bảo trì</option>       
                    </select>
                    @error('status')
                        <div class="text-danger text-sm">{{$message}}</div>
                    @enderror
                </div>

                {{-- <img style="width: 80px;height=80px;object-fit: cover; " src="{{asset('storage/'.$doctor->user->image)}}"/> --}}

                {{-- <div class="form-group">
                    <label for="exampleSelectGender">Trạng thái</label>
                    <select name="active" class="form-control" id="exampleSelectGender">
                        <option value="">Chọn trạng thái</option>      
                        <option {{$user->active === 1 ? 'selected' : ''}} value="1">Online</option>      
                        <option {{$user->active === 0 ? 'selected' : ''}} value="0">Offline</option>       
                    </select>
                  </div> --}}
                {{-- <div class="form-group">
                  <label>File upload</label>
                  <input type="file" name="img[]" class="file-upload-default">
                  <div class="input-group col-xs-12">
                    <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                    <span class="input-group-append">
                      <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                    </span>
                  </div>
                </div> --}}
                <button type="submit" class="btn btn-secondary mr-2">Cập nhật</button>
                <a href="{{route('admin.roomsDetails.list',$room->department_id)}}" type="submit" class="btn btn-primary mr-2">Quay lại</a>
              </form>
            </div>
          </div>
    </div>
</div>
@endsection