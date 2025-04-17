@extends('layouts.admin')

@section('title')
    Thống kê số lượng khách
@endsection

@section('content')
    <style>
        /*.highcharts-figure,*/
        /*.highcharts-data-table table {*/
        /*    min-width: 360px;*/
        /*    max-width: 800px;*/
        /*    margin: 1em auto;*/
        /*}*/

        .highcharts-data-table table {
            /*font-family: Verdana, sans-serif;*/
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
                <h5 class="card-title fw-bold">SỐ LƯỢNG HÓA ĐƠN <span id="timeRange">
                    @if ($filterType == 'year') TRONG NĂM @elseif ($filterType == 'month') TRONG THÁNG @elseif ($filterType == 'week') TRONG TUẦN @else TRONG NGÀY @endif
                </span></h5>

                <h5 class="text-primary fw-bold">
                    <i class="bi bi-info-circle"></i>
                    <span id="totalInvoices">{{ number_format(array_sum($data), 0, ',', '.') }} Hóa đơn</span>
                </h5>

                <form id="filterForm">
                    <div class="form-group d-flex align-items-center justify-content-between">
                        <select name="filterType" id="filterType" class="form-select mr-2" style="width: 135px">
                            <option value="year" {{ $filterType == 'year' ? 'selected' : '' }}>Theo Năm</option>
                            <option value="month" {{ $filterType == 'month' ? 'selected' : '' }}>Theo Tháng</option>
                            <option value="week" {{ $filterType == 'week' ? 'selected' : '' }}>Theo Tuần</option>
                            <option value="day" {{ $filterType == 'day' ? 'selected' : '' }}>Theo Ngày</option>
                        </select>

                        <div class="boLocTuyChinh">
                            <label for="startDate"><strong>Từ:</strong></label>
                            <input type="date" name="fromDate" id="startDate" style="padding: 8px 12px; border-radius: 5px; border: 1px solid #ccc;">
                            <label for="endDate"><strong>Đến:</strong></label>
                            <input type="date" name="toDate" id="endDate" style="padding: 8px 12px; border-radius: 5px; border: 1px solid #ccc;">
                            <button type="button" class="btn btn-primary" id="btnFilter">Lọc</button>
                        </div>
                    </div>
                </form>
                <!-- Biểu đồ -->
{{--                <canvas id="thongKeHoaDon" height="100"></canvas>--}}
                <figure class="highcharts-figure">
                    <div id="thongKeHoaDon" style="height: 430px;"></div>
                </figure>
            </div>
        </div>
    </div>

    <!-- Nhúng Chart.js -->
{{--    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>--}}
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/series-label.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // let chart;
            function updateChart(labels, data, formatType) {
                // if (chart) { chart.destroy(); }
                // let ctx = document.getElementById('thongKeHoaDon').getContext('2d');
                // chart = new Chart(ctx, {
                //     type: 'line',
                //     data: {
                //         labels: labels,
                //         datasets: [{
                //             label: 'Số lượng hóa đơn',
                //             data: data,
                //             backgroundColor: 'rgba(54, 162, 235, 0.6)',
                //             borderColor: 'rgba(54, 162, 235, 1)',
                //             borderWidth: 2,
                //             fill: true,
                //             tension: 0.1 // Làm mượt đường
                //         }]
                //     },
                //     options: {
                //         responsive: true,
                //         scales: {
                //             x: { title: { display: true, text: formatType === 'day' ? 'Ngày' : formatType === 'month' ? 'Tháng' : formatType === 'week' ? 'Tuần' : 'Năm' } },
                //             y: { beginAtZero: true }
                //         }
                //     }
                // });
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
                    }
                });


                Highcharts.chart('thongKeHoaDon', {
                    chart: {
                        style: {
                            fontFamily: 'Arial, sans-serif' // 👈 Đổi tên font tùy ý
                        }
                    },

                    title: {
                        text: ' ',
                        align: 'left'
                    },

                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Hóa đơn'
                        }
                    },

                    xAxis: {
                        categories: labels,
                        title: {
                            text: formatType === 'day' ? 'Ngày' : formatType === 'month' ? 'Tháng' : formatType === 'week' ? 'Tuần' : 'Năm'
                        },
                    },

                    legend: {
                        layout: 'vertical',
                        // align: 'right',
                        verticalAlign: 'top'
                    },

                    plotOptions: {
                        series: {
                            label: {
                                enabled: false
                            },
                            connectorAllowed: false
                        }
                    },

                    series: [{
                        name: 'Hóa đơn',
                        data: data,
                        color: '#36A2EB'
                    }],
                    credits: { enabled: false },

                    responsive: {
                        rules: [{
                            condition: {
                                maxWidth: 500
                            },
                            chartOptions: {
                                legend: {
                                    layout: 'horizontal',
                                    align: 'center',
                                    verticalAlign: 'bottom'
                                }
                            }
                        }]
                    }

                });
            }

            $('#filterType').on('change', function () {
                let filterType = $(this).val();
                $.ajax({
                    url: "/thong-ke-hoa-don",
                    type: "GET",
                    data: { filterType: filterType },
                    success: function (response) {
                        $('#totalInvoices').text(response.totalOrders);
                        $('#timeRange').text(filterType === 'year' ? 'TRONG NĂM' : filterType === 'month' ? 'TRONG THÁNG' : 'TRONG NGÀY');
                        updateChart(response.labels, response.data, filterType);
                    },
                    error: function () {
                        alert('Lỗi tải dữ liệu, vui lòng thử lại.');
                    }
                });
            });

            // Format lại ngày thành DD-MM-YYYY
            function formatDate(dateString) {
                let parts = dateString.split(/[-\/]/); // Tách theo cả '-' và '/'
                return `${parts[2]}-${parts[1]}-${parts[0]}`; // Định dạng DD-MM-YYYY
            }

            $('#btnFilter').on('click', function () {
                let fromDate = $('#startDate').val();
                let toDate = $('#endDate').val();

                if (!fromDate || !toDate) {
                    alert("Vui lòng chọn đầy đủ ngày bắt đầu và ngày kết thúc!");
                    return;
                }

                let fromDateObj = new Date(fromDate);
                let toDateObj = new Date(toDate);
                let currentDate = new Date();

                // Loại bỏ phần giờ, phút, giây
                currentDate.setHours(0, 0, 0, 0);
                fromDateObj.setHours(0, 0, 0, 0);
                toDateObj.setHours(0, 0, 0, 0);

                // Kiểm tra năm bắt đầu không lớn hơn năm kết thúc
                if (fromDateObj.getFullYear() > toDateObj.getFullYear()) {
                    alert("Năm của ngày bắt đầu không thể lớn hơn năm của ngày kết thúc!");
                    return;
                }

                // Nếu cùng năm, kiểm tra tháng
                if (fromDateObj.getFullYear() === toDateObj.getFullYear() &&
                    fromDateObj.getMonth() > toDateObj.getMonth()) {
                    alert("Tháng của ngày bắt đầu không thể lớn hơn tháng của ngày kết thúc!");
                    return;
                }

                // Nếu cùng năm và tháng, kiểm tra ngày
                if (fromDateObj.getFullYear() === toDateObj.getFullYear() &&
                    fromDateObj.getMonth() === toDateObj.getMonth() &&
                    fromDateObj.getDate() > toDateObj.getDate()) {
                    alert("Ngày bắt đầu không thể lớn hơn ngày kết thúc!");
                    return;
                }

                // Kiểm tra ngày không lớn hơn ngày hiện tại
                if (fromDateObj > currentDate || toDateObj > currentDate) {
                    alert("Chỉ lọc đến ngày hiện tại! Vui lòng chọn đến ngày " +
                        currentDate.toLocaleDateString('vi-VN') + ".");
                    return;
                }

                $.ajax({
                    url: "/thong-ke-hoa-don",
                    type: "GET",
                    data: { fromDate: fromDate, toDate: toDate },
                    success: function (response) {
                        $('#totalInvoices').text(response.totalOrders);
                        $('#timeRange').text(`TỪ ${formatDate(fromDate)} ĐẾN ${formatDate(toDate)}`);
                        let from = new Date(fromDate);
                        let to = new Date(toDate);
                        let diffDays = (to - from) / (1000 * 60 * 60 * 24);

                        let formatType = diffDays > 365 ? 'year' : diffDays > 30 ? 'month' : 'day';

                        updateChart(response.labels, response.data, formatType);
                    },
                    error: function () {
                        alert('Lỗi tải dữ liệu, vui lòng thử lại.');
                    }
                });
            });

            updateChart(@json($labels), @json($data), 'day');
        });
    </script>
@endsection
