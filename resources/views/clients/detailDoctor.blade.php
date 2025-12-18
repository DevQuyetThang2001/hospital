@extends('clients.layouts.app')

@section('content')
    <style>
        .doctor-header {
            background: linear-gradient(to right, #e8f3ff, #ffffff);
            border-radius: 24px;
            padding: 40px;
            display: flex;
            gap: 40px;
            align-items: center;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.07);
        }

        .doctor-avatar {
            width: 320px;
            height: 380px;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
        }

        .doctor-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: top;
        }

        .doctor-info h2 {
            font-size: 32px;
            font-weight: 800;
        }

        .doctor-info span.badge {
            padding: 10px 14px;
            font-size: 14px;
            border-radius: 12px;
        }

        .profile-section {
            margin-top: 50px;
            padding: 30px;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
        }

        .schedule-card {
            background: #ffffff;
            padding: 16px 20px;
            border-radius: 14px;
            margin-bottom: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .btn-appointment {
            background: #0d6efd;
            color: #fff;
            padding: 14px 28px;
            font-size: 18px;
            border-radius: 50px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-appointment:hover {
            background: #005ad3;
            transform: translateY(-2px);
        }
    </style>

    <div class="container py-5">

        <!-- HEADER -->
        <div class="doctor-header">
            <div class="doctor-avatar">
                @if (!empty($doctor->user->image))
                    <img src="{{ asset('storage/' . $doctor->user->image) }}" alt="Doctor Photo">
                @else
                    <img src="https://placehold.co/600x400?text=Doctor" alt="">
                @endif
            </div>

            <div class="doctor-info">
                <h2>{{ $doctor->name }}</h2>

                <span class="badge bg-primary mb-3">
                    {{ $doctor->department->name ?? 'Chưa có chuyên khoa' }}
                </span>

                <p class="text-muted mb-2"><strong>🏅 Kinh nghiệm:</strong>
                    {{ $doctor->experience_years ?? 'Chưa cập nhật' }} năm
                </p>
                <p class="text-muted mb-2"><strong>🏅 Chứng chì hành nghề:</strong>
                    {{ $doctor->license_number ?? 'Chưa cập nhật' }}
                </p>

                

                <p class="text-muted mb-4"><strong>🎓 Học vị:</strong> {{ $doctor->specialization ?? 'Chưa cập nhật' }}</p>

                <a href="{{ route('doctor.appointment', $doctor->id) }}" class="btn btn-appointment">
                    Đặt lịch khám ngay
                </a>
            </div>
        </div>

        <!-- MÔ TẢ -->
        <div class="profile-section mt-5">
            <h4 class="fw-bold mb-3">📝 Giới thiệu bác sĩ</h4>
            <p class="text-muted" style="font-size: 16px; line-height: 1.7;">
                {!! $doctor->description !!}
            </p>
        </div>

    
    </div>
@endsection
