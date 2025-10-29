@extends('doctor.layouts.app')

@section('content')
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Sửa bài viết</h4>

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

                    <form class="forms-sample" action="{{ route('doctor.blogs.update',$blog->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        {{-- Tiêu đề bài viết --}}
                        <div class="form-group">
                            <label for="title">Tiêu đề bài viết</label>
                            <input type="text" name="title" id="title" class="form-control" placeholder="Nhập tiêu đề..."
                                value="{{ old('title', $blog->title) }}" required>
                            @error('title')
                                <div class="text-danger text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nội dung (CKEditor) --}}
                        <div class="form-group">
                            <label for="description">Nội dung bài viết</label>
                            <textarea name="description" id="editor" rows="8" class="form-control"
                                placeholder="Nhập nội dung bài viết...">{{ old('description', $blog->description) }}</textarea>
                            @error('description')
                                <div class="text-danger text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Ảnh minh họa</label><br>
                            @if ($blog->images->count() > 0)
                                <img src="{{ asset('storage/' . $blog->images->first()->image) }}" alt="Ảnh bài viết" width="90"
                                    height="70" class="rounded border">
                            @else
                                <img src="{{ asset('images/no-image.png') }}" alt="Không có ảnh" width="90" height="70"
                                    class="rounded border">
                            @endif
                            <input type="file" name="image" class="form-control mt-2">
                        </div>

                        <button type="submit" class="btn btn-primary mr-2">Cập nhật bài viết</button>
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