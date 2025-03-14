@extends('admin.layouts.app')
@section('content')
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
              <h4 class="card-title">Sửa khoa viện</h4>
              <form class="forms-sample" action="{{route('admin.departments.edit',$department->id)}}" method="POST">
                @csrf
                <div class="form-group">
                  <label for="exampleInputName1">Tên khoa viện</label>
                  <input type="text" name="name" value="{{$department->name}}" class="form-control" id="exampleInputName1" placeholder="Tên khoa viện">
                  @error('name')
                      <div class="text-danger text-sm">{{$message}}</div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail3">Mô tả</label>
                  <input type="text" name="description" value="{{$department->description}}" class="form-control" id="exampleInputEmail3" placeholder="Mô tả">
                  @error('description')
                      <div class="text-danger text-sm">{{$message}}</div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="exampleSelectGender">Trạng thái</label>
                    <select name="status" class="form-control" id="exampleSelectGender">
                        <option value="">Chọn trạng thái</option>      
                        <option {{$department->status === 0 ? 'selected' : ''}} value="0">OFF</option>      
                        <option {{$department->status === 1 ? 'selected' : ''}} value="1">ON</option>      
                    </select>

                    @error('role')
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
                <button type="submit" class="btn btn-secondary mr-2">Cập nhật</button>
                <a href="{{route('admin.departments.list')}}" type="submit" class="btn btn-primary mr-2">Quay lại</a>
              </form>
            </div>
          </div>
    </div>
</div>
@endsection