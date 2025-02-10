<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
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

<div class="container-fluid">
    {{-- <div class="row page-titles mx-0">
        <div class="col-sm-6 p-md-0">
            <div class="welcome-text">
                <h4>Hi, welcome back!</h4>
            </div>
        </div>
        <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Thu ngân</a></li>
            </ol>
        </div>
    </div> --}}
    <!-- row -->
    <div class="row">
        <div class="col-lg-12 my-4">
            <div class="d-flex justify-content-between align-items-center">
                <!-- Ô tìm kiếm + Nút tìm kiếm (Sát lề trái) -->
                <div class="input-group">
                    <input type="text" id="search-name" class="form-control border-0" placeholder="Tìm kiếm ...">
                </div>

                <div>
                    <select id="statusFilter" class="btn btn-primary btn-sm">
                        <option value="Tất cả">Tất cả</option>
                        <option value="trong">Bàn trống</option>
                        <option value="co_khach">Bàn có khách</option>
                        <option value="da_dat_truoc">Đã đặt trước</option>

                    </select>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Danh sách</h4>
                    <div class="btn-group">

                        <button id="btn-ban-an" class="btn btn-sm btn-primary active">
                            <i class="fa fa-utensils"></i> Bàn ăn
                        </button>

                        <button id="btn-thuc-don" class="btn btn-sm btn-secondary">
                            <i class="fa fa-ellipsis-v"></i> Thực đơn
                        </button>

                    </div>
                </div>

                <div class="card-body" id="list-container">
                    @include('gdnhanvien.thungan.body-list')
                </div>

            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Hóa đơn</h4>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="hoaDonTabs">
                        <!-- Tab hóa đơn sẽ được thêm vào đây -->
                    </ul>
                    <div class="tab-content" id="hoaDonContent">
                        <!-- Nội dung của các hóa đơn sẽ được thêm vào đây -->
                    </div>
                </div>
            </div>
        </div>
    </div>

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
