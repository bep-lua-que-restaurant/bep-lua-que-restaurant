@extends('layouts.admin')

@section('title')
    Thống kê doanh số
@endsection

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-bold">DOANH SỐ <span id="phamViLoc">
                    @if ($boLoc == 'year') TRONG NĂM @elseif ($boLoc == 'month') TRONG THÁNG @elseif ($boLoc == 'week') TRONG TUẦN @else TRONG NGÀY @endif
                </span></h5>

                <h5 class="text-primary fw-bold">
                    <i class="bi bi-info-circle"></i>
                    <span id="tongDoanhSo">{{ number_format(array_sum($data), 0, ',', '.') }} VND</span>
                </h5>

                <form id="bieuMauLoc">
                    <div class="form-group d-flex align-items-center justify-content-between">
                        <select name="boLoc" id="boLoc" class="form-select mr-2" style="width: 135px">
                            <option value="year" {{ $boLoc == 'year' ? 'selected' : '' }}>Theo Năm</option>
                            <option value="month" {{ $boLoc == 'month' ? 'selected' : '' }}>Theo Tháng</option>
                            <option value="week" {{ $boLoc == 'week' ? 'selected' : '' }}>Theo Tuần</option>
                            <option value="day" {{ $boLoc == 'day' ? 'selected' : '' }}>Theo Ngày</option>
                        </select>

                        <div class="boLocTuyChinh">
                            <label for="ngayBatDau"><strong>Từ:</strong></label>
                            <input type="date" name="ngayBatDau" id="ngayBatDau" style="padding: 8px 12px; border-radius: 5px; border: 1px solid #ccc;">
                            <label for="ngayKetThuc"><strong>Đến:</strong></label>
                            <input type="date" name="ngayKetThuc" id="ngayKetThuc" style="padding: 8px 12px; border-radius: 5px; border: 1px solid #ccc;">
                            <button type="button" class="btn btn-primary" id="btnFilter">Lọc</button>
                        </div>
                    </div>
                </form>
                <!-- Biểu đồ -->
                <canvas id="thongKeDoanhSo" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Nhúng Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            let chart;

            function capNhatBieuDo(labels, data, dinhDang) {
                if (chart) {
                    chart.destroy();
                }
                let ctx = document.getElementById('thongKeDoanhSo').getContext('2d');
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
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: dinhDang === 'day' ? 'Ngày' : dinhDang === 'month' ? 'Tháng' : dinhDang === 'year' ? 'Năm' : 'Tuần'
                                }
                            },
                            y: { beginAtZero: true }
                        }
                    }
                });
            }

            // Xử lý tự động khi thay đổi bộ lọc năm/tháng/tuần/ngày
            $('#boLoc').on('change', function () {
                let boLoc = $(this).val();

                $.ajax({
                    url: "/thong-ke-doanh-so",
                    type: "GET",
                    data: { boLoc: boLoc },
                    success: function (response) {
                        $('#tongDoanhSo').text(response.tongDoanhSo);
                        $('#phamViLoc').text(boLoc === 'year' ? 'TRONG NĂM' : boLoc === 'month' ? 'TRONG THÁNG' : boLoc === 'week' ? 'TRONG TUẦN' : 'TRONG NGÀY');
                        capNhatBieuDo(response.labels, response.data, boLoc);
                    },
                    error: function () {
                        alert('Lỗi tải dữ liệu, vui lòng thử lại.');
                    }
                });
            });

            // Xử lý lọc theo khoảng ngày tháng năm
            $('#btnFilter').on('click', function () {
                let ngayBatDau = $('#ngayBatDau').val();
                let ngayKetThuc = $('#ngayKetThuc').val();

                if (!ngayBatDau || !ngayKetThuc) {
                    alert("Vui lòng chọn đầy đủ ngày bắt đầu và ngày kết thúc!");
                    return;
                }

                let ngayBatDauObj = new Date(ngayBatDau);
                let ngayKetThucObj = new Date(ngayKetThuc);
                let ngayHienTai = new Date();

                // Loại bỏ phần giờ, phút, giây
                ngayHienTai.setHours(0, 0, 0, 0);
                ngayBatDauObj.setHours(0, 0, 0, 0);
                ngayKetThucObj.setHours(0, 0, 0, 0);

                // Kiểm tra năm bắt đầu không lớn hơn năm kết thúc
                if (ngayBatDauObj.getFullYear() > ngayKetThucObj.getFullYear()) {
                    alert("Năm của ngày bắt đầu không thể lớn hơn năm của ngày kết thúc!");
                    return;
                }

                // Nếu cùng năm, kiểm tra tháng
                if (ngayBatDauObj.getFullYear() === ngayKetThucObj.getFullYear() &&
                    ngayBatDauObj.getMonth() > ngayKetThucObj.getMonth()) {
                    alert("Tháng của ngày bắt đầu không thể lớn hơn tháng của ngày kết thúc!");
                    return;
                }

                // Nếu cùng năm và tháng, kiểm tra ngày
                if (ngayBatDauObj.getFullYear() === ngayKetThucObj.getFullYear() &&
                    ngayBatDauObj.getMonth() === ngayKetThucObj.getMonth() &&
                    ngayBatDauObj.getDate() > ngayKetThucObj.getDate()) {
                    alert("Ngày bắt đầu không thể lớn hơn ngày kết thúc!");
                    return;
                }

                // Kiểm tra ngày không lớn hơn ngày hiện tại
                if (ngayBatDauObj > ngayHienTai || ngayKetThucObj > ngayHienTai) {
                    alert("Chỉ lọc đến ngày hiện tại! Vui lòng chọn đến ngày " +
                        ngayHienTai.toLocaleDateString('vi-VN') + ".");
                    return;
                }

                // Format lại ngày thành DD-MM-YYYY
                function dinhDangNgay(dateString) {
                    let parts = dateString.split(/[-\/]/); // Tách theo cả '-' và '/'
                    return `${parts[2]}-${parts[1]}-${parts[0]}`; // Định dạng DD-MM-YYYY
                }

                $.ajax({
                    url: "/thong-ke-doanh-so",
                    type: "GET",
                    data: { ngayBatDau: ngayBatDau, ngayKetThuc: ngayKetThuc },
                    success: function (response) {
                        $('#tongDoanhSo').text(response.tongDoanhSo);
                        $('#phamViLoc').text(`TỪ ${dinhDangNgay(ngayBatDau)} ĐẾN ${dinhDangNgay(ngayKetThuc)}`);

                        let tu = new Date(ngayBatDau);
                        let den = new Date(ngayKetThuc);
                        let dieuKienNgay = (den - tu) / (1000 * 60 * 60 * 24);

                        let dinhDang = dieuKienNgay > 365 ? 'year' : dieuKienNgay > 30 ? 'month' : 'day';

                        capNhatBieuDo(response.labels, response.data, dinhDang);
                    },
                    error: function () {
                        alert('Lỗi tải dữ liệu, vui lòng thử lại.');
                    }
                });
            });

            // Cập nhật biểu đồ ban đầu
            capNhatBieuDo(@json($labels), @json($data), 'day');
        });
    </script>
@endsection
