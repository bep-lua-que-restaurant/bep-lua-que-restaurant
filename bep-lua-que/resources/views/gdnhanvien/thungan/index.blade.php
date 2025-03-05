<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Thu ngân</title>

    <!-- Import Bootstrap mới nhất -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('admin') }}/images/favicon.png">
    <link href="{{ asset('admin') }}/vendor/chartist/css/chartist.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin') }}/vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('admin') }}/css/style.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #004080;
            color: #fff;
        }

        .cardd:hover {
            border-color: #FF6347 !important;
            box-shadow: 0 4px 10px rgba(255, 99, 71, 0.3);
            cursor: pointer;
        }

        .add-mon-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            padding: 0;
            font-size: 14px;
            border: none;
            transition: 0.3s;
        }

        .add-btn:hover {
            background-color: #007bff;
        }

        .filter-room-group label {
            cursor: pointer;
            padding: 8px 12px;
            border: 2px solid transparent;
            border-radius: 2px;
            background: #f8f9fa;
            transition: all 0.3s ease-in-out;
            display: inline-block;
        }

        .filter-room-group input[type="radio"] {
            display: none;
        }

        .filter-room-group input[type="radio"]:checked+label {
            border-color: #e49b07;
            background: #e49b07;
            color: white;
            font-weight: bold;
            box-shadow: 0px 0px 10px rgba(0, 123, 255, 0.5);
        }

        .empty-invoice {
            padding: 50px 0;
            text-align: center;
            font-style: italic;
            border: 2px dashed #ccc;
            /* Viền nét đứt */
            border-radius: 10px;
            /* Bo góc nhẹ nhàng */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            font-size: 16px;
            width: 100%;
        }
    </style>
</head>

<body>
    <header>
        <audio id="ding-sound" src="{{ asset('sounds/ding.mp3') }}" preload="auto"></audio>

        <div class="container-fluid">
            <section class="row my-4">
                <div class="col-12 text-center mb-3">
                    <h2 class="fw-bold text-uppercase text-light ">Thu Ngân</h2>
                </div>
            </section>


            <!-- Tìm kiếm + Lọc trạng thái + Dropdown -->
            <section class="row">
                <div class="col-lg-6 d-flex align-items-center">
                    <input type="text" id="search-name" class="form-control form-control-sm me-2 border-primary"
                        style="width: 200px; height: 40px;">
                    <select id="statusFilter" class="btn btn-primary btn-sm " style="width: 100px;">
                        <option value="Tất cả">Tất cả</option>
                        <option value="trong">Bàn trống</option>
                        <option value="co_khach">Bàn có khách</option>
                    </select>
                </div>
                <div class="col-lg-6 d-flex justify-content-end">
                    <div class="dropdown">
                        <button class="btn btn-primary btn-sm  d-flex align-items-center justify-content-center"
                            type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-bars"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item text-primary" href="/"><i
                                        class="fas fa-tachometer-alt me-2"></i> Dashboard</a></li>
                            <li><a class="dropdown-item text-primary" href="bep"><i
                                        class="fas fa-utensils me-2"></i> Bếp</a></li>
                            <li><a class="dropdown-item text-danger" href="dat-ban"><i
                                        class="fas fa-concierge-bell me-2"></i> Lễ tân</a></li>
                        </ul>
                    </div>
                </div>

            </section>
            <!-- Danh sách Bàn ăn / Thực đơn -->
            <section class="row">
                <div class="col-lg-12 my-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="btn-group">
                            <button id="btn-ban-an" class="btn btn-sm btn-primary active ">
                                <i class="fa fa-utensils"></i> Bàn ăn
                            </button>

                            <button id="btn-thuc-don" class="btn btn-sm btn-secondary ">
                                <i class="fa fa-ellipsis-v"></i> Thực đơn
                            </button>
                        </div>

                    </div>

                </div>
            </section>
        </div>
    </header>

    <main>
        <section class="container-fluid">
            <div class="row">
                <!-- Card Bàn ăn / Thực đơn -->
                <div class="col-lg-7 col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Danh sách</h4>
                            <div class="ms-auto filter-room-group">
                                <label>
                                    <input type="radio" name="filter-room" value="" checked>
                                    Tất cả
                                </label>
                                @foreach ($phongBans as $phong)
                                    <input type="radio" id="phong_{{ $phong->id }}" name="filter-room"
                                        value="{{ $phong->id }}">
                                    <label for="phong_{{ $phong->id }}">{{ $phong->ten_phong_an }}</label>
                                @endforeach
                            </div>

                            @if (isset($danhMucs))
                                <div class="ms-auto">
                                    <label>
                                        <input type="radio" name="filter-category" value="" checked> Tất cả
                                    </label>
                                    @foreach ($danhMucs as $danhMuc)
                                        <input type="radio" id="danhmuc_{{ $danhMuc->id }}" name="filter-category"
                                            value="{{ $danhMuc->id }}">
                                        <label for="danhmuc_{{ $danhMuc->id }}">{{ $danhMuc->ten_danh_muc }}</label>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div class="card-body" id="list-container">
                            @include('gdnhanvien.thungan.body-list')
                        </div>
                    </div>
                </div>

                <!-- Card Hóa đơn -->
                <div class="col-lg-5 col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Hóa đơn <h5 class="text-success" id="ten-ban">Bàn </h5>
                            </h4>
                        </div>
                        <div id="hoaDon-body" class="card-body">
                            @include('gdnhanvien.thungan.hoa-don-list')
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>


    <footer>
        <!-- Footer content (nếu cần) -->
    </footer>

    {{-- toast --}}
    <div class="position-fixed top-0 start-0 p-3" style="z-index: 1050">
        <div id="toastMessage" class="toast align-items-center text-bg-success border-0" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <!-- Nội dung thông báo -->
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>


    <!-- Script -->

    <script src="{{ asset('admin') }}/vendor/bootstrap-select/dist/js/bootstrap-select.min.js" type="text/javascript">
    </script>

    <script src="{{ asset('admin') }}/vendor/peity/jquery.peity.min.js" type="text/javascript"></script>
    <script src="{{ asset('admin') }}/vendor/apexchart/apexchart.js" type="text/javascript"></script>
    <script src="{{ asset('admin') }}/js/dashboard/dashboard-1.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
        var apiUrl = "{{ route('thungan.getBanAn') }}";

        var apiUrlThucDon = "{{ route('thungan.getThucDon') }}";

        var apiUrlHoaDon = "{{ route('thungan.getHoaDon') }}";

        var apiUrlChiTietHoaDon = "{{ route('thungan.getChiTietHoaDon') }}";

        var apiUrlGetBanAn = "{{ route('thungan.getBanAn') }}";

        var apiUrlGetThucDon = "{{ route('thungan.getThucDon') }}";

        var apiUrlThongBaoBep = "{{ route('thungan.thongBaoBep') }}";

        var apiUrlXoaMon = "{{ route('thungan.deleteMonAn') }}";

        var dingSoundUrl = "{{ asset('sounds/ding.mp3') }}";
    </script>
    @vite('resources/js/public.js')
    @vite('resources/js/thungan.js')

</body>

</html>
