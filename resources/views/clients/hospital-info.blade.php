@extends('clients.layouts.app')

@section('title', 'Giới thiệu Bệnh viện')

@section('content')
    {{-- Banner --}}
    <section class="bg-primary text-white text-center py-5 mb-5">
        <div class="container">
            <h1 class="display-5 fw-bold">Giới thiệu về Bệnh viện Hồng Phúc</h1>
            <p class="lead mt-3">Nơi chăm sóc sức khỏe toàn diện, tận tâm và hiện đại</p>
        </div>
    </section>

    {{-- Thông tin chung --}}
    <section class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-6 p-5">
                <img src="{{asset('client/img/logoBenhVien.png')}}" class="img-fluid rounded shadow p-3" alt="Bệnh viện">
            </div>
            <div class="col-lg-6">
                <h2 class="mb-3 text-primary">Lịch sử hình thành</h2>
                <p>Bệnh viện Hồng Phúc được thành lập năm 2010, với sứ mệnh mang đến dịch vụ chăm sóc y tế chất
                    lượng cao cho cộng đồng.
                    Trải qua hơn 10 năm phát triển, bệnh viện đã trở thành địa chỉ tin cậy của hàng triệu bệnh nhân trên cả
                    nước.</p>
                <p><strong>Sứ mệnh:</strong> Cung cấp dịch vụ y tế toàn diện, chuyên nghiệp, lấy người bệnh làm trung tâm.
                </p>
                <p><strong>Tầm nhìn:</strong> Trở thành bệnh viện hàng đầu Việt Nam trong lĩnh vực y học hiện đại và chăm
                    sóc sức khỏe cộng đồng.</p>
            </div>
        </div>
    </section>

    {{-- Đội ngũ bác sĩ --}}
    <section class="bg-light py-5">
        <div class="container text-center">
            <h2 class="text-primary mb-4">Đội ngũ y bác sĩ chuyên môn cao</h2>
            <div class="row g-4">
                @foreach ($doctors as $doctor)
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm">
                            <img src="{{ asset('storage/' . $doctor->user->image) }}" class="card-img-top blog-image"
                                alt="{{ $doctor->user->name }}">
                            <div class="card-body">
                                <h5 class="card-title text-primary">{{ $doctor->user->name }}</h5>
                                <p class="text-muted">{{ $doctor->specialization }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Dịch vụ --}}
    <section class="container py-5">
        <h2 class="text-center text-primary mb-4">Dịch vụ nổi bật</h2>
        <div class="row text-center g-4">
            <div class="col-md-3">
                <div class="p-4 border rounded shadow-sm">
                    <i class="fa fa-heartbeat fa-3x text-danger mb-3"></i>
                    <h5>Khám tổng quát</h5>
                    <p>Kiểm tra sức khỏe toàn diện định kỳ giúp phát hiện sớm các vấn đề tiềm ẩn.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-4 border rounded shadow-sm">
                    <i class="fa fa-stethoscope fa-3x text-success mb-3"></i>
                    <h5>Khám chuyên khoa</h5>
                    <p>Đội ngũ chuyên gia đầu ngành trong nhiều lĩnh vực y khoa.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-4 border rounded shadow-sm">
                    <i class="fa fa-user-md fa-3x text-info mb-3"></i>
                    <h5>Phẫu thuật - điều trị</h5>
                    <p>Trang thiết bị hiện đại, quy trình chuẩn quốc tế.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-4 border rounded shadow-sm">
                    <i class="fa fa-ambulance fa-3x text-warning mb-3"></i>
                    <h5>Cấp cứu 24/7</h5>
                    <p>Luôn sẵn sàng hỗ trợ bệnh nhân mọi lúc, mọi nơi.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Liên hệ / bản đồ --}}
    <section class="bg-primary text-white py-5">
        <div class="container text-center">
            <h2 class="mb-3">Liên hệ với chúng tôi</h2>
            <p><i class="fa fa-map-marker-alt me-2"></i>123 Nguyễn Văn Cừ, Quận 5, TP. Hồ Chí Minh</p>
            <p><i class="fa fa-phone-alt me-2"></i>(028) 1234 5678 &nbsp;|&nbsp; <i
                    class="fa fa-envelope me-2"></i>contact@benhvienviet.vn</p>
            <iframe class="mt-3 rounded" src="https://www.google.com/maps/embed?pb=!1m18!..." width="100%" height="300"
                style="border:0;" allowfullscreen loading="lazy"></iframe>
        </div>
    </section>
@endsection