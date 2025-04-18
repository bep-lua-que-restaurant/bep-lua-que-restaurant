@extends('layouts.admin')

@section('content')
    <div class="card" style="border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); background-color: #f8f9fa;">
        <div class="card-body" style="padding: 20px;">
            <h2 id="chartTitle"
                style="text-align: center; margin-top: 30px;margin-bottom: 30px; font-weight: bold; color: #333; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                🍽️ Thống kê 10 món ăn bán chạy
            </h2>

            <!-- Bộ lọc thời gian -->
            <form id="filterForm" style="margin-bottom: 40px;margin-top: 40px;">
                <div style="display: flex; justify-content: center; gap: 20px; align-items: center; flex-wrap: wrap;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <label for="chartType" style="font-weight: bold; white-space: nowrap;">Thống kê:</label>
                        <select id="chartType" class="custom-select" onchange="updateTitle()">
                            <option value="banchay" selected>Bán chạy nhất</option>
                            <option value="banit">Bán ít nhất</option>
                        </select>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <label for="timeFilter" style="font-weight: bold; white-space: nowrap;">Hiển thị:</label>
                        <select name="filterType" id="timeFilter" class="custom-select">
                            <option value="day" selected>Theo Ngày</option>
                            <option value="month">Theo Tháng</option>
                            <option value="year">Theo Năm</option>
                        </select>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <label for="startDate" style="font-weight: bold; white-space: nowrap;">Từ:</label>
                        <input type="date" name="fromDate" id="startDate" class="custom-input"
                            max="<?= date('Y-m-d') ?>">
                        <label for="endDate" style="font-weight: bold; white-space: nowrap;">Đến:</label>
                        <input type="date" name="toDate" id="endDate" class="custom-input" max="<?= date('Y-m-d') ?>">
                        <button type="button" id="dateFilterBtn" class="custom-button">Lọc</button>
                    </div>
                </div>
            </form>

            <div id="chartContainer"
                style="padding: 20px; background: #fff; border-radius: 10px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                @include('admin.thongke.listthongkemonan')
            </div>
        </div>
    </div>
@endsection

<style>
    body {
        background-color: #e9ecef;
    }

    .custom-select {
        padding: 8px 12px;
        border-radius: 5px;
        border: 1px solid #ccc;
        background: #fff;
        cursor: pointer;
    }

    .custom-input {
        padding: 8px 12px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    .custom-button {
        padding: 8px 16px;
        border-radius: 5px;
        background-color: #28a745;
        color: white;
        border: none;
        cursor: pointer;
        font-weight: bold;
        transition: background 0.3s;
    }

    .custom-button:hover {
        background-color: #218838;
    }
</style>




<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        let currentDate = new Date().toISOString().split('T')[0];
        updateTitle();

        // Hiển thị alert khi trang vừa tải
        // alert(`Đang hiển thị dữ liệu theo ngày hiện tại: ${currentDate}`);

        // Gọi API khi trang vừa tải để lấy dữ liệu ngày hiện tại
        loadData(currentDate, currentDate, 'day');

        $('#timeFilter').change(function() {
            let filterType = $(this).val();
            let fromDate, toDate;
            let chartType = $('#chartType').val(); // Lấy giá trị bán chạy hoặc bán ít

            if (filterType === 'day') {
                fromDate = toDate = new Date().toISOString().split('T')[0];
            } else if (filterType === 'month') {
                let currentDate = new Date();
                let month = currentDate.getMonth() + 1; // Sửa lỗi tháng bị lệch
                let year = currentDate.getFullYear();
                fromDate = `${year}-${month.toString().padStart(2, '0')}-01`;
                toDate = new Date(year, month, 0).toISOString().split('T')[0]; // Lấy ngày cuối tháng
            } else if (filterType === 'year') {
                let currentYear = new Date().getFullYear();
                fromDate = `${currentYear}-01-01`;
                toDate = `${currentYear}-12-31`;
            }

            loadData(fromDate, toDate, filterType, chartType);
        });

        $('#dateFilterBtn').click(function() {
            let fromDate = $('#startDate').val();
            let toDate = $('#endDate').val();
            let chartType = $('#chartType').val(); // Lấy giá trị bán chạy hoặc bán ít

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

            // 1️⃣ Kiểm tra năm bắt đầu không lớn hơn năm kết thúc
            if (fromDateObj.getFullYear() > toDateObj.getFullYear()) {
                alert("Năm của ngày bắt đầu không thể lớn hơn năm của ngày kết thúc!");
                return;
            }

            // 2️⃣ Nếu cùng năm, kiểm tra tháng
            if (fromDateObj.getFullYear() === toDateObj.getFullYear() &&
                fromDateObj.getMonth() > toDateObj.getMonth()) {
                alert("Tháng của ngày bắt đầu không thể lớn hơn tháng của ngày kết thúc!");
                return;
            }

            // 3️⃣ Nếu cùng năm và tháng, kiểm tra ngày
            if (fromDateObj.getFullYear() === toDateObj.getFullYear() &&
                fromDateObj.getMonth() === toDateObj.getMonth() &&
                fromDateObj.getDate() > toDateObj.getDate()) {
                alert("Ngày bắt đầu không thể lớn hơn ngày kết thúc!");
                return;
            }

            // 4️⃣ Kiểm tra ngày không lớn hơn ngày hiện tại
            if (fromDateObj > currentDate || toDateObj > currentDate) {
                alert("Chỉ lọc đến ngày hiện tại! Vui lòng chọn đến ngày " +
                    currentDate.toLocaleDateString('vi-VN') + ".");
                return;
            }

            // Nếu hợp lệ, gọi hàm loadData
            loadData(fromDate, toDate, 'custom', chartType);
        });

        $('#chartType').change(function() {
            let filterType = $('#timeFilter').val();
            let chartType = $(this).val(); // Lấy giá trị bán chạy hoặc bán ít
            let fromDate = $('#startDate').val() || currentDate;
            let toDate = $('#endDate').val() || currentDate;
            updateTitle();
            loadData(fromDate, toDate, filterType, chartType);
        });

        function loadData(fromDate, toDate, filterType, chartType) {
            $.ajax({
                url: "/thong-ke-mon-an",
                type: "GET",
                data: {
                    fromDate: fromDate,
                    toDate: toDate,
                    filterType: filterType,
                    chartType: chartType
                },
                beforeSend: function() {
                    $('#chartContainer').html(
                        '<p style="text-align: center;">Đang tải dữ liệu...</p>');
                },
                success: function(response) {
                    if (response.labels && response.datasets) {
                        renderChart(response.labels, response.datasets, fromDate, toDate,
                            filterType, chartType);
                    } else {
                        renderChart(['Không có dữ liệu'], [0], fromDate, toDate, filterType,
                            chartType);
                    }
                },

                error: function(xhr, status, error) {
                    console.error('Lỗi khi gọi API:', error);
                    $('#chartContainer').html(
                        '<p style="text-align: center; color: red;">Có lỗi xảy ra khi tải dữ liệu.</p>'
                    );
                }
            });
        }

        function renderChart(labels, data, fromDate, toDate, filterType, chartType) {
            $('#chartContainer').html('<canvas id="myChart"></canvas>');
            let ctx = document.getElementById('myChart').getContext('2d');

            if (!Array.isArray(labels) || labels.length === 0) {
                labels = ['Không có dữ liệu'];
                data = [0];
            }

            let maxDataValue = Math.max(...data);
            let yAxisMaxValue = Math.ceil(maxDataValue * 1.2) || 10;

            // 🛠 Định dạng ngày tháng năm cho tiêu đề
            let titleText = '';
            let formatDate = (dateStr) => {
                let date = new Date(dateStr);
                return date.toLocaleDateString('vi-VN', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });
            };

            if (filterType === 'day') {
                titleText = `Ngày: ${formatDate(fromDate)}`;
            } else if (filterType === 'month') {
                titleText = `Tháng: ${fromDate.split('-')[1]}/${fromDate.split('-')[0]}`;
            } else if (filterType === 'year') {
                titleText = `Năm: ${new Date(fromDate).getFullYear()}`;
            } else if (filterType === 'custom') {
                titleText = `Khoảng thời gian: ${formatDate(fromDate)} - ${formatDate(toDate)}`;
            }


            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Số lượng món ăn',
                        data: data,
                        backgroundColor: chartType === 'banit' ?
                            'rgba(255, 99, 132, 0.9)' // Màu đỏ cho bán ít
                            :
                            'rgba(144, 197, 238, 0.9)', // Màu xanh cho bán chạy
                        borderColor: chartType === 'banit' ?
                            'rgba(255, 59, 92, 1)' : 'rgba(94, 197, 238, 1)',
                        borderWidth: 1.5,
                        barThickness: labels.length <= 3 ? 80 : 'flex',
                        categoryPercentage: labels.length === 1 ? 0.5 : 0.8,
                        hoverBackgroundColor: chartType === 'banit' ?
                            'rgba(255, 80, 92, 0.95)' : 'rgba(94, 167, 228, 0.95)',
                        hoverBorderColor: chartType === 'banit' ?
                            'rgba(255, 40, 60, 1)' : 'rgba(54, 137, 218, 1)',
                    }]

                },
                options: {
                    responsive: true,
                    animation: {
                        duration: 800,
                        easing: 'easeOutBounce' // Hiệu ứng mềm mại
                    },
                    plugins: {
                        legend: {
                            display: true,
                            labels: {
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                },
                                color: '#333'
                            }
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: [`Món ăn`, titleText],
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                },
                                color: '#444'
                            },
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Số lượng',
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                },
                                color: '#444'
                            },
                            ticks: {
                                stepSize: Math.ceil(yAxisMaxValue / 5), // Chia nhỏ trục Y
                                font: {
                                    size: 12
                                }
                            },
                            beginAtZero: true,
                            max: yAxisMaxValue
                        }
                    }
                }
            });
        }

        function updateTitle() {
            const chartType = document.getElementById('chartType').value;
            const titleElement = document.getElementById('chartTitle');

            if (chartType === 'banchay') {
                titleElement.innerHTML = '🍽️ Thống kê 10 món ăn bán chạy';
            } else {
                titleElement.innerHTML = '🍽️ Thống kê 10 món ăn bán ít';
            }
        }
    });
</script>
