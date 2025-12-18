@extends('clients.layouts.app')

@section('content')

    @include('clients.partials._banner')
    <!-- About Start -->
    <div class="container-fluid py-5 bg-light">
        <div class="container">
            <div class="row gx-5 align-items-center">
                <!-- Image Section -->
                <div class="col-lg-5 mb-4 mb-lg-0">
                    <div class="position-relative overflow-hidden rounded" style="min-height: 500px;">
                        <img src="{{ asset('client/img/about.jpg') }}" class="w-100 h-100 position-absolute"
                            style="object-fit: cover;" alt="About Us">
                    </div>
                </div>

                <!-- Text & Info Section -->
                <div class="col-lg-7">
                    <div class="mb-4">
                        <h5 class="d-inline-block text-primary text-uppercase border-bottom border-3">Thông tin về chúng tôi
                        </h5>
                        <h1 class="display-5 fw-bold mt-3">Luôn dành sự quan tâm tốt nhất cho bạn và gia đình bạn</h1>
                    </div>
                    <p class="mb-4 text-muted">
                        Chúng tôi luôn đặt sức khỏe của bạn lên hàng đầu. Đội ngũ bác sĩ chuyên môn cao, dịch vụ cấp cứu
                        nhanh chóng và các phòng xét nghiệm hiện đại luôn sẵn sàng phục vụ bạn.
                    </p>

                    <div class="row g-3">
                        <div class="col-6 col-sm-3">
                            <div class="bg-white text-center rounded shadow-sm py-4 hover-shadow">
                                <i class="fa fa-user-md fa-3x text-primary mb-3"></i>
                                <h6 class="mb-0">Bác sĩ<br><small class="text-primary">Chuyên môn cao</small></h6>
                            </div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div class="bg-white text-center rounded shadow-sm py-4 hover-shadow">
                                <i class="fa fa-procedures fa-3x text-primary mb-3"></i>
                                <h6 class="mb-0">Dịch vụ<br><small class="text-primary">Cấp cứu 24/7</small></h6>
                            </div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div class="bg-white text-center rounded shadow-sm py-4 hover-shadow">
                                <i class="fa fa-microscope fa-3x text-primary mb-3"></i>
                                <h6 class="mb-0">Xét nghiệm<br><small class="text-primary">Chính xác</small></h6>
                            </div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div class="bg-white text-center rounded shadow-sm py-4 hover-shadow">
                                <i class="fa fa-ambulance fa-3x text-primary mb-3"></i>
                                <h6 class="mb-0">Xe cứu thương<br><small class="text-primary">Miễn phí</small></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->

    <style>
        .hover-shadow:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }
    </style>


    <!-- Search & Appointment Section Start -->
    <div class="container-fluid bg-primary my-5 py-5">
        <div class="container py-5">
            <div class="row gx-5 align-items-center">

                <!-- Left Text Section -->
                <div class="col-lg-6 mb-5 mb-lg-0 text-white">
                    <div class="mb-4">
                        <h5 class="d-inline-block text-white text-uppercase border-bottom border-5">Dịch vụ hỗ trợ</h5>
                        <h1 class="display-5 fw-bold mt-3">Tìm bác sĩ & Đặt lịch khám</h1>
                    </div>

                    <p class="mb-5">
                        Chọn chức năng phù hợp để bắt đầu: tìm bác sĩ theo triệu chứng hoặc đặt lịch khám trực tuyến.
                    </p>

                    <a class="btn btn-dark rounded-pill py-3 px-5 me-3" href="{{ route('client.doctors.searchByDisease') }}">
                        🔍 Tìm bác sĩ theo triệu chứng
                    </a>

                    <a class="btn btn-outline-light rounded-pill py-3 px-5" href="{{ route('appointment') }}">
                        🗓 Đặt lịch khám
                    </a>
                </div>

                <!-- Right Static Illustration -->
                <div class="col-lg-6 text-center">
                    <img src="https://cdn-icons-png.flaticon.com/512/2966/2966482.png" alt="doctor search" class="img-fluid"
                        style="max-width: 65%;">
                </div>

            </div>
        </div>
    </div>
    <!-- Search & Appointment Section End -->

    <!-- Optional CSS for better hover effect -->
    <style>
        .btn:hover {
            transition: 0.3s;
            transform: translateY(-2px);
        }
    </style>





    <!-- Team Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="text-center mx-auto mb-5" style="max-width: 500px;">
                <h5 class="d-inline-block text-primary text-uppercase border-bottom border-5">Bác sĩ của chúng tôi</h5>
                <h1 class="display-4">Đội ngũ chăm sóc sức khỏe có trình độ</h1>
            </div>
            <div class="owl-carousel team-carousel position-relative">
                @if ($doctors->count() > 0)
                    @foreach ($doctors as $item)
                        <div class="team-item">
                            <div class="row g-0 bg-light rounded overflow-hidden">
                                <div class="col-12 col-sm-5 h-100">
                                    @if (!empty($item->user->image))
                                        <img class="img-fluid h-100" src="{{ asset('storage/' . $item->user->image) }}"
                                            alt="image" style="object-fit: cover;">
                                    @else
                                        <img src="https://placehold.co/600x400?text=No+Image" class="img-fluid h-100"
                                            style="object-fit: cover;">
                                    @endif
                                </div>
                                <div class="col-12 col-sm-7 h-100 d-flex flex-column">
                                    <div class="mt-auto p-4">
                                        <h3>{{ $item->user->name }}</h3>
                                        <h6 class="fw-normal text-primary mb-2">{{ $item->department->name }}</h6>
                                        <h7 class="fw-normal text-secondary mb-2">Đã có {{ $item->experience_years }} năm
                                            kinh nghiệm
                                        </h7>
                                        <p class="m-0">Chứng chỉ hành nghề {{ $item->license_number }}</p>
                                    </div>
                                    <div class="d-flex mt-auto border-top p-4">
                                        <a class="btn btn-lg btn-primary btn-lg-square rounded-circle me-3"
                                            href="#"><i class="fab fa-twitter"></i></a>
                                        <a class="btn btn-lg btn-primary btn-lg-square rounded-circle me-3"
                                            href="#"><i class="fab fa-facebook-f"></i></a>
                                        <a class="btn btn-lg btn-primary btn-lg-square rounded-circle" href="#"><i
                                                class="fab fa-linkedin-in"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif


            </div>
        </div>
    </div>
    <!-- Team End -->





    <!-- Testimonial Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="text-center mx-auto mb-5" style="max-width: 500px;">
                <h5 class="d-inline-block text-primary text-uppercase border-bottom border-5">Mức độ tin cậy</h5>
                <h1 class="display-4">Bệnh nhân nói gì về dịch vụ của chúng tôi</h1>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="owl-carousel testimonial-carousel">
                        @if ($feedbacks->count() > 0)
                            @foreach ($feedbacks as $item)
                                <div class="testimonial-item text-center">
                                    <div class="position-relative mb-5">
                                        <img class="img-fluid rounded-circle mx-auto"
                                            src="{{ asset('storage/' . $item->patient->user->image) }}" alt="">
                                        <div class="position-absolute top-100 start-50 translate-middle d-flex align-items-center justify-content-center bg-white rounded-circle"
                                            style="width: 60px; height: 60px;">
                                            <i class="fa fa-quote-left fa-2x text-primary"></i>
                                        </div>
                                    </div>
                                    <p class="fs-4 fw-normal">{{ $item->text }}</p>
                                    <hr class="w-25 mx-auto">
                                    <h3>{{ $item->patient->user->name }}</h3>
                                    <h6 class="fw-normal text-primary mb-3">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $item->rating)
                                                <i class="bi bi-star-fill text-warning"></i>
                                            @else
                                                <i class="bi bi-star text-muted"></i>
                                            @endif
                                        @endfor
                                    </h6>
                                </div>
                            @endforeach
                        @endif

                        <div class="testimonial-item text-center">
                            <div class="position-relative mb-5">
                                <img class="img-fluid rounded-circle mx-auto" src="img/testimonial-2.jpg" alt="">
                                <div class="position-absolute top-100 start-50 translate-middle d-flex align-items-center justify-content-center bg-white rounded-circle"
                                    style="width: 60px; height: 60px;">
                                    <i class="fa fa-quote-left fa-2x text-primary"></i>
                                </div>
                            </div>
                            <p class="fs-4 fw-normal">Dolores sed duo clita tempor justo dolor et stet lorem kasd labore
                                dolore lorem ipsum. At lorem lorem magna ut et, nonumy et labore et tempor diam tempor
                                erat. Erat dolor rebum sit ipsum.</p>
                            <hr class="w-25 mx-auto">
                            <h3>Patient Name</h3>
                            <h6 class="fw-normal text-primary mb-3">Profession</h6>
                        </div>
                        <div class="testimonial-item text-center">
                            <div class="position-relative mb-5">
                                <img class="img-fluid rounded-circle mx-auto" src="img/testimonial-3.jpg" alt="">
                                <div class="position-absolute top-100 start-50 translate-middle d-flex align-items-center justify-content-center bg-white rounded-circle"
                                    style="width: 60px; height: 60px;">
                                    <i class="fa fa-quote-left fa-2x text-primary"></i>
                                </div>
                            </div>
                            <p class="fs-4 fw-normal">Dolores sed duo clita tempor justo dolor et stet lorem kasd labore
                                dolore lorem ipsum. At lorem lorem magna ut et, nonumy et labore et tempor diam tempor
                                erat. Erat dolor rebum sit ipsum.</p>
                            <hr class="w-25 mx-auto">
                            <h3>Patient Name</h3>
                            <h6 class="fw-normal text-primary mb-3">Profession</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Testimonial End -->


    <!-- Blog Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="text-center mx-auto mb-5" style="max-width: 500px;">
                <h5 class="d-inline-block text-primary text-uppercase border-bottom border-5">Kiến Thức Y Khoa</h5>
                <p class="text-center">Kiến Thức Y Khoa" là nơi tổng hợp các thông tin y học chính xác, cập nhật từ đội ngũ
                    bác sĩ chuyên môn của chúng tôi. Cung cấp những kiến thức bổ ích về chăm sóc sức khỏe, phòng bệnh và
                    điều trị giúp cộng đồng chủ động bảo vệ sức khỏe bản thân và gia đình.</p>
            </div>
            <div class="row g-5">
                @if ($blogs->count() > 0)
                    @foreach ($blogs as $item)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="blog-card">
                                @if ($item->images->count() > 0)
                                    <img src="{{ asset('storage/' . $item->images->first()->image) }}"
                                        alt="{{ $item->title }}" class="blog-image">
                                @else
                                    <img src="{{ asset('images/no-image.jpg') }}" alt="No image" class="blog-image">
                                @endif

                                <div class="p-4">
                                    <a class="h4 d-block mb-3 text-dark fw-semibold"
                                        href="{{ route('client.blog.show', $item->slug) }}">
                                        {{ $item->title }}
                                    </a>
                                </div>

                                <div class="blog-meta">
                                    <div class="d-flex align-items-center">
                                        @if (!empty($item->doctor->user->image))
                                            <img src="{{ asset('storage/' . $item->doctor->user->image) }}"
                                                class="rounded-circle me-2" width="28" height="28"
                                                style="object-fit: cover; object-position: center;">
                                        @endif
                                        <small>{{ $item->doctor->user->name ?? 'Ẩn danh' }}</small>
                                    </div>
                                    <div>
                                        <small class="me-3"><i class="far fa-eye text-primary me-1"></i>12345</small>
                                        <small><i class="far fa-comment text-primary me-1"></i>123</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

                {{--
                <div class="col-xl-4 col-lg-6">
                    <div class="bg-light rounded overflow-hidden">
                        <img class="img-fluid w-100" src="img/blog-2.jpg" alt="">
                        <div class="p-4">
                            <a class="h3 d-block mb-3" href="">Dolor clita vero elitr sea stet dolor justo diam</a>
                            <p class="m-0">Dolor lorem eos dolor duo et eirmod sea. Dolor sit magna
                                rebum clita rebum dolor stet amet justo</p>
                        </div>
                        <div class="d-flex justify-content-between border-top p-4">
                            <div class="d-flex align-items-center">
                                <img class="rounded-circle me-2" src="img/user.jpg" width="25" height="25" alt="">
                                <small>John Doe</small>
                            </div>
                            <div class="d-flex align-items-center">
                                <small class="ms-3"><i class="far fa-eye text-primary me-1"></i>12345</small>
                                <small class="ms-3"><i class="far fa-comment text-primary me-1"></i>123</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6">
                    <div class="bg-light rounded overflow-hidden">
                        <img class="img-fluid w-100" src="img/blog-3.jpg" alt="">
                        <div class="p-4">
                            <a class="h3 d-block mb-3" href="">Dolor clita vero elitr sea stet dolor justo diam</a>
                            <p class="m-0">Dolor lorem eos dolor duo et eirmod sea. Dolor sit magna
                                rebum clita rebum dolor stet amet justo</p>
                        </div>
                        <div class="d-flex justify-content-between border-top p-4">
                            <div class="d-flex align-items-center">
                                <img class="rounded-circle me-2" src="img/user.jpg" width="25" height="25" alt="">
                                <small>John Doe</small>
                            </div>
                            <div class="d-flex align-items-center">
                                <small class="ms-3"><i class="far fa-eye text-primary me-1"></i>12345</small>
                                <small class="ms-3"><i class="far fa-comment text-primary me-1"></i>123</small>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
    <!-- Blog End -->
@endsection
