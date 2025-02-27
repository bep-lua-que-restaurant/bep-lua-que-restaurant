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
                        <p class="text-danger" style="font-size: 24px; font-weight: bold;">{{ number_format(array_sum($data), 0, ',', '.') }} VND</p>
{{--                        <small class="text-muted">Hôm qua: 6,055,000</small>--}}
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


    </div>

    <!-- Nhúng Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

@endsection
