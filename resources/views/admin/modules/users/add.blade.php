@extends('admin.layouts.app')
@section('content')
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
              <h4 class="card-title">Thêm tài khoản</h4>
              <form class="forms-sample" action="{{route('admin.users.add')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                  <label for="exampleInputName1">Họ Tên</label>
                  <input type="text" name="name" value="{{old('name')}}" class="form-control" id="exampleInputName1" placeholder="Họ tên">
                  @error('name')
                      <div class="text-danger text-sm">{{$message}}</div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail3">Email</label>
                  <input type="email" name="email" value="{{old('email')}}" class="form-control" id="exampleInputEmail3" placeholder="Email">
                  @error('email')
                      <div class="text-danger text-sm">{{$message}}</div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail3">Căn cước công dân</label>
                  <input type="text" name="CCCD" value="{{old('CCCD')}}" class="form-control" id="exampleInputEmail3" placeholder="Căn cước công dân">
                  @error('CCCD')
                      <div class="text-danger text-sm">{{$message}}</div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword4">Mật khẩu</label>
                  <input type="password" value="{{old('password')}}" name="password" class="form-control" id="exampleInputPassword4" placeholder="Mật khẩu">
                  @error('password')
                    <div class="text-danger text-sm">{{$message}}</div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="exampleSelectGender">Vai trò</label>
                    <select name="role" class="form-control" id="exampleSelectGender">
                        <option value="">Chọn vai trò</option>      
                        <option value="admin">Admin</option>      
                        <option value="schedule_manager">Quản lý</option>      
                        <option value="doctor">Bác sĩ</option>      
                        <option value="nurse">Y tá</option>      
                        <option value="receptionist">Lễ tân</option>      
                        <option value="patient">Bệnh nhân</option>      
                    </select>

                    @error('role')
                        <div class="text-danger text-sm">{{$message}}</div>
                    @enderror
                </div>

                <div class="form-group">
                  <label for="exampleInputName1">Ảnh đại diện</label>
                  <input type="file" name="image" class="form-control" id="image" accept="image/*" required>
                  @error('image')
                      <div class="text-danger text-sm">{{$message}}</div>
                  @enderror
                </div>

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
                <button type="submit" class="btn btn-primary mr-2">Thêm</button>
                <a href="{{route('admin.users.list')}}" class="btn btn-warning mr-2">Trở về</a>
              </form>
            </div>
          </div>
    </div>
</div>
@endsection