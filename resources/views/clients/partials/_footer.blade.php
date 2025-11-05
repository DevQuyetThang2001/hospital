<!-- Footer Start -->
<footer class="footer bg-primary text-light pt-5 mt-5">
    <div class="container pb-4">
        <div class="row g-4">
            <!-- Logo & Giới thiệu -->
            <div class="col-lg-4 col-md-6">
                <h4 class="text-white text-uppercase mb-3">Bệnh viện Hồng Phúc</h4>
                <p class="small">
                    Chúng tôi mang đến dịch vụ khám chữa bệnh tận tâm, chuyên nghiệp và chất lượng hàng đầu.
                    Đặt lịch nhanh chóng, chăm sóc chu đáo – vì sức khỏe của bạn là ưu tiên của chúng tôi.
                </p>
                <div class="d-flex align-items-center mt-3">
                    <a class="btn btn-outline-light btn-sm rounded-circle me-2" href="#"><i class="fab fa-facebook-f"></i></a>
                    <a class="btn btn-outline-light btn-sm rounded-circle me-2" href="#"><i class="fab fa-youtube"></i></a>
                    <a class="btn btn-outline-light btn-sm rounded-circle me-2" href="#"><i class="fab fa-tiktok"></i></a>
                    <a class="btn btn-outline-light btn-sm rounded-circle" href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>

            <!-- Thông tin liên hệ -->
            <div class="col-lg-4 col-md-6">
                <h5 class="text-white text-uppercase mb-3 border-bottom border-light pb-2">Liên hệ</h5>
                <p class="mb-2"><i class="fa fa-map-marker-alt me-2"></i> 215 Nguyễn Trãi, TP. Biên Hòa, Đồng Nai</p>
                <p class="mb-2"><i class="fa fa-envelope me-2"></i> hotro@hongphuchospital.vn</p>
                <p class="mb-2"><i class="fa fa-phone-alt me-2"></i> (0251) 3 123 456</p>
                <p class="mb-0"><i class="fa fa-clock me-2"></i> Thứ 2 - CN: 7:00 - 21:00</p>
            </div>

            <!-- Liên kết nhanh -->
            <div class="col-lg-4 col-md-6">
                <h5 class="text-white text-uppercase mb-3 border-bottom border-light pb-2">Liên kết nhanh</h5>
                <div class="d-flex flex-column">
                    <a class="text-light mb-2" href="{{ route('home') }}"><i class="fa fa-angle-right me-2"></i>Trang chủ</a>
                    <a class="text-light mb-2" href="{{ route('client.hospital.info') }}"><i class="fa fa-angle-right me-2"></i>Giới thiệu</a>
                    <a class="text-light mb-2" href=""><i class="fa fa-angle-right me-2"></i>Dịch vụ</a>
                    <a class="text-light mb-2" href=""><i class="fa fa-angle-right me-2"></i>Đội ngũ bác sĩ</a>
                    <a class="text-light mb-2" href="{{ route('client.hospital.contact') }}"><i class="fa fa-angle-right me-2"></i>Liên hệ</a>
                    <a class="text-light" href="{{ route('appointment') }}"><i class="fa fa-angle-right me-2"></i>Đặt lịch khám</a>
                </div>
            </div>
        </div>
    </div>

    <div class="border-top border-light text-center py-3 small bg-dark">
        © 2025 <strong>Bệnh viện Hồng Phúc</strong> | Thiết kế bởi <a class="text-light" href="#">Đội phát triển hệ thống</a>
    </div>

    <!-- Nút Back to Top -->
    <a href="#" class="btn btn-lg btn-light btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
</footer>
<!-- Footer End -->
<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{asset('client/lib/easing/easing.min.js')}}"></script>
<script src="{{asset('client/lib/waypoints/waypoints.min.js')}}"></script>
<script src="{{asset('client/lib/owlcarousel/owl.carousel.min.js')}}"></script>
<script src="{{asset('client/lib/tempusdominus/js/moment.min.js')}}"></script>
<script src="{{asset('client/lib/tempusdominus/js/moment-timezone.min.js')}}"></script>
<script src="{{asset('client/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js')}}"></script>

<!-- Template Javascript -->
<script src="{{asset('client/js/main.js')}}"></script>