{{-- @extends('layouts.admin') --}}
@extends('admin.datban.layout')

@section('title')
    Lễ tân
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="container mt-4">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <script>
        setInterval(() => {
            fetch('/api/update-datban')
                .then(response => response.json())
                .then(data => console.log(data.message));
        }, 60000); // 60000ms = 1 phút
    </script> --}}
    @vite('resources/js/datban.js')

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
@endsection
