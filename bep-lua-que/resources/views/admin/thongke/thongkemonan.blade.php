@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-body">
            <h2 style="text-align: center; margin-bottom: 20px;">Thống kê Top 10 món ăn bán chạy nhất</h2>

            <!-- Bộ lọc thời gian -->
            <form id="filterForm">
                <div style="display: flex; justify-content: center; gap: 20px; align-items: center; margin-bottom: 20px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <label for="timeFilter"><strong>Hiển thị:</strong></label>
                        <select name="filterType" id="timeFilter"
                            style="padding: 8px 12px; border-radius: 5px; border: 1px solid #ccc;">
                            <option value="day" selected>Theo Ngày</option>
                            <option value="month">Theo Tháng</option>
                            <option value="year">Theo Năm</option>
                        </select>
                    </div>

                    <div style="display: flex; align-items: center; gap: 10px;">
                        <label for="startDate"><strong>Từ:</strong></label>
                        <input type="date" name="fromDate" id="startDate" value="2025-01-01"
                            style="padding: 8px 12px; border-radius: 5px; border: 1px solid #ccc;">
                        <label for="endDate"><strong>Đến:</strong></label>
                        <input type="date" name="toDate" id="endDate" value="2025-01-31"
                            style="padding: 8px 12px; border-radius: 5px; border: 1px solid #ccc;">
                        <button type="button" id="dateFilterBtn"
                            style="padding: 8px 12px; border-radius: 5px; background-color: #28a745; color: white; border: none; cursor: pointer;">
                            Lọc
                        </button>
                    </div>
                </div>
            </form>

            <div id="chartContainer">
                @include('admin.thongke.listthongkemonan')
            </div>
        </div>
    </div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#timeFilter').change(function() {
            let filterType = $(this).val();
            let currentDate = new Date();
            let fromDate, toDate;

            if (filterType === 'day') {
                fromDate = toDate = currentDate.toISOString().split('T')[0];
            } else if (filterType === 'month') {
                fromDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1).toISOString()
                    .split('T')[0];
                toDate = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0)
                    .toISOString().split('T')[0];
            } else if (filterType === 'year') {
                fromDate = new Date(currentDate.getFullYear(), 0, 1).toISOString().split('T')[0];
                toDate = new Date(currentDate.getFullYear(), 11, 31).toISOString().split('T')[0];
            }

            alert(`Đang lọc theo: ${filterType.toUpperCase()} (Từ: ${fromDate} đến ${toDate})`);
            loadData(fromDate, toDate, filterType);
        });

        $('#dateFilterBtn').click(function() {
            let fromDate = $('#startDate').val();
            let toDate = $('#endDate').val();

            if (!fromDate || !toDate) {
                alert("Vui lòng chọn đầy đủ ngày bắt đầu và ngày kết thúc!");
                return;
            }

            fromDate = new Date(fromDate).toISOString().split('T')[0];
            toDate = new Date(toDate).toISOString().split('T')[0];

            alert(`Đang lọc từ: ${fromDate} đến ${toDate}`);
            loadData(fromDate, toDate, 'custom');
        });

        function loadData(fromDate, toDate, filterType) {
            $.ajax({
                url: "/thong-ke-mon-an",
                type: "GET",
                data: {
                    fromDate: fromDate,
                    toDate: toDate,
                    filterType: filterType
                },
                beforeSend: function() {
                    $('#chartContainer').html(
                        '<p style="text-align: center;">Đang tải dữ liệu...</p>');
                },
                success: function(response) {
                    if (response.labels && response.datasets) {
                        renderChart(response.labels, response.datasets);
                    } else {
                        renderChart(['Không có dữ liệu'], [0]);
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

        function renderChart(labels, data) {
            $('#chartContainer').html('<canvas id="myChart"></canvas>');
            let ctx = document.getElementById('myChart').getContext('2d');

            if (!Array.isArray(labels) || labels.length === 0) {
                labels = ['Không có dữ liệu'];
                data = [0];
            }

            let maxDataValue = Math.max(...data);
            let yAxisMaxValue = Math.ceil(maxDataValue * 1.2) || 10;

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Số lượng món ăn',
                        data: data,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                        barThickness: labels.length === 1 ? 50 : 'flex',
                        categoryPercentage: labels.length === 1 ? 0.5 : 0.8,
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Món ăn'
                            },
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Số lượng'
                            },
                            beginAtZero: true,
                            max: yAxisMaxValue
                        }
                    }
                }
            });
        }
    });
</script>
