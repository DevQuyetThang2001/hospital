@extends('doctor.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="text-center text-uppercase text-lg">Xác nhận tài khoản bệnh nhân</h4>
                    @if (session('success'))
                        <div class="btn btn-success">{{ session('success') }}</div>
                    @endif

                    @if (session('update'))
                        <div class="btn btn-secondary">{{ session('update') }}</div>
                    @endif

                    @if (session('delete'))
                        <div class="btn btn-danger">{{ session('delete') }}</div>
                    @endif

                    {{-- <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Ảnh bệnh nhân</th>
                                    <th>Tên bệnh nhân</th>
                                    <th>Ngày sinh</th>
                                    <th>Giới tính</th>
                                    <th>Khoa viện</th>
                                    <th>Số điện thoại</th>
                                    <th>Địa chỉ</th>
                                    <th>Ngày tạo</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($data))
                                    @foreach ($data as $item)
                                        <tr>
                                            <td>
                                                @if ($item->user)
                                                    <img style="width: 80px;height: 80px;object-fit: cover"
                                                        src="{{ asset('storage/' . $item->user->image) }}"
                                                        alt="{{ $item->user->name }}" />
                                                @else
                                                    <img src="https://placehold.co/400"
                                                        style="width: 80px;height: 80px;object-fit: cover" />
                                                @endif
                                            </td>
                                            <td>{{ $item->user->name }}</td>
                                            <td>{{ $item->date_of_birth }}</td>
                                            <td>
                                                @if ($item->gender === 'male')
                                                    Nam
                                                @else
                                                    Nữ
                                                @endif
                                            </td>
                                            <td>{{ $item->department->name }}</td>
                                            <td>{{ $item->phone }}</td>
                                            <td>{{ $item->address }}</td>
                                            <td>{{ $item->created_at }}</td>
                                            <td>
                                                <a href="{{ route('admin.patient.editPatientForm', $item->id) }}"
                                                    class="btn btn-success uppercase text-md-end text-uppercase">
                                                    Xác nhận
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <h1 class="text-center">Không có dữ liệu</h1>
                                @endif
                            </tbody>
                        </table>
                    </div> --}}
                    @forelse ($users as $user)
                        <div class="card border-0 shadow-sm mb-3 patient-card position-relative overflow-hidden">
                            <div class="card-body d-flex justify-content-between align-items-center p-4">
                                <div class="d-flex align-items-center">
                                    <div
                                        class="avatar-circle bg-gradient text-white d-flex justify-content-center align-items-center me-3">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div class="ml-4">
                                        <h5 class="fw-semibold mb-1 text-dark">{{ $user->name }}</h5>
                                        <p class="mb-1 text-muted small">
                                            <i class="bi bi-envelope me-1"></i>Email: {{ $user->email }}
                                        </p>
                                        <p class="mb-0 text-muted small">
                                            <i class="bi bi-gender-ambiguous me-1"></i>
                                            Giới tính: {{ $user->gender ?? 'Chưa có' }}
                                        </p>
                                    </div>
                                </div>

                                <form action="{{ route('doctor.patient.confirm', $user->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-lg d-flex align-items-center">
                                        <i class="bi bi-check2-circle me-2"></i> Xác nhận
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info text-center">
                            <i class="bi bi-info-circle me-1"></i> Hiện chưa có tài khoản nào chờ xác nhận.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

<style>
    .avatar-circle {
        width: 60px;
        height: 60px;
        font-size: 24px;
        font-weight: 600;
        border-radius: 50%;
        background: linear-gradient(135deg, #4f8ef7, #6ad3f8);
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
    }

    .patient-card {
        border-left: 6px solid #4f8ef7;
        transition: all 0.25s ease-in-out;
    }

    .patient-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 16px rgba(79, 142, 247, 0.2);
    }

    .btn-success {
        background: linear-gradient(135deg, #28a745, #56d66f);
        border: none;
        transition: all 0.25s ease-in-out;
    }

    .btn-success:hover {
        background: linear-gradient(135deg, #23963f, #4ec460);
        transform: scale(1.03);
    }
</style>