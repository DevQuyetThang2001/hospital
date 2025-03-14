@extends('admin.layouts.app')
@section('content')
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
              <h4 class="card-title">Xác nhận bác sĩ</h4>
              <form class="forms-sample" action="{{route('admin.doctors.add')}}" method="POST" enctype="multipart/form-data">
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
                    <label for="exampleInputName1">Bác sĩ</label>
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
                  <label for="exampleInputPassword4">Chuyên môn</label>
                  <input type="text" value="{{old('specialization')}}" name="specialization" class="form-control" id="exampleInputPassword4" placeholder="Chuyên môn">
                  @error('specialization')
                    <div class="text-danger text-sm">{{$message}}</div>
                  @enderror
                </div>

                <div class="form-group">
                    <label for="exampleInputPassword4">Số năm hành nghề</label>
                    <input type="number" value="{{old('experience_years')}}" name="experience_years" class="form-control" id="exampleInputPassword4" placeholder="Số năm hành nghề">
                    @error('experience_years')
                      <div class="text-danger text-sm">{{$message}}</div>
                    @enderror
                </div>
{{-- license_number --}}

                <div class="form-group">
                    <label for="exampleInputPassword4">Chứng chỉ hành nghề</label>
                    <input type="text" value="{{old('license_number')}}" name="license_number" class="form-control" id="exampleInputPassword4" placeholder="Chứng chỉ hành nghề">
                    @error('license_number')
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
                <a href="{{route('admin.doctors.list')}}" class="btn btn-warning mr-2">Trở về</a>
              </form>
            </div>
          </div>
    </div>
</div>
@endsection