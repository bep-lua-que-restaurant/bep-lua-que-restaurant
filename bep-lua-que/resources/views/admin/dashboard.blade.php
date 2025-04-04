@extends('layouts.admin')

@section('title')
    Trang chủ
@endsection

@section('content')
    <div class="container">
        <!-- Kết quả bán hàng hôm nay -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <h5>Đơn đã xong hôm nay</h5>
                        <p id="tongTienHomNay" class="text-danger" style="font-size: 24px; font-weight: bold;">
                            {{ number_format($tongTienHomNay, 0, ',', '.') }} VND</p>
                        <small class="text-muted">Hôm qua: <span
                                id="tongTienHomQua">{{ number_format($tongTienHomQua, 0, ',', '.') }}
                                VND</span></small>
                    </div>
                    <div class="col-md-4 text-center">
                        <h5>Đơn đang phục vụ</h5>
                        <p id="donDangPhucVuHomNay" class="text-danger" style="font-size: 24px; font-weight: bold;">
                            {{ $donDangPhucVuHomNay }}
                        </p>
                        <small class="text-muted">Hôm qua: <span
                                id="donPhucVuHomQua">{{ $donPhucVuHomQua }}</span></small>
                    </div>
                    <div class="col-md-4 text-center">
                        <h5>Khách hàng hôm nay</h5>
                        <p id="soLuongKhachHomNay" class="text-danger" style="font-size: 24px; font-weight: bold;">
                            {{ $soLuongKhachHomNay }}</p>
                        <small class="text-muted">Hôm qua: <span
                                id="soLuongKhachHomQua">{{ $soLuongKhachHomQua }}</span></small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-bold">DOANH SỐ HÔM NAY</h5>

                <h5 class="text-primary fw-bold">
                    <i class="bi bi-info-circle"></i>
                    <span id="tongDoanhSo">{{ number_format(array_sum($data), 0, ',', '.') }} VND</span>
                </h5>

                <!-- Biểu đồ -->
                <canvas id="thongKeChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Nhúng Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            let chart;
            let isFirstLoad = true; // Đánh dấu lần load đầu tiên

            function updateChart(labels, data) {
                if (chart) {
                    chart.destroy();
                }
                let ctx = document.getElementById('thongKeChart').getContext('2d');
                chart = new Chart(ctx, {
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
                        animation: isFirstLoad ? {
                            duration: 1000
                        } : false, // Chỉ animation khi tải lần đầu
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
                isFirstLoad = false;
            }

            function loadTodayStats() {
                $.ajax({
                    url: "{{ route('dashboard') }}",
                    method: 'GET',
                    cache: true, // Lưu cache để tối ưu tốc độ
                    success: function(response) {
                        $('#tongTienHomNay').text(response.tongTienHomNay);
                        $('#tongTienHomQua').text(response.tongTienHomQua);
                        $('#soLuongKhachHomNay').text(response.soLuongKhachHomNay);
                        $('#soLuongKhachHomQua').text(response.soLuongKhachHomQua);
                        $('#tongDoanhSo').text(response.tongTienHomNay);
                        $('#donDangPhucVuHomNay').text(response.donDangPhucVuHomNay);
                        $('#donPhucVuHomQua').text(response.donPhucVuHomQua);

                        updateChart(response.labels, response.data);
                    }
                });
            }

            // Gọi AJAX ban đầu và sau mỗi 5 phút
            loadTodayStats();
            setInterval(loadTodayStats, 300000);
        });
    </script>
@endsection
