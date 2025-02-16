@extends('layouts.admin')

@section('title')
	Dashboard
@endsection
@section('content')
    <div class="container">
        <!-- Kết quả bán hàng hôm nay -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <h5>Đơn đã xong</h5>
                        <p class="text-danger" style="font-size: 24px; font-weight: bold;">3,886,000</p>
                        <small class="text-muted">Hôm qua: 6,055,000</small>
                    </div>
                    <div class="col-md-4 text-center">
                        <h5>Đơn đang phục vụ</h5>
                        <p class="text-success" style="font-size: 24px; font-weight: bold;">0</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <h5>Khách hàng</h5>
                        <p class="text-danger" style="font-size: 24px; font-weight: bold;">3</p>
                        <small class="text-muted">Hôm qua: 10</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-bold">DOANH SỐ <span id="timeRange">HÔM NAY</span></h5>
                <h5 class="text-primary fw-bold">
                    <i class="bi bi-info-circle"></i>
                    <span id="totalSales">15,489,000</span>
                </h5>

                <!-- Bộ lọc thời gian -->
                <div class="d-flex justify-content-between align-items-center">
                    <ul class="nav nav-tabs" id="chartTabs">
                        <li class="nav-item">
                            <a class="nav-link active" data-range="day" href="#">Theo ngày</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-range="hour" href="#">Theo giờ</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-range="weekday" href="#">Theo thứ</a>
                        </li>
                    </ul>

                    <select class="form-select w-auto" id="dateRange" style="background-color: #f8f9fa; border: 1px solid #ced4da; padding: 5px 10px; border-radius: 5px; font-size: 14px;">
                        <option value="today" selected>Hôm nay</option>
                        <option value="yesterday">Hôm qua</option>
                        <option value="last7days">7 ngày qua</option>
                        <option value="thisMonth">Tháng này</option>
                        <option value="lastMonth">Tháng trước</option>
                    </select>
                </div>

                <!-- Biểu đồ -->
                <canvas id="salesChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Nhúng Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const ctx = document.getElementById('salesChart').getContext('2d');

            // Dữ liệu mặc định cho 7 ngày qua
            let salesData = {
                labels: ["06", "07", "08", "09", "10", "11", "12"],
                datasets: [{
                    label: "Chi nhánh trung tâm",
                    backgroundColor: "#FF720D",
                    data: [1.2, 0.3, 3.5, 0.8, 1.1, 6.2, 4.5]
                }]
            };

            let salesChart = new Chart(ctx, {
                type: 'bar',
                data: salesData,
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) { return value + ' tr'; }
                            }
                        }
                    }
                }
            });

            // Cập nhật dữ liệu khi thay đổi khoảng thời gian
            document.getElementById('dateRange').addEventListener('change', function () {
                let range = this.value;
                let newLabels = [], newData = [];

                if (range === 'today') {
                    newLabels = ["00h", "06h", "12h", "18h"];
                    newData = [2.1, 3.5, 5.0, 2.8];
                    document.getElementById('timeRange').innerText = "HÔM NAY";
                } else if (range === 'yesterday') {
                    newLabels = ["00h", "06h", "12h", "18h"];
                    newData = [1.8, 2.9, 4.3, 3.1];
                    document.getElementById('timeRange').innerText = "HÔM QUA";
                } else if (range === 'last7days') {
                    newLabels = ["06", "07", "08", "09", "10", "11", "12"];
                    newData = [1.2, 0.3, 3.5, 0.8, 1.1, 6.2, 4.5];
                    document.getElementById('timeRange').innerText = "7 NGÀY QUA";
                } else if (range === 'thisMonth') {
                    newLabels = ["01", "05", "10", "15", "20", "25", "30"];
                    newData = [4.2, 3.8, 5.1, 2.9, 6.0, 7.5, 4.8];
                    document.getElementById('timeRange').innerText = "THÁNG NÀY";
                } else if (range === 'lastMonth') {
                    newLabels = ["01", "05", "10", "15", "20", "25", "30"];
                    newData = [3.5, 2.9, 4.7, 3.3, 5.8, 6.9, 4.2];
                    document.getElementById('timeRange').innerText = "THÁNG TRƯỚC";
                }

                // Cập nhật biểu đồ
                salesChart.data.labels = newLabels;
                salesChart.data.datasets[0].data = newData;
                salesChart.update();
            });

            // Xử lý chuyển tab
            document.querySelectorAll("#chartTabs .nav-link").forEach(tab => {
                tab.addEventListener("click", function (e) {
                    e.preventDefault();
                    document.querySelector("#chartTabs .active").classList.remove("active");
                    this.classList.add("active");

                    // Thay đổi dữ liệu khi chọn loại thống kê
                    let selectedRange = this.getAttribute("data-range");
                    let newLabels = [], newData = [];

                    if (selectedRange === "hour") {
                        newLabels = ["00h", "06h", "12h", "18h"];
                        newData = [2.1, 3.5, 5.0, 2.8];
                    } else if (selectedRange === "weekday") {
                        newLabels = ["Thứ 2", "Thứ 3", "Thứ 4", "Thứ 5", "Thứ 6", "Thứ 7", "CN"];
                        newData = [3.2, 4.5, 2.8, 5.1, 3.9, 6.2, 4.7];
                    } else {
                        newLabels = ["06", "07", "08", "09", "10", "11", "12"];
                        newData = [1.2, 0.3, 3.5, 0.8, 1.1, 6.2, 4.5];
                    }

                    salesChart.data.labels = newLabels;
                    salesChart.data.datasets[0].data = newData;
                    salesChart.update();
                });
            });
        });
    </script>
@endsection

