@extends('doctor.layouts.app')

@section('content')
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Tạo bài viết</h4>

                    {{-- Thông báo thành công hoặc lỗi --}}
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form class="forms-sample" action="" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Tiêu đề bài viết --}}
                        <div class="form-group">
                            <label for="title">Tiêu đề bài viết</label>
                            <input type="text" name="title" id="title" class="form-control" placeholder="Nhập tiêu đề..."
                                value="{{ old('title') }}" required>
                            @error('title')
                                <div class="text-danger text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nội dung (CKEditor) --}}
                        <div class="form-group">
                            <label for="description">Nội dung bài viết</label>
                            <textarea name="description" id="editor" rows="8" class="form-control"
                                placeholder="Nhập nội dung bài viết...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="text-danger text-sm">{{ $message }}</div>
                            @enderror
                        </div>



                        {{-- Ảnh bài viết --}}
                        <div class="form-group">
                            <label for="images">Ảnh bài viết (có thể chọn nhiều)</label>
                            <input type="file" name="images[]" id="images" class="form-control" multiple>
                            @error('images.*')
                                <div class="text-danger text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nút thao tác --}}
                        <button type="submit" class="btn btn-primary mr-2">Đăng bài viết</button>
                        <a href="{{ route('doctor.blogs.list') }}" class="btn btn-warning">Trở về</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- CKEditor --}}
    <script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('editor');
    </script>
@endsection