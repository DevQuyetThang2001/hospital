@extends('admin.layouts.app')
@section('content')
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
              <h4 class="card-title">Sửa thông tin bệnh nhân  <span class="text-secondary">{{$patient->user->name}}</span> </h4>
              <form class="forms-sample" action="{{route('admin.patient.edit',$patient->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                  <label for="exampleInputName1">Ngày sinh</label>
                  <input type="text" name="date_of_birth" value="{{$patient->date_of_birth}}" class="form-control" id="exampleInputName1" placeholder="Ngày sinh">
                  @error('date_of_birth')
                      <div class="text-danger text-sm">{{$message}}</div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail3">Giới tính</label>
                  <select name="gender" class="form-control" id="exampleSelectGender">
                    <option value="">Chọn giới tính</option>   
                    <option {{$patient->gender === 'male' ? 'selected' : ''}} value="male">Nam</option>   
                    <option {{$patient->gender === 'female' ? 'selected' : ''}} value="female">Nữ</option>   
                </select>       
                  @error('gender')
                      <div class="text-danger text-sm">{{$message}}</div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword4">Số điện thoại</label>
                  <input type="text" name="phone" value="{{$patient->phone}}" class="form-control" id="exampleInputPassword4" placeholder="Số điện thoại">
                  @error('phone')
                    <div class="text-danger text-sm">{{$message}}</div>
                  @enderror
                </div>

                <div class="form-group">
                    <label for="exampleInputPassword4">Địa chỉ</label>
                    <input type="text" name="address" value="{{$patient->address}}" class="form-control" id="exampleInputPassword4" placeholder="Địa chỉ">
                    @error('address')
                      <div class="text-danger text-sm">{{$message}}</div>
                    @enderror
                  </div>

                <div class="form-group">
                  <label for="exampleSelectGender">Khoa viện</label>
                    <select name="department_id" class="form-control" id="exampleSelectGender">
                        <option value="">Chọn khoa viện</option>   
                        @foreach ($departments as $item)
                            <option {{$item->id === $patient->department_id ? 'selected' : ''}} value="{{$item->id}}">{{$item->name}}</option>     
                        @endforeach
                    </select>
                    @error('department_id')
                        <div class="text-danger text-sm">{{$message}}</div>
                    @enderror
                </div>

                {{-- <img style="width: 80px;height=80px;object-fit: cover; " src="{{asset('storage/'.$patient->user->image)}}"/> --}}

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
                <a href="{{route('admin.patients.list')}}" type="submit" class="btn btn-primary mr-2">Quay lại</a>
              </form>
            </div>
          </div>
    </div>
</div>
@endsection