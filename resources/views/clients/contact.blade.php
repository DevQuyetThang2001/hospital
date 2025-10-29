@extends('clients.layouts.app')


@section('content')

    <div class="container-fluid bg-primary py-5 mb-5">
        <div class="container py-5 text-center">
            <h1 class="display-3 text-white mb-3">Liên Hệ</h1>
            <p class="text-white-50">Chúng tôi luôn sẵn sàng hỗ trợ bạn 24/7</p>
        </div>
    </div>

    <!-- Contact Info -->
    <div class="container py-5">

        @if (session('success'))
            <div class="d-flex justify-content-center mb-4">
                <div class="alert alert-success text-center w-50 shadow-sm">
                    {{ session('success') }}
                </div>
            </div>
        @endif
        <div class="row g-5">
            <div class="col-lg-4">
                <div class="bg-light rounded p-4 h-100 shadow-sm">
                    <h4 class="mb-4">Thông Tin Liên Hệ</h4>
                    <p><i class="fa fa-map-marker-alt text-primary me-3"></i>123 Đường Sức Khỏe, TP. Hà Nội</p>
                    <p><i class="fa fa-phone-alt text-primary me-3"></i>012 345 6789</p>
                    <p><i class="fa fa-envelope text-primary me-3"></i>hongphuc@contact.com</p>
                    <p><i class="fa fa-clock text-primary me-3"></i>Thứ 2 - CN: 7:00 - 21:00</p>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="col-lg-8">
                <div class="bg-light rounded p-4 shadow-sm">
                    <h4 class="mb-4">Gửi Thông Tin Liên Hệ</h4>
                    <form action="{{ route('client.hospital.contact.send') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <input type="text" name="name" class="form-control" placeholder="Họ và tên">
                                @error('name')
                                    <div class="text-danger text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <input type="email" name="email" class="form-control" placeholder="Email">
                                @error('email')
                                    <div class="text-danger text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="phone" class="form-control" placeholder="Số điện thoại">
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="subject" class="form-control" placeholder="Chủ đề">
                            </div>
                            <div class="col-12">
                                <textarea name="message" rows="5" class="form-control" placeholder="Nội dung"></textarea>
                                @error('message')
                                    <div class="text-danger text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 text-end">
                                <button class="btn btn-primary px-4 py-2" type="submit">Gửi Tin Nhắn</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Google Map -->
    <div class="container-fluid px-0 mt-5">
        <iframe class="w-100" height="400" frameborder="0" style="border:0;" allowfullscreen
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3723.806616025986!2d105.78499321535987!3d21.0402984926721!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab5c8d4b3c43%3A0x66bcbdfba7f30f!2zTmjDoCBow6BuZyBC4bq_biB2aeG7h24gUXXDoW4g4buZIE5hbQ!5e0!3m2!1svi!2s!4v1638980462156!5m2!1svi!2s">
        </iframe>
    </div>





@endsection