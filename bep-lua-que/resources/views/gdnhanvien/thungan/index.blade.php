
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
    </style>
</head>

<body>
    <header>
        <div class="container-fluid">
            <!-- Tìm kiếm + Lọc trạng thái -->
            <section class="row my-4">
                <div class="col-lg-6">
                    <div class="input-group">
                        <input type="text" id="search-name" class="form-control border-0 "
                            placeholder="Tìm kiếm ...">
                    </div>
                </div>
                <div class="col-lg-6 d-flex justify-content-end">
                    <select id="statusFilter" class="btn btn-primary btn-sm ">

                        <option value="Tất cả">Tất cả</option>
                        <option value="trong">Bàn trống</option>
                        <option value="co_khach">Bàn có khách</option>
                        <option value="da_dat_truoc">Đã đặt trước</option>

                    </select>
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
            <!-- Card Bàn ăn / Thực đơn -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Danh sách</h4>
                        </div>

                        <div class="card-body" id="list-container">
                            @include('gdnhanvien.thungan.body-list')
                        </div>

                    </div>
                </div>

                <div class="card-body" id="list-container">
                    @include('gdnhanvien.thungan.body-list')
                </div>

            </div>


            <!-- Card Hóa đơn -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Hóa đơn</h4>
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

    <!-- Script -->

    <script src="{{ asset('admin') }}/vendor/bootstrap-select/dist/js/bootstrap-select.min.js" type="text/javascript">
    </script>

    <script src="{{ asset('admin') }}/vendor/peity/jquery.peity.min.js" type="text/javascript"></script>
    <script src="{{ asset('admin') }}/vendor/apexchart/apexchart.js" type="text/javascript"></script>
    <script src="{{ asset('admin') }}/js/dashboard/dashboard-1.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @vite('resources/js/public.js')
    <script>
        var apiUrl = "{{ route('thungan.getBanAn') }}";

        var apiUrlThucDon = "{{ route('thungan.getThucDon') }}";

        var apiUrlHoaDon = "{{ route('thungan.getHoaDon') }}";

        var apiUrlChiTietHoaDon = "{{ route('thungan.getChiTietHoaDon') }}";

        document.getElementById('btn-ban-an').addEventListener('click', function() {
            fetch('{{ route('thungan.getBanAn') }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('list-container').innerHTML = data.html;
                })
                .catch(error => console.error('Lỗi:', error));
        });

        document.getElementById('btn-thuc-don').addEventListener('click', function() {
            fetch('{{ route('thungan.getThucDon') }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('list-container').innerHTML = data.html;
                })
                .catch(error => console.error('Lỗi:', error));
        });

        $(document).ready(function() {
            let currentType = 'ban'; // Mặc định hiển thị danh sách bàn

            function fetchFilteredData() {
                let searchQuery = $('#search-name').val();
                let statusFilter = $('#statusFilter').val();
                $('#list-container').html('<div class="text-center">Đang tải dữ liệu...</div>');

                $.ajax({
                    url: currentType === 'ban' ? "{{ route('thungan.getBanAn') }}" :
                        "{{ route('thungan.getThucDon') }}",
                    method: "GET",
                    data: {
                        ten: searchQuery,
                        statusFilter: currentType === 'ban' ? statusFilter : null
                    },
                    success: function(response) {
                        $('#list-container').html(response.html);
                    },
                    error: function(xhr) {
                        console.error("Lỗi khi tải dữ liệu:", xhr);
                    }
                });
            }

            $('#search-name').on('input', function() {
                fetchFilteredData();
            });

            $('#statusFilter').on('change', function() {
                if (currentType === 'ban') {
                    fetchFilteredData();
                }
            });

            $('#btn-ban-an').on('click', function() {
                currentType = 'ban';
                $('#statusFilter').parent().show();
                fetchFilteredData();
            });

            $('#btn-thuc-don').on('click', function() {
                currentType = 'menu';
                $('#statusFilter').parent().hide();
                fetchFilteredData();
            });

            fetchFilteredData();
        });
    </script>
</body>


</div>


<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</script>
<script>
    document.getElementById('btn-ban-an').addEventListener('click', function() {
        fetch('{{ route('thungan.getBanAn') }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('list-container').innerHTML = data.html;
            })
            .catch(error => console.error('Lỗi:', error));
    });

    document.getElementById('btn-thuc-don').addEventListener('click', function() {
        fetch('{{ route('thungan.getThucDon') }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('list-container').innerHTML = data.html;
            })
            .catch(error => console.error('Lỗi:', error));
    });
    $(document).ready(function() {
        let currentType = 'ban'; // Mặc định hiển thị danh sách bàn

        function fetchFilteredData() {
            let searchQuery = $('#search-name').val();
            let statusFilter = $('#statusFilter').val();
            $('#list-container').html('<div class="text-center">Đang tải dữ liệu...</div>');

            $.ajax({
                url: currentType === 'ban' ? "{{ route('thungan.getBanAn') }}" :
                    "{{ route('thungan.getThucDon') }}",
                method: "GET",
                data: {
                    ten: searchQuery,
                    statusFilter: currentType === 'ban' ? statusFilter : null
                },
                success: function(response) {
                    $('#list-container').html(response.html);
                },
                error: function(xhr) {
                    console.error("Lỗi khi tải dữ liệu:", xhr);
                }
            });
        }

        $('#search-name').on('input', function() {
            fetchFilteredData();
        });

        $('#statusFilter').on('change', function() {
            if (currentType === 'ban') {
                fetchFilteredData();
            }
        });

        $('#btn-ban-an').on('click', function() {
            currentType = 'ban';
            $('#statusFilter').parent().show();
            fetchFilteredData();
        });

        $('#btn-thuc-don').on('click', function() {
            currentType = 'menu';
            $('#statusFilter').parent().hide();
            fetchFilteredData();
        });

        fetchFilteredData();
    });

    //
</script>
