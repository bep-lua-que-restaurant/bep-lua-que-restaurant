@extends('layouts.admin')

@section('title')
    Thống kê doanh thu
@endsection

@section('content')
    <style>

        .highcharts-data-table table {
            font-family: Verdana, sans-serif;
            border-collapse: collapse;
            border: 1px solid #ebebeb;
            margin: 10px auto;
            text-align: center;
            width: 100%;
            max-width: 500px;
        }

        .highcharts-data-table caption {
            padding: 1em 0;
            font-size: 1.2em;
            color: #555;
        }

        .highcharts-data-table th {
            font-weight: 600;
            padding: 0.5em;
        }

        .highcharts-data-table td,
        .highcharts-data-table th,
        .highcharts-data-table caption {
            padding: 0.5em;
        }

        .highcharts-data-table thead tr,
        .highcharts-data-table tbody tr:nth-child(even) {
            background: #f8f8f8;
        }

        .highcharts-data-table tr:hover {
            background: #f1f7ff;
        }
    </style>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-bold">DOANH THU <span id="phamViLoc">
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
{{--                <canvas id="thongKeDoanhSo" height="100"></canvas>--}}

                <figure class="highcharts-figure">
                    <div id="thongKeDoanhSo" style="height: 430px;"></div>
                </figure>
            </div>
        </div>
    </div>

    <!-- Nhúng Chart.js -->
{{--    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>--}}
    <!-- Thay thế Chart.js bằng Highcharts -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/series-label.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            function capNhatBieuDo(labels, data, dinhDang) {
                // Ép kiểu sang số
                data = data.map(d => parseFloat(d) || 0);

                Highcharts.setOptions({
                    lang: {
                        contextButtonTitle: "Tùy chọn biểu đồ",
                        downloadJPEG: "Tải xuống JPEG",
                        downloadPDF: "Tải xuống PDF",
                        downloadPNG: "Tải xuống PNG",
                        downloadSVG: "Tải xuống SVG",
                        downloadCSV: "Tải xuống CSV",
                        downloadXLS: "Tải xuống Excel",
                        viewData: "Xem bảng dữ liệu",
                        hideData: "Ẩn bảng dữ liệu",
                        openInCloud: "Mở bằng Highcharts Cloud",
                        printChart: "In biểu đồ",
                        viewFullscreen: "Xem toàn màn hình",
                        exitFullscreen: "Thoát toàn màn hình",
                        loading: "Đang tải...",
                        noData: "Không có dữ liệu để hiển thị"
                    },
                });

                Highcharts.chart('thongKeDoanhSo', {
                    data: {
                        enabled: true
                    },
                    exportData: {
                        tableFormatter: function (items) {
                            const columns = this.getDataRows(true); // true để lấy cả header
                            const html = [];

                            html.push('<table class="highcharts-data-table"><thead><tr>');
                            columns[0].forEach(header => {
                                html.push('<th>' + header + '</th>');
                            });
                            html.push('</tr></thead><tbody>');

                            for (let i = 1; i < columns.length; i++) {
                                html.push('<tr>');
                                columns[i].forEach((val, j) => {
                                    if (j === 0) {
                                        // Cột đầu tiên là "Thời gian"
                                        html.push('<td>' + val + '</td>');
                                    } else {
                                        // Cột Doanh thu: định dạng tiền
                                        const formatted = Highcharts.numberFormat(Number(val), 0, ',', '.') + ' VNĐ';
                                        html.push('<td style="text-align:right;">' + formatted + '</td>');
                                    }
                                });
                                html.push('</tr>');
                            }

                            html.push('</tbody></table>');
                            return html.join('');
                        }
                    },

                    exporting: {
                        enabled: true
                    },


                    chart: { type: 'column',
                        style: {
                            fontFamily: 'Arial, sans-serif' // <- Font bạn muốn
                        }},
                    title: { text: ' ' },
                    xAxis: {
                        categories: labels,
                        title: {
                            text: dinhDang === 'day' ? 'Ngày' : dinhDang === 'month' ? 'Tháng' : dinhDang === 'year' ? 'Năm' : 'Tuần'
                        }
                    },
                    yAxis: {
                        min: 0,
                        title: { text: 'Doanh thu (VND)' },
                        labels: {
                            formatter: function () {
                                let value = this.value;
                                if (value >= 1_000_000_000) return (value / 1_000_000_000) + ' tỷ';
                                if (value >= 1_000_000) return (value / 1_000_000) + ' triệu';
                                if (value >= 1_000) return (value / 1_000) + 'k';
                                return value;
                            }
                        }
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.y:,.0f} VND</b>',
                    },

                    legend: {
                        verticalAlign: 'top'
                    },

                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            borderWidth: 0
                        }
                    },
                    series: [{
                        name: 'Doanh thu',
                        data: data,
                        color: '#36A2EB'
                    }],
                    credits: { enabled: false }
                });
            }


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

                ngayHienTai.setHours(0, 0, 0, 0);
                ngayBatDauObj.setHours(0, 0, 0, 0);
                ngayKetThucObj.setHours(0, 0, 0, 0);

                if (ngayBatDauObj > ngayKetThucObj) {
                    alert("Ngày bắt đầu không thể lớn hơn ngày kết thúc!");
                    return;
                }

                if (ngayBatDauObj > ngayHienTai || ngayKetThucObj > ngayHienTai) {
                    alert("Chỉ lọc đến ngày hiện tại! Vui lòng chọn đến ngày " +
                        ngayHienTai.toLocaleDateString('vi-VN') + ".");
                    return;
                }

                function dinhDangNgay(dateString) {
                    let parts = dateString.split(/[-\/]/);
                    return `${parts[2]}-${parts[1]}-${parts[0]}`;
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

            // Load biểu đồ ban đầu
            capNhatBieuDo(@json($labels), @json($data), 'day');
        });
    </script>

@endsection
