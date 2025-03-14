@extends('admin.layouts.app')
@section('content')
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
              <h4 class="card-title">Thêm khoa viện</h4>
              <form class="forms-sample" action="{{route('admin.departments.add')}}" method="POST">
                @csrf
                <div class="form-group">
                  <label for="exampleInputName1">Tên khoa viện</label>
                  <input type="text" name="name" value="{{old('name')}}" class="form-control" id="exampleInputName1" placeholder="Tên khoa viện">
                  @error('name')
                      <div class="text-danger text-sm">{{$message}}</div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail3">Mô tả</label>
                  <input type="text" name="description" value="{{old('description')}}" class="form-control" id="exampleInputEmail3" placeholder="Mô tả">
                  @error('description')
                      <div class="text-danger text-sm">{{$message}}</div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="exampleSelectGender">Trạng thái</label>
                    <select name="status" class="form-control" id="exampleSelectGender">
                        <option value="">Chọn trạng thái</option>      
                        <option value="0">OFF</option>      
                        <option value="1">ON</option>      
                    </select>

                    @error('status')
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
                <a href="{{route('admin.departments.list')}}" class="btn btn-warning mr-2">Trở về</a>
              </form>
            </div>
          </div>
    </div>
</div>
@endsection