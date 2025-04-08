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
    <link rel="stylesheet" href="{{ asset('admin') }}/css/thungan.css">
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
                            <li>
                                <button class="dropdown-item text-warning" onclick="showOrders()" type="button">
                                    <i class="fas fa-list me-2"></i> Đơn đặt bàn
                                </button>
                            </li>

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

    <!-- Modal -->
    <div class="modal fade" id="ordersModal" tabindex="-1" aria-labelledby="ordersModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ordersModalLabel">Danh sách đơn đặt trước</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-fix">
                    <!-- Bộ lọc nâng cao -->
                    <div class="row g-2 mb-3">
                        <!-- Ô tìm kiếm -->
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="searchInput"
                                placeholder="Nhập mã đặt bàn, tên khách hoặc thời gian đến">
                        </div>

                        <!-- Lọc theo khoảng ngày -->
                        <div class="col-md-3">
                            <input type="date" class="form-control" id="fromDate" placeholder="Từ ngày">
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control" id="toDate" placeholder="Đến ngày">
                        </div>
                    </div>

                    <!-- Bảng danh sách đơn đặt trước -->
                    <table class="table-dat-ban table table-bordered table-sm text-center">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Mã đặt bàn</th>
                                <th>Tên khách</th>
                                <th>Số người</th>
                                <th>Bàn</th>
                                <th>Thời gian đến</th>
                            </tr>
                        </thead>
                        <tbody id="ordersList">
                            <tr>
                                <td colspan="6">Đang tải...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
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
    <script src="{{ asset('admin/js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('js/jquery-3.6.4.min.js') }}"></script>
    <script src="{{ asset('admin') }}/js/tachBan.js" type="text/javascript"></script>
    <script src="{{ asset('admin') }}/js/donDatBan.js" type="text/javascript"></script>
    <script src="{{ asset('admin') }}/js/ghiChuMonAn.js" type="text/javascript"></script>
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
