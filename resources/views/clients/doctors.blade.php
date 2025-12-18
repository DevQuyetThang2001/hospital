@extends('clients.layouts.app')

@section('content')
    <style>
        .doctor-card {
            overflow: hidden;
            border-radius: 16px;
            background: #fff;
            transition: 0.3s ease;
            position: relative;
        }

        .doctor-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
        }

        .doctor-img-wrapper {
            height: 230px;
            overflow: hidden;
            border-radius: 14px;
        }

        .doctor-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: 0.4s ease;
            border-radius: 14px;
        }

        .doctor-card:hover .doctor-img {
            transform: scale(1.07);
        }

        .doctor-info {
            padding: 18px 15px 25px;
            text-align: center;
        }

        .doctor-name {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 5px;
            color: #003366;
        }

        .doctor-spec {
            color: #6c757d;
            font-size: 0.95rem;
            margin-bottom: 15px;
        }

        .btn-view {
            border-radius: 30px;
            padding: 6px 18px;
            font-size: 0.85rem;
        }

        .doctor-img-wrapper {
            height: 300px;
            /* Tăng chiều cao ảnh để không cắt mặt */
            overflow: hidden;
            border-radius: 16px;
        }

        .doctor-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: top;
            /* Giữ khuôn mặt ở phần trên */
            transition: 0.4s ease;
        }

        .doctor-card:hover .doctor-img {
            transform: scale(1.06);
        }
    </style>

    <div class="container py-5">

        <div class="text-center mb-5">
            <h2 class="fw-bold">👨‍⚕️ Đội Ngũ Bác Sĩ Của Chúng Tôi</h2>
            <p class="text-muted">Đội ngũ bác sĩ với chuyên môn cao – tận tâm với bệnh nhân – luôn sẵn sàng hỗ trợ.</p>
        </div>

        <div class="row g-4">

            @foreach ($doctors as $doctor)
                <div class="col-md-4 col-lg-3">

                    <div class="doctor-card shadow-sm">
                        <div class="doctor-img-wrapper">
                            @if (!empty($doctor->user->image))
                                <img src="{{ asset('storage/' . $doctor->user->image) }}" class="doctor-img" alt="">
                            @else
                                <img src="https://placehold.co/600x400?text=No+Image" class="doctor-img" alt="">
                            @endif
                        </div>

                        <div class="doctor-info">
                            <h5 class="doctor-name text-center">{{ $doctor->name }}</h5>
                            <p class="doctor-spec">{{ $doctor->department->name ?? 'Chuyên khoa chưa cập nhật' }}</p>
                            <a href="{{ route('client.doctors.show', $doctor->id) }}"
                                class="btn btn-primary btn-sm btn-view">Xem chi tiết</a>
                        </div>
                    </div>

                </div>
            @endforeach

        </div>

    </div>
@endsection
