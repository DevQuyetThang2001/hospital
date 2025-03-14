@extends('admin.layouts.app')
@section('content')
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
              <h4 class="card-title">Xác nhận bệnh nhân</h4>
              <form class="forms-sample" action="{{route('admin.patients.add')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                <label for="exampleInputName1">Khoa viện</label>
                <select name="department_id" class="form-control" id="exampleSelectGender">
                      <option value="">Chọn khoa viện</option>      
                    @foreach ($department as $item)
                        <option value="{{$item->id}}">{{$item->name}}</option>    
                    @endforeach  
                </select>
                @error('department_id')
                    <div class="text-danger text-sm">{{$message}}</div>
                @enderror
                </div>

                <div class="form-group">
                    <label for="exampleInputName1">Bệnh nhân</label>
                    <select name="user_id" class="form-control" id="exampleSelectGender">
                          <option value="">Chọn tài khoản</option>      
                            @foreach ($user as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>    
                            @endforeach  
                    </select>
                    @error('user_id')
                        <div class="text-danger text-sm">{{$message}}</div>
                    @enderror
                    </div>
                <div class="form-group">
                  <label for="exampleInputPassword4">Ngày sinh</label>
                  <input type="date" value="{{old('date_of_birth')}}" name="date_of_birth" class="form-control" id="exampleInputPassword4" placeholder="Chuyên môn">
                  @error('date_of_birth')
                    <div class="text-danger text-sm">{{$message}}</div>
                  @enderror
                </div>

                <div class="form-group">
                    <label for="exampleInputPassword4">Giới tính</label>
                    <select name="gender" class="form-control" id="exampleSelectGender">
                        <option value="">Chọn giới tính</option>      
                        <option value="male">Nam</option>     
                        <option value="female">Nữ</option>     
                  </select>
                    @error('gender')
                      <div class="text-danger text-sm">{{$message}}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="exampleInputPassword4">Số điện thoại</label>
                    <input type="text" value="{{old('phone')}}" name="phone" class="form-control" id="exampleInputPassword4" placeholder="Số điện thoại">
                    @error('phone')
                    <div class="text-danger text-sm">{{$message}}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="exampleInputPassword4">Địa chỉ</label>
                    <input type="text" value="{{old('address')}}" name="address" class="form-control" id="exampleInputPassword4" placeholder="Địa chỉ">
                    @error('address')
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
                <a href="{{route('admin.patients.list')}}" class="btn btn-warning mr-2">Trở về</a>
              </form>
            </div>
          </div>
    </div>
</div>
@endsection