@extends('layouts.admin')

@section('title')
    Thống kê số lượng khách
@endsection

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-bold">SỐ LƯỢNG KHÁCH <span id="timeRange">
                    @if ($filterType == 'year') TRONG NĂM @elseif ($filterType == 'month') TRONG THÁNG @elseif ($filterType == 'week') TRONG TUẦN @else TRONG NGÀY @endif
                </span></h5>

                <h5 class="text-primary fw-bold">
                    <i class="bi bi-info-circle"></i>
                    <span id="totalGuests">{{ number_format(array_sum($data), 0, ',', '.') }} Khách</span>
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
                <canvas id="thongKeChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Nhúng Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            let chart;

            function updateChart(labels, data, formatType) {
                if (chart) {
                    chart.destroy();
                }
                let ctx = document.getElementById('thongKeChart').getContext('2d');
                chart = new Chart(ctx, {
                    type: 'bar',
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
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: formatType === 'day' ? 'Ngày' : formatType === 'month' ? 'Tháng' : formatType === 'year' ? 'Năm' : 'Tuần'
                                }
                            },
                            y: { beginAtZero: true }
                        }
                    }
                });
            }

            // Xử lý khi thay đổi bộ lọc
            $('#filterType').on('change', function () {
                let filterType = $(this).val();

                $.ajax({
                    url: "/thong-ke-so-luong-khach",
                    type: "GET",
                    data: { filterType: filterType },
                    success: function (response) {
                        $('#totalGuests').text(response.totalCustomers); // Cập nhật số khách đúng key
                        $('#timeRange').text(filterType === 'year' ? 'TRONG NĂM' : filterType === 'month' ? 'TRONG THÁNG' : filterType === 'week' ? 'TRONG TUẦN' : 'TRONG NGÀY');
                        updateChart(response.labels, response.data, filterType);
                    },
                    error: function () {
                        alert('Lỗi tải dữ liệu, vui lòng thử lại.');
                    }
                });
            });

            // Xử lý lọc theo khoảng ngày tháng năm
            $('#btnFilter').on('click', function () {
                let fromDate = $('#startDate').val();
                let toDate = $('#endDate').val();

                if (!fromDate || !toDate) {
                    alert("Vui lòng chọn đầy đủ ngày bắt đầu và ngày kết thúc!");
                    return;
                }

                let fromDateObj = new Date(fromDate);
                let toDateObj = new Date(toDate);

                let fromDay = fromDateObj.getDate();
                let fromMonth = fromDateObj.getMonth() + 1; // getMonth() trả về từ 0-11 nên cần +1
                let fromYear = fromDateObj.getFullYear();

                let toDay = toDateObj.getDate();
                let toMonth = toDateObj.getMonth() + 1;
                let toYear = toDateObj.getFullYear();

                // Kiểm tra năm
                if (toYear < fromYear) {
                    alert("Năm của ngày kết thúc không thể nhỏ hơn năm của ngày bắt đầu!");
                    return;
                }

                // Nếu cùng năm, kiểm tra tháng
                if (toYear === fromYear && toMonth < fromMonth) {
                    alert("Tháng của ngày kết thúc không thể nhỏ hơn tháng của ngày bắt đầu!");
                    return;
                }

                // Nếu cùng năm và tháng, kiểm tra ngày
                if (toYear === fromYear && toMonth === fromMonth && toDay < fromDay) {
                    alert("Ngày kết thúc không thể nhỏ hơn ngày bắt đầu!");
                    return;
                }

                // Format lại ngày thành DD-MM-YYYY
                function formatDate(dateString) {
                    let parts = dateString.split(/[-\/]/); // Tách theo cả '-' và '/'
                    return `${parts[2]}-${parts[1]}-${parts[0]}`; // Định dạng DD-MM-YYYY
                }

                $.ajax({
                    url: "/thong-ke-so-luong-khach",
                    type: "GET",
                    data: { fromDate: fromDate, toDate: toDate },
                    success: function (response) {
                        $('#totalGuests').text(response.totalCustomers); // Cập nhật số khách đúng key
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

            // Cập nhật biểu đồ ban đầu
            updateChart(@json($labels), @json($data), 'day');
        });
    </script>

@endsection
