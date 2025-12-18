@extends('clients.layouts.app')

@section('content')
    <div class="container py-4">
        <h2 class="fw-bold mb-4">Tìm kiếm bác sĩ theo bệnh</h2>

        {{-- Form chọn bệnh --}}
        <form action="{{ route('client.doctors.handleSearchByDisease') }}" method="POST">
            @csrf

            <div class="row g-3 mb-4">
                @foreach ($diseases as $disease)
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <label class="disease-card">
                            <input type="checkbox" name="diseases[]" value="{{ $disease->id }}">

                            <div class="card h-100 shadow-sm">
                                <div class="card-body">
                                    <h6 class="card-title mb-2">
                                        {{ ucfirst($disease->name) }}
                                    </h6>

                                    <p class="card-text text-muted small">
                                        {{ $disease->description }}
                                    </p>
                                </div>
                            </div>
                        </label>
                    </div>
                @endforeach
            </div>

            <button type="submit" class="btn btn-primary px-4">
                Tìm kiếm
            </button>
        </form>

        {{-- Hiển thị kết quả inline (nếu có) --}}
        @if (isset($diseases) && isset($doctors))
            <div class="mt-4">
                <h4>Bệnh đã chọn:</h4>
                <ul class="list-group mb-3">
                    @foreach ($diseases as $disease)
                        <li class="list-group-item">{{ ucfirst($disease->name) }}</li>
                    @endforeach
                </ul>

                <h4>Bác sĩ liên quan:</h4>
                @if ($doctors->count() > 0)
                    <div class="row">
                        @foreach ($doctors as $doctor)
                            <div class="col-md-4 mb-3">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-body">
                                        <h5 class="card-title">Bác sĩ {{ $doctor->user->name }}</h5>
                                        <p class="card-text">
                                            Chuyên khoa: {{ $doctor->department->name ?? 'Chưa xác định' }}<br>
                                            Email: {{ $doctor->user->email ?? 'Chưa có' }}<br>

                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p>Không có bác sĩ nào liên quan đến bệnh đã chọn.</p>
                @endif
            </div>

            {{-- Modal kết quả --}}
            <button type="button" class="btn btn-success mt-3" data-bs-toggle="modal" data-bs-target="#searchResultModal">
                Xem kết quả trên modal
            </button>

            <div class="modal fade" id="searchResultModal" tabindex="-1" aria-labelledby="searchResultModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="searchResultModalLabel">Kết quả tìm kiếm</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                        </div>
                        <div class="modal-body">
                            <h6>Bệnh đã chọn:</h6>
                            <ul>
                                @foreach ($diseases as $disease)
                                    <li>{{ ucfirst($disease->name) }}</li>
                                @endforeach
                            </ul>

                            <h6>Bác sĩ liên quan:</h6>
                            @if ($doctors->count() > 0)
                                <div class="row">
                                    @foreach ($doctors as $doctor)
                                        <div class="col-md-6 mb-3">
                                            <div class="card shadow-sm border-0 h-100">

                                                <!-- Doctor Image Wrapper (fix ảnh bị cắt) -->
                                                <div
                                                    style="width: 100%; height: 220px; overflow: hidden; border-radius: 10px 10px 0 0; background: #f8f9fa;">
                                                    @if (!empty($doctor->user->image))
                                                        <img src="{{ asset('storage/' . $doctor->user->image) }}"
                                                            alt="doctor image"
                                                            style="width: 100%; height: 100%; object-fit: contain; object-position: center;">
                                                    @else
                                                        <img src="{{ asset('images/default-doctor.jpg') }}"
                                                            alt="default doctor"
                                                            style="width: 100%; height: 100%; object-fit: contain; object-position: center;">
                                                    @endif
                                                </div>

                                                <div class="card-body">
                                                    <h5 class="card-title">{{ $doctor->user->name }}</h5>

                                                    <p class="card-text mb-2">
                                                        <strong>Chuyên khoa:</strong>
                                                        {{ $doctor->department->name ?? 'Chưa xác định' }} <br>

                                                        <strong>Email:</strong>
                                                        {{ $doctor->user->email ?? 'Chưa có' }}
                                                    </p>

                                                    <!-- Button -->
                                                    <a href="{{ route('client.doctors.show', $doctor->id) }}"
                                                        class="btn btn-primary w-100">
                                                        Xem thông tin bác sĩ
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p>Không có bác sĩ nào liên quan.</p>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        // Nếu muốn tự động mở modal khi có kết quả
        @if (isset($doctors) && $doctors->count() > 0)
            var myModal = new bootstrap.Modal(document.getElementById('searchResultModal'));
            myModal.show();
        @endif
    </script>
@endsection

<style>
    .disease-card {
        cursor: pointer;
        width: 100%;
    }

    .disease-card input {
        display: none;
    }

    .disease-card .card {
        transition: all 0.2s ease;
        border: 2px solid #e9ecef;
    }

    .disease-card:hover .card {
        border-color: #0d6efd;
        transform: translateY(-2px);
    }

    .disease-card input:checked+.card {
        border-color: #0d6efd;
        background-color: #f0f6ff;
    }

    /* Giới hạn mô tả 3 dòng */
    .disease-card .card-text {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 3.6em;
    }
</style>
