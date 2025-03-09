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
                        <h5>Đơn đã xong hôm nay</h5>
                        <p id="totalRevenueToday" class="text-danger" style="font-size: 24px; font-weight: bold;">{{ number_format($totalRevenueToday, 0, ',', '.') }} VND</p>
                        <small class="text-muted">Hôm qua: <span id="totalRevenueYesterday">{{ number_format($totalRevenueYesterday, 0, ',', '.') }} VND</span></small>
                    </div>
                    <div class="col-md-4 text-center">
                        <h5>Đơn đang phục vụ</h5>
                        <p class="text-success" style="font-size: 24px; font-weight: bold;">0</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <h5>Khách hàng hôm nay</h5>
                        <p id="customersToday" class="text-danger" style="font-size: 24px; font-weight: bold;">{{ $customersToday }}</p>
                        <small class="text-muted">Hôm qua: <span id="customersYesterday">{{ $customersYesterday }}</span></small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-bold">DOANH SỐ <span id="timeRange">
                    @if ($filterType == 'year') TRONG NĂM @elseif ($filterType == 'month') TRONG THÁNG @else TRONG NGÀY @endif
                </span></h5>

                <h5 class="text-primary fw-bold">
                    <i class="bi bi-info-circle"></i>
                    <span id="totalSales">{{ number_format(array_sum($data), 0, ',', '.') }} VND</span>
                </h5>

                <!-- Bộ lọc thời gian -->
                <form id="filterForm">
                    <div class="form-group d-flex align-items-center">
                        <select name="filterType" id="filterType" class="form-select mr-2" style="width: 135px">
                            <option value="year" {{ $filterType == 'year' ? 'selected' : '' }}>Theo Năm</option>
                            <option value="month" {{ $filterType == 'month' ? 'selected' : '' }}>Theo Tháng</option>
                            <option value="day" {{ $filterType == 'day' ? 'selected' : '' }}>Theo Ngày</option>
                        </select>
                        <button type="submit" class="btn btn-primary">Lọc</button>
                    </div>
                </form>


                <!-- Biểu đồ -->
                <canvas id="thongKeChart" height="100"></canvas>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title fw-bold">So sánh Doanh số</h5>
                <div class="row d-flex justify-content-center">
                    <div class="col-md-3 text-center">
                        <h6>Tháng này vs Tháng trước</h6>
                        <canvas id="pieChartMonth"></canvas>
                        <p id="monthPercentage" class="fw-bold"></p>
                    </div>
                    <div class="col-md-3 text-center">
                        <h6>Tuần này vs Tuần trước</h6>
                        <canvas id="pieChartWeek"></canvas>
                        <p id="weekPercentage" class="fw-bold"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Biểu đồ số lượng khách -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-bold">SỐ LƯỢNG KHÁCH <span id="timeRange2">
                    @if ($filterType2 == 'year') TRONG NĂM @elseif ($filterType2 == 'month') TRONG THÁNG @else TRONG NGÀY @endif
                </span></h5>

                <h5 class="text-primary fw-bold">
                    <i class="bi bi-info-circle"></i>
                    <span id="soKhach"></span>
                </h5>

                <!-- Bộ lọc thời gian -->
                <form id="filterForm2">
                    <div class="form-group d-flex align-items-center">
                        <select name="filterType2" id="filterType2" class="form-select mr-2" style="width: 135px">
                            <option value="year" {{ $filterType2 == 'year' ? 'selected' : '' }}>Theo Năm</option>
                            <option value="month" {{ $filterType2 == 'month' ? 'selected' : '' }}>Theo Tháng</option>
                            <option value="day" {{ $filterType2 == 'day' ? 'selected' : '' }}>Theo Ngày</option>
                        </select>
                        <button type="submit" class="btn btn-primary">Lọc</button>
                    </div>
                </form>


                <!-- Biểu đồ -->
                <canvas id="soLuongKhachChart" height="100"></canvas>
            </div>
        </div>
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title fw-bold">So sánh Lượng Khách</h5>
                <div class="row d-flex justify-content-center">
                    <div class="col-md-3 text-center">
                        <h6>Tháng này vs Tháng trước</h6>
                        <canvas id="pieChartMonth2"></canvas>
                        <p id="monthPercentage2" class="fw-bold"></p>
                    </div>
                    <div class="col-md-3 text-center">
                        <h6>Tuần này vs Tuần trước</h6>
                        <canvas id="pieChartWeek2"></canvas>
                        <p id="weekPercentage2" class="fw-bold"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Nhúng Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- script AJAX của phần kết quả bán hàng hôm nay -->
    <script>
        // AJAX phần kết quả bán hàng
        function loadTodayStats() {
            $.ajax({
                url: "{{ route('dashboard') }}",
                method: 'GET',
                success: function(response) {
                    // Cập nhật tổng tiền đơn đã xong
                    $('#totalRevenueToday').text(response.totalRevenueToday);
                    $('#totalRevenueYesterday').text(response.totalRevenueYesterday);

                    // Cập nhật tổng số khách hàng
                    $('#customersToday').text(response.customersToday);
                    $('#customersYesterday').text(response.customersYesterday);
                }
            });
        }

        // Gọi AJAX ngay khi trang load
        $(document).ready(function() {
            loadTodayStats();
            setInterval(loadTodayStats, 1000); // Cập nhật mỗi 1 giây
        });
    </script>

    <!-- script của biểu đồ thống kê doanh số và biểu đồ so sánh doanh số -->
    <script>
        $(document).ready(function () {
            let barChart, pieChartMonth, pieChartWeek;

            function updateChart(labels, data) {
                if (barChart) {
                    barChart.destroy();
                }
                let ctx = document.getElementById('thongKeChart').getContext('2d');
                barChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Doanh thu (VND)',
                            data: data,
                            backgroundColor: 'rgba(54, 162, 235, 0.6)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: { beginAtZero: true }
                        }
                    }
                });
            }

            function updatePieChart(chart, canvasId, data, labels, percentageId, percentage) {
                if (chart) {
                    chart.destroy();
                }
                let ctx = document.getElementById(canvasId).getContext('2d');
                chart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: data,
                            backgroundColor: ['rgba(75, 192, 192, 0.6)', 'rgba(255, 99, 132, 0.6)']
                        }]
                    },
                    options: {
                        responsive: true
                    }
                });

                // Hiển thị phần trăm chênh lệch
                let changeText = percentage > 0
                    ? `Tăng ${percentage}% so với kỳ trước 🔼`
                    : percentage < 0
                        ? `Giảm ${Math.abs(percentage)}% so với kỳ trước 🔽`
                        : `Không thay đổi 📊`;

                document.getElementById(percentageId).innerText = changeText;
                return chart;
            }

            $('#filterForm').on('submit', function (e) {
                e.preventDefault();
                let filterType = $('#filterType').val();

                $.ajax({
                    url: "{{ route('dashboard') }}",
                    type: "GET",
                    data: { filterType: filterType },
                    success: function (response) {
                        $('#totalSales').text(response.totalSales);
                        updateChart(response.labels, response.data);

                        // Cập nhật nội dung bộ lọc
                        let timeRangeText = response.filterType === 'year' ? 'TRONG NĂM' :
                            response.filterType === 'month' ? 'TRONG THÁNG' :
                                'TRONG NGÀY';
                        $('#timeRange').text(timeRangeText);

                        // Cập nhật biểu đồ tròn
                        pieChartMonth = updatePieChart(
                            pieChartMonth, 'pieChartMonth',
                            [response.monthComparison.currentMonth, response.monthComparison.lastMonth],
                            ['Tháng này', 'Tháng trước'],
                            'monthPercentage', response.monthComparison.percentage
                        );

                        pieChartWeek = updatePieChart(
                            pieChartWeek, 'pieChartWeek',
                            [response.weekComparison.currentWeek, response.weekComparison.lastWeek],
                            ['Tuần này', 'Tuần trước'],
                            'weekPercentage', response.weekComparison.percentage
                        );
                    },
                    error: function () {
                        alert('Lỗi tải dữ liệu, vui lòng thử lại.');
                    }
                });
            });

            // Khởi tạo biểu đồ ban đầu
            updateChart(@json($labels), @json($data));

            pieChartMonth = updatePieChart(
                null, 'pieChartMonth',
                [@json($currentMonthRevenue), @json($lastMonthRevenue)],
                ['Tháng này', 'Tháng trước'],
                'monthPercentage', @json($monthPercentage)
            );

            pieChartWeek = updatePieChart(
                null, 'pieChartWeek',
                [@json($currentWeekRevenue), @json($lastWeekRevenue)],
                ['Tuần này', 'Tuần trước'],
                'weekPercentage', @json($weekPercentage)
            );
        });
    </script>

    <!-- script của biểu đồ thống kê số lượng khách và biểu đồ so sánh số lượng khách -->
    <script>
        // Biểu đồ thống kê số lượng khách
        $(document).ready(function () {
            let customerChart;

            function updateCustomerChart(labels, data) {
                if (customerChart) {
                    customerChart.destroy();
                }
                let ctx = document.getElementById('soLuongKhachChart').getContext('2d');
                let chartType = labels.length === 24 ? 'line' : 'bar';

                customerChart = new Chart(ctx, {
                    type: chartType,
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Số lượng khách',
                            data: data,
                            backgroundColor: 'rgba(54, 162, 235, 0.6)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: { y: { beginAtZero: true } }
                    }
                });
            }

            $('#filterForm2').on('submit', function (e) {
                e.preventDefault();
                let filterType2 = $('#filterType2').val();

                $.ajax({
                    url: "{{ route('dashboard') }}",
                    type: "GET",
                    data: { filterType2: filterType2 },
                    success: function (response) {
                        $('#soKhach').text(response.soKhach);
                        updateCustomerChart(response.customerLabels, response.customerData);
                        let timeRangeText = response.filterType2 === 'year' ? 'TRONG NĂM' :
                            response.filterType2 === 'month' ? 'TRONG THÁNG' : 'TRONG NGÀY';
                        $('#timeRange2').text(timeRangeText);
                    },
                    error: function () {
                        alert('Lỗi tải dữ liệu, vui lòng thử lại.');
                    }
                });
            });

            updateCustomerChart(@json($customerLabels), @json($customerData));

            $(document).ready(function () {
                let pieChartMonth2, pieChartWeek2;

                function updatePieChart(chart, canvasId, data, labels, percentageId, percentage) {
                    if (chart) {
                        chart.destroy();
                    }
                    let ctx = document.getElementById(canvasId).getContext('2d');
                    chart = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: labels,
                            datasets: [{
                                data: data,
                                backgroundColor: ['rgba(75, 192, 192, 0.6)', 'rgba(255, 99, 132, 0.6)']
                            }]
                        },
                        options: {
                            responsive: true
                        }
                    });

                    // Hiển thị phần trăm chênh lệch
                    let changeText = percentage > 0
                        ? `Tăng ${percentage}% so với kỳ trước 🔼`
                        : percentage < 0
                            ? `Giảm ${Math.abs(percentage)}% so với kỳ trước 🔽`
                            : `Không thay đổi 📊`;

                    document.getElementById(percentageId).innerText = changeText;
                    return chart;
                }

                // AJAX tải dữ liệu so sánh lượng khách
                function loadCustomerComparison() {
                    $.ajax({
                        url: "{{ route('dashboard') }}",
                        method: 'GET',
                        data: { compareCustomers: true },
                        success: function (response) {
                            pieChartMonth2 = updatePieChart(
                                pieChartMonth2, 'pieChartMonth2',
                                [response.currentMonthCustomers, response.lastMonthCustomers],
                                ['Tháng này', 'Tháng trước'],
                                'monthPercentage2', response.monthComparisonPercentage
                            );

                            pieChartWeek2 = updatePieChart(
                                pieChartWeek2, 'pieChartWeek2',
                                [response.currentWeekCustomers, response.lastWeekCustomers],
                                ['Tuần này', 'Tuần trước'],
                                'weekPercentage2', response.weekComparisonPercentage
                            );
                        },
                        error: function () {
                            alert('Lỗi tải dữ liệu so sánh số lượng khách.');
                        }
                    });
                }

                // Gọi hàm khi trang load
                loadCustomerComparison();
            });
        });
    </script>
@endsection
