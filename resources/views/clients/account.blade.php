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
                        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}"
                            alt="Avatar" class="rounded-circle shadow" width="120" height="120">
                    </div>

                    {{-- Hiển thị thông báo --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show text-start" role="alert">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Form cập nhật --}}
                    <form method="POST" action="{{ route('client.account.updateInfo') }}" enctype="multipart/form-data" class="text-start">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Ảnh đại diện</label>
                            <input type="file" name="avatar" class="form-control rounded-3 shadow-sm">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Họ tên</label>
                            <input type="text" name="name" class="form-control rounded-3 shadow-sm"
                                value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" class="form-control rounded-3 shadow-sm text-muted"
                                value="{{ $user->email }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Đổi mật khẩu</label>
                            <input type="password" name="password" class="form-control rounded-3 shadow-sm"
                                placeholder="Nhập mật khẩu mới (nếu muốn đổi)">
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
@endsection