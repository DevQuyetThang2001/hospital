@extends('admin.layouts.app')
@section('content')
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
              <h4 class="card-title">Sửa thông tin bác sĩ  <span class="text-secondary">{{$doctor->user->name}}</span> </h4>
              <form class="forms-sample" action="{{route('admin.doctor.edit',$doctor->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                  <label for="exampleInputName1">Chuyên môn</label>
                  <input type="text" name="specialization" value="{{$doctor->specialization}}" class="form-control" id="exampleInputName1" placeholder="Chuyên môn">
                  @error('specialization')
                      <div class="text-danger text-sm">{{$message}}</div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail3">Chứng chỉ hành nghề</label>
                  <input type="text" name="license_number" value="{{$doctor->license_number}}" class="form-control" id="exampleInputEmail3" placeholder="Chứng chỉ hành nghề">
                  @error('license_number')
                      <div class="text-danger text-sm">{{$message}}</div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword4">Số năm kinh nghiệm</label>
                  <input type="number" name="experience_years" value="{{$doctor->experience_years}}" class="form-control" id="exampleInputPassword4" placeholder="Số năm kinh nghiệm">
                  @error('experience_years')
                  <div class="text-danger text-sm">{{$message}}</div>
              @enderror
                </div>
                <div class="form-group">
                  <label for="exampleSelectGender">Khoa viện</label>
                    <select name="department_id" class="form-control" id="exampleSelectGender">
                        <option value="">Chọn khoa viện</option>   
                        @foreach ($departments as $item)
                            <option {{$item->id === $doctor->department_id ? 'selected' : ''}} value="{{$item->id}}">{{$item->name}}</option>     
                        @endforeach
                    </select>
                    @error('department_id')
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
                <a href="{{route('admin.doctors.list')}}" type="submit" class="btn btn-primary mr-2">Quay lại</a>
              </form>
            </div>
          </div>
    </div>
</div>
@endsection