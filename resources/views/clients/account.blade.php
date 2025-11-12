@extends('clients.layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                    <div class="card-header text-white text-center"
                        style="background: linear-gradient(90deg, #007bff, #0056b3);">
                        <h4 class="mb-0"><i class="bi bi-person-circle me-2"></i>Thông tin tài khoản</h4>
                    </div>

                    <div class="card-body p-4 text-center">
                        {{-- Ảnh đại diện --}}
                        <div class="mb-4">
                            <img id="avatarPreview"
                                src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}"
                                alt="Avatar" class="rounded-circle shadow" width="120" height="120">
                        </div>

                        {{-- Thông báo thành công --}}
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show text-start" role="alert">
                                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session('info'))
                            <div class="alert alert-info alert-dismissible fade show text-start" role="alert">
                                <i class="bi bi-check-circle me-2"></i>{{ session('info') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        {{-- Thông báo lỗi --}}
                        @if ($errors->any())
                            <div class="alert alert-danger text-start">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $err)
                                        <li>{{ $err }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Form cập nhật --}}
                        <form method="POST" action="{{ route('client.account.updateInfo') }}" enctype="multipart/form-data"
                            class="text-start">
                            @csrf

                            {{-- Avatar --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Ảnh đại diện</label>
                                <input type="file" name="avatar" class="form-control rounded-3 shadow-sm"
                                    accept="image/*" onchange="previewAvatar(event)">
                                @error('avatar')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Họ tên --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Họ tên</label>
                                <input type="text" name="name" class="form-control rounded-3 shadow-sm"
                                    value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="email" class="form-control rounded-3 shadow-sm text-muted"
                                    value="{{ $user->email }}" disabled>
                            </div>

                            {{-- Mật khẩu hiện tại --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Mật khẩu hiện tại</label>
                                <input type="password" name="current_password" class="form-control rounded-3 shadow-sm"
                                    placeholder="Nhập mật khẩu hiện tại nếu muốn đổi">
                                @error('current_password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Mật khẩu mới --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Mật khẩu mới</label>
                                <input type="password" name="password" class="form-control rounded-3 shadow-sm"
                                    placeholder="Nhập mật khẩu mới">
                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Xác nhận mật khẩu --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Xác nhận mật khẩu mới</label>
                                <input type="password" name="password_confirmation" class="form-control rounded-3 shadow-sm"
                                    placeholder="Nhập lại mật khẩu mới">
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 shadow-sm">
                                    <i class="bi bi-save me-1"></i> Cập nhật
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="card-footer text-center text-muted small bg-light py-2">
                        <i class="bi bi-shield-lock me-1"></i>Thông tin của bạn được bảo mật.
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script preview avatar --}}
    <script>
        function previewAvatar(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('avatarPreview');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
@endsection
