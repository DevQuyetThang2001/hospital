
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Đăng Nhập</title>
  <!-- base:css -->
  <link rel="stylesheet" href="{{asset('admin/vendors/typicons.font/font/typicons.css')}}">
  <link rel="stylesheet" href="{{asset('admin/vendors/css/vendor.bundle.base.css')}}">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{asset('admin/css/vertical-layout-light/style.css')}}">
  <!-- endinject -->
  <link rel="shortcut icon" href="{{asset('admin/images/favicon.png')}}" />
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo text-center">
                <img src="{{asset('admin/images/logoBenhVien.png')}}" alt="logo">
              </div>    
              @if (session('msg'))
                  <div class="text-danger">{{ session('msg') }}</div>
              @endif

              <form class="pt-3" method="POST" action="{{route('auth.login')}}">
                @csrf
                <div class="form-group">
                  <input type="email" value="{{old('email')}}" class="form-control form-control-lg" id="exampleInputEmail1" name="email" placeholder="Email">
                  @error('email')
                      <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-group">
                  <input type="password" value="{{old('password')}}" class="form-control form-control-lg" id="exampleInputPassword1" name="password" placeholder="Mật khẩu">
                  @error('password')
                      <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>
                <div class="mt-3">
                  <button type="submit" class="btn btn-block btn-info  btn-lg font-weight-medium auth-form-btn">Đăng nhập</button>
                </div>
                <div class="my-2 d-flex justify-content-between align-items-center">
                  <a href="#" class="auth-link text-black">Quên mật khẩu ?</a>
                </div>
                <div class="text-center mt-4 font-weight-light">
                  Bạn chưa có tài khoản ? <a href="{{route('auth.register')}}" class="text-primary">Tạo tài khoản</a>
                </div>

                <a class="text-center d-block mt-2" href="{{route('home')}}">Quay về trang chủ</a>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- base:js -->
  <script src="{{asset('admin/vendors/js/vendor.bundle.base.js')}}"></script>
  <!-- endinject -->
  <!-- inject:js -->
  <script src="{{asset('admin/js/off-canvas.js')}}"></script>
  <script src="{{asset('admin/js/hoverable-collapse.js')}}"></script>
  <script src="{{asset('admin/js/template.js')}}"></script>
  <script src="{{asset('admin/js/settings.js')}}"></script>
  <script src="{{asset('admin/js/todolist.js')}}"></script>
  <!-- endinject -->
</body>

</html>
