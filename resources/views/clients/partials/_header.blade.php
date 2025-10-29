<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Bệnh Viện Hồng Phúc</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Favicon -->
    <link href="{{asset('client/img/logoBenhVien.png')}}" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@400;700&family=Roboto:wght@400;700&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{asset('client/lib/owlcarousel/assets/owl.carousel.min.css')}}" rel="stylesheet">
    <link href="{{asset('client/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css')}}" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{asset('client/css/bootstrap.min.css')}}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{asset('client/css/style.css')}}" rel="stylesheet">
</head>

<body>
    <!-- Topbar Start -->
    <div class="container-fluid py-2 border-bottom d-none d-lg-block">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-center text-lg-start mb-2 mb-lg-0">
                    <div class="d-inline-flex align-items-center">
                        <a class="text-decoration-none text-body pe-3" href=""><i class="bi bi-telephone me-2"></i>+012
                            345 6789</a>
                        <span class="text-body">|</span>
                        <a class="text-decoration-none text-body px-3" href=""><i
                                class="bi bi-envelope me-2"></i>hongphuc@contact.com</a>
                    </div>
                </div>
                <div class="col-md-6 text-center text-lg-end">
                    <div class="d-inline-flex align-items-center">
                        @if(!Auth::check())
                            <a href="{{route('login')}}">Đăng nhập</a>

                        @else
                            <span class="text-body">Xin chào, {{ Auth::user()->name }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <div class="container-fluid sticky-top bg-white shadow-sm">
        <div class="container">
            <nav class="navbar navbar-expand-lg bg-white navbar-light py-3 py-lg-0">
                <a href="/" class="navbar-brand">
                    <h1 class="m-0 text-uppercase text-primary"><i class="fa fa-clinic-medical me-2"></i>HỒNG PHÚC</h1>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto py-0">
                        <a href="index.html" class="nav-item nav-link active">Trang chủ</a>
                        <a href="{{route('client.hospital.info')}}" class="nav-item nav-link">Thông tin</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Dịch vụ</a>
                            <div class="dropdown-menu m-0">
                                <a href="{{route('client.blog.list')}}" class="dropdown-item">Cẩm năng y khoa</a>
                                <a href="{{route('client.hospital.feedback')}}" class="dropdown-item">Đánh giá</a>
                                <a href="team.html" class="dropdown-item">Đội ngũ bác sĩ</a>
                                <a href="{{route('appointment')}}" class="dropdown-item">Đặt lịch khám</a>
                                <a href="search.html" class="dropdown-item">Tìm kiếm</a>
                            </div>
                        </div>
                        <a href="{{ route('client.hospital.contact') }}" class="nav-item nav-link">Liên hệ</a>

                        @if (Auth::check())
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Tài khoản</a>
                                <div class="dropdown-menu m-0">
                                    <a href="blog.html" class="dropdown-item">Thông tin tài khoản</a>
                                    <a href="{{route('client.hospital.listAppointment')}}" class="dropdown-item">Lịch khám của bạn</a>
                                    <a href="{{route('auth.logout')}}" class="dropdown-item">Đăng xuất</a>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </nav>
        </div>
    </div>
    <!-- Navbar End -->