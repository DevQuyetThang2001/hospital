@extends('admin.layouts.app')
@section('content')
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
              <h4 class="card-title">Sửa tài khoản</h4>
              <form class="forms-sample" action="{{route('admin.users.edit',$user->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                  <label for="exampleInputName1">Họ Tên</label>
                  <input type="text" name="name" value="{{$user->name}}" class="form-control" id="exampleInputName1" placeholder="Họ tên">
                  @error('name')
                      <div class="text-danger text-sm">{{$message}}</div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail3">Email</label>
                  <input type="email" name="email" value="{{$user->email}}" class="form-control" id="exampleInputEmail3" placeholder="Email">
                  @error('email')
                      <div class="text-danger text-sm">{{$message}}</div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword4">Mật khẩu</label>
                  <input type="password" name="password" class="form-control" id="exampleInputPassword4" placeholder="Mật khẩu">
                  <p class="text-warning">Để trống nếu không muốn thay đổi mật khẩu</p>
                  @error('password')
                    <div class="text-danger text-sm">{{$message}}</div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="exampleSelectGender">Vai trò</label>
                    <select name="role" class="form-control" id="exampleSelectGender">
                        <option value="">Chọn vai trò</option>      
                        <option {{$user->role === 'admin' ? 'selected' : ''}} value="admin">Admin</option>      
                        <option {{$user->role === 'doctor' ? 'selected' : ''}} value="doctor">Bác sĩ</option>      
                        <option {{$user->role === 'nurse' ? 'selected' : ''}} value="nurse">Y tá</option>      
                        <option {{$user->role === 'receptionist' ? 'selected' : ''}} value="receptionist">Lễ tân</option>      
                        <option {{$user->role === 'patient' ? 'selected' : ''}} value="patient">Bệnh nhân</option>      
                    </select>

                    @error('role')
                        <div class="text-danger text-sm">{{$message}}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="exampleSelectGender">Trạng thái</label>
                    <select name="active" class="form-control" id="exampleSelectGender">
                        <option value="">Chọn trạng thái</option>      
                        <option {{$user->active === 1 ? 'selected' : ''}} value="1">Online</option>      
                        <option {{$user->active === 0 ? 'selected' : ''}} value="0">Offline</option>       
                    </select>
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
                <div class="form-group">
                  <label for="exampleInputName1">Ảnh đại diện</label>
                  <input type="file" name="image" class="form-control" id="image" accept="image/*">
                  @error('image')
                      <div class="text-danger text-sm">{{$message}}</div>
                  @enderror

                  @if ($user->image)
                    <p>Ảnh hiện tại</p>
                    <img style="width: 100px; height: 100px;" src="{{asset('storage/'.$user->image)}}" alt="{{$user->name}}"/>
                  @else
                    <img style="width: 100px; height: 100px;"  src="https://placehold.co/400" alt="Empty"/>
                  @endif
                </div>
                <button type="submit" class="btn btn-secondary mr-2">Cập nhật</button>
                <a href="{{route('admin.users.list')}}" type="submit" class="btn btn-primary mr-2">Quay lại</a>
              </form>
            </div>
          </div>
    </div>
</div>
@endsection