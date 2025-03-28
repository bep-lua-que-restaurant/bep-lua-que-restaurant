{{-- <div class="container">
    <h2>Đăng nhập</h2>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password">Mật khẩu</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Đăng nhập</button>
    </form>
</div> --}}

{{-- @if (session('errors'))
    <div class="alert alert-danger">
        {{ session('errors')->first() }}
    </div>
@endif --}}
<!DOCTYPE html>
<html lang="en" class="h-100">


<!-- Mirrored from uena.dexignzone.com/laravel/demo/page-login by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 12 Jan 2025 11:05:14 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="3pvIDLSX29wd150Hxi6MALt7d8F4BtSQooZ74xzA">
    <meta name="description" content="Some description for the page" />

    <title> Đăng nhập</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="public/images/favicon.png">
    <link href="{{ asset('admin') }}/css/style.css" rel="stylesheet">


</head>

<body class="vh-100">
    <div class="authincation h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-lg-6 col-md-8">
                    <div class="authincation-content">
                        <div class="row no-gutters">
                            <div class="col-xl-12">
                                <div class="auth-form">
                                    <div class="text-center mb-3">
                                        <a href="index.html"><img src="public/images/logo-full.png" alt=""></a>
                                    </div>
                                    <div style="display: flex; justify-content: center;">
                                        <img src="{{ url('storage/logo.jpg') }}" alt="Logo"
                                            style="width: 60%; height: auto; display: block; ; 
                                        border-radius: 10px;">

                                    </div>

                                    <form action="{{ route('login') }}" method="POST">
                                        @csrf
                                        {{-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> --}}

                                        <div class="form-group">
                                            <label class="mb-1"><strong>Email</strong></label>
                                            <input type="email" class="form-control" name="email"
                                                value="{{ old('email') }}">
                                            @error('email')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label class="mb-1"><strong>Mật khẩu</strong></label>
                                            <input type="password" class="form-control" name="password">
                                            @error('password')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-row d-flex justify-content-between mt-4 mb-2">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox ml-1">
                                                    <input type="checkbox" class="custom-control-input"
                                                        id="basic_checkbox_1">

                                                </div>
                                            </div>

                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary btn-block">Đăng nhập</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!--**********************************
        Scripts
    ***********************************-->


    <script src="public/vendor/global/global.min.js" type="text/javascript"></script>
    <script src="public/vendor/bootstrap-select/dist/js/bootstrap-select.min.js" type="text/javascript"></script>
    <script src="public/js/custom.min.js" type="text/javascript"></script>
    <script src="public/js/deznav-init.js" type="text/javascript"></script>
    <script src="public/js/demo.js" type="text/javascript"></script>
    <script src="public/js/styleSwitcher.js" type="text/javascript"></script>


</body>

<!-- Mirrored from uena.dexignzone.com/laravel/demo/page-login by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 12 Jan 2025 11:05:17 GMT -->

</html>
