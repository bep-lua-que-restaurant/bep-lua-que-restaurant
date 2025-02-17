@extends('layouts.admin')

@section('title')
    Danh mục Bàn Ăn
@endsection

@section('content')
    <h1>Ban An</h1>
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Hi, welcome back!</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Danh mục bàn ăn</a></li>
                </ol>
            </div>
        </div>
        <!-- row -->
        <div class="row">
            {{-- @include('admin.filter') --}}
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Danh sách</h4>



                        <div class="btn-group">
                            <!-- Nút Thêm mới -->
                            <a href="{{ route('ban-an.create') }}" class="btn btn-sm btn-primary">
                                <i class="fa fa-plus"></i> Thêm mới
                            </a>

                            <!-- Nút Nhập file (Mở Modal) -->
                            <a href="#" class="btn btn-sm btn-secondary" data-toggle="modal"
                                data-target="#importExcelModal">
                                <i class="fa fa-upload"></i> Nhập file
                            </a>

                            <!-- Nút Xuất file -->
                            <a href="{{ route('ban-an.export') }}" class="btn btn-sm btn-success">
                                <i class="fa fa-download"></i> Xuất file
                            </a>

                            <!-- Nút Danh sách -->
                            <a href="{{ route('ban-an.index') }}" class="btn btn-sm btn-info">
                                <i class="fa fa-list"></i> Danh sách
                            </a>
                        </div>

                        <!-- Modal Nhập File -->
                        <div class="modal fade" id="importExcelModal" tabindex="-1" role="dialog"
                            aria-labelledby="importExcelModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="importExcelModalLabel">Nhập dữ liệu từ Excel</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('ban-an.import') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group">
                                                <label for="file">Chọn file Excel (.xlsx, .xls)</label>
                                                <input type="file" name="file" id="file" class="form-control"
                                                    required>
                                                @if ($errors->has('file'))
                                                    <small class="text-danger">*{{ $errors->first('file') }}</small>
                                                @endif
                                            </div>
                                            <div class="text-right">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Hủy</button>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fa fa-upload"></i> Nhập dữ liệu
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>




                    <div class="container mt-4">
                        <!-- Tab Header -->
                        <div class="p-4 d-flex">
                            <div id="tab-table-list" class="tab-button" onclick="switchTab('table-list')">Danh sách bàn ăn
                            </div>
                            <div id="tab-booked-list" class="tab-button" onclick="switchTab('booked-list')">Danh sách bàn đã
                                đặt</div>
                        </div>

                        <!-- Nội dung tab -->
                        <div id="table-list-section">
                            <div id="table-list-content" class="row">
                                <!-- Nội dung danh sách bàn sẽ được xử lý tại đây -->
                                <div class="w-4/5 ">
                                    {{-- <h2 class="text-xl font-bold">Phòng/Bàn & Mốc Giờ Đặt</h2> --}}

                                    <div class="row" style="max-width: 500px">
                                        <div class="col p-3 tab-item" id="ngay-tab" onclick="changeTab('ngay')">
                                            Ngày
                                        </div>
                                        <div class="col p-3 tab-item" id="tuan-tab" onclick="changeTab('tuan')">
                                            Tuần
                                        </div>
                                        <div class="col p-3 tab-item" id="thang-tab" onclick="changeTab('thang')">
                                            Tháng
                                        </div>
                                    </div>

                                    <!-- Nội dung hiển thị cho "Ngày" -->
                                    <div id="ngay-content" class="tab-content">
                                        <div id="ngay-tabs">
                                            <!-- Các ô div cho các ngày trong tuần sẽ được chèn vào đây -->
                                            @include('admin.datban.ngay')
                                        </div>
                                    </div>

                                    <!-- Nội dung hiển thị cho "Tuần" -->
                                    <div id="tuan-content" class="tab-content" style="display: none">
                                        <div id="tuan-tabs">
                                            <!-- Các ô div cho các ngày trong tuần sẽ được chèn vào đây -->
                                            @include('admin.datban.tuan')

                                        </div>
                                    </div>

                                    <!-- Nội dung hiển thị cho "Tháng" -->
                                    <div id="thang-content" class="tab-content" style="display: none">
                                        <div id="thang-tabs">
                                            <!-- Các ô div cho các ngày trong tháng sẽ được chèn vào đây -->
                                            @include('admin.datban.thang')
                                        </div>
                                    </div>

                                    @include('admin.datban.formdat')



                                    <script>
                                        // Thay đổi nội dung khi chọn tab
                                        function changeTab(tab) {
                                            // Ẩn tất cả các nội dung
                                            document.getElementById("ngay-content").style.display = "none";
                                            document.getElementById("tuan-content").style.display = "none";
                                            document.getElementById("thang-content").style.display = "none";

                                            // Loại bỏ lớp active của tất cả các tab
                                            const tabs = document.querySelectorAll(".tab-item");
                                            tabs.forEach((tab) => tab.classList.remove("active"));

                                            // Hiển thị nội dung tương ứng với tab đã chọn
                                            if (tab === "ngay") {
                                                document.getElementById("ngay-content").style.display = "block";
                                                document.getElementById("ngay-tab").classList.add("active");
                                                renderWeekDates(); // Render ngày trong tuần
                                            } else if (tab === "tuan") {
                                                document.getElementById("tuan-content").style.display = "block";
                                                document.getElementById("tuan-tab").classList.add("active");
                                                renderWeekDates(); // Render ngày trong tuần cho tuần
                                            } else if (tab === "thang") {
                                                document.getElementById("thang-content").style.display = "block";
                                                document.getElementById("thang-tab").classList.add("active");
                                                renderMonthDates(); // Render ngày trong tháng
                                            }
                                        }
                                    </script>
                                </div>
                            </div>
                        </div>

                        <div id="booked-list-section">
                            <h2>Danh sách bàn đã được đặt</h2>

                            <!-- Bộ lọc tìm kiếm -->
                            <div class="row d-flex p-3">
                                <div class="col-9">
                                    <input type="text" name="searchBanDat" id="searchBanDat" class="form-control"
                                        placeholder="Tìm theo họ tên hoặc số điện thoại">
                                </div>
                                <div class="col-3">
                                    <select name="trang_thai" id="trang_thai" class="form-control">
                                        <option value="">Tất cả trạng thái</option>
                                        <option value="dang_xu_ly">Đang xử lý</option>
                                        <option value="xac_nhan">Xác nhận</option>
                                        <option value="da_huy">Đã hủy</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Bảng dữ liệu -->
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Thời Gian Đến</th>
                                        <th>Họ Tên</th>
                                        <th>Số Điện Thoại</th>
                                        <th>Số Người</th>
                                        <th>Danh Sách Bàn</th>
                                        <th>Trạng Thái</th>
                                        <th>Mô Tả</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody">
                                    <!-- Dữ liệu sẽ được load ở đây bằng AJAX -->
                                </tbody>
                            </table>

                        </div>
                    </div>

                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                    <script>
                        $(document).ready(function() {
                            function loadData() {
                                let search = $('#searchBanDat').val();
                                let trang_thai = $('#trang_thai').val();

                                $.ajax({
                                    url: "{{ route('datban.filter') }}",
                                    method: "GET",
                                    data: {
                                        search: search,
                                        trang_thai: trang_thai
                                    },
                                    success: function(response) {
                                        let rows = '';
                                        if (response.length > 0) {
                                            $.each(response, function(index, datban) {
                                                rows += `
                            <tr>
                                <td>${datban.thoi_gian_den}</td>
                                <td>${datban.ho_ten}</td>
                                <td>${datban.so_dien_thoai}</td>
                                <td>${datban.so_nguoi}</td>
                                <td>${datban.danh_sach_ban}</td>
                                <td>${datban.trang_thai}</td>
                                <td>${datban.mo_ta}</td>
                            </tr>
                        `;
                                            });
                                        } else {
                                            rows =
                                                `<tr><td colspan="7" class="text-center">Không tìm thấy dữ liệu</td></tr>`;
                                        }
                                        $('#tableBody').html(rows);
                                    }
                                });
                            }

                            // Load dữ liệu ban đầu
                            loadData();

                            // Tìm kiếm khi nhập vào ô input
                            $('#searchBanDat').on('keyup', function() {
                                loadData();
                            });

                            // Lọc theo trạng thái khi thay đổi select
                            $('#trang_thai').on('change', function() {
                                loadData();
                            });
                        });
                    </script>

                    <script>
                        function switchTab(tabName) {
                            // Ẩn tất cả các tab
                            document.getElementById("table-list-section").style.display = "none";
                            document.getElementById("booked-list-section").style.display = "none";

                            // Loại bỏ class active khỏi tất cả các tab
                            document.querySelectorAll(".tab-button").forEach(item => item.classList.remove("active"));

                            // Hiển thị tab tương ứng và thêm class active
                            if (tabName === "table-list") {
                                document.getElementById("table-list-section").style.display = "block";
                                document.getElementById("tab-table-list").classList.add("active");
                            } else if (tabName === "booked-list") {
                                document.getElementById("booked-list-section").style.display = "block";
                                document.getElementById("tab-booked-list").classList.add("active");
                            }
                        }

                        // Mặc định hiển thị tab "Danh sách bàn" khi tải trang
                        document.addEventListener("DOMContentLoaded", function() {
                            switchTab('table-list');
                        });
                    </script>


                </div>
            </div>

        </div>
    </div>


    <style>
        .tab-header {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .tab-button {
            padding: 10px 20px;
            cursor: pointer;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin: 0 5px;
            background-color: #f1f1f1;
            transition: 0.3s;
        }

        .tab-button.active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }

        .tab-section {
            display: none;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .tab-item {
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s;
        }

        .tab-item.active {
            color: #007bff;
            /* Màu xanh lam khi tab được chọn */
            font-weight: bold;
        }

        .tab-content {
            margin-top: 20px;
        }

        .date-box {
            padding: 10px;
            margin: 5px;
            border: 1px solid #007bff;
            text-align: center;
            display: inline-block;
            width: 80px;
            cursor: pointer;
        }

        .date-box.active {
            background-color: #007bff;
            color: white;
        }
    </style>




    @include('admin.search-srcip')
    <!-- Hiển thị phân trang -->
    {{-- {{ $banPhong->links('pagination::bootstrap-5') }} --}}
@endsection
