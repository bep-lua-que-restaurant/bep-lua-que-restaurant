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
                    <span id="totalSales"></span>
                </h5>

                <!-- Bộ lọc thời gian -->
                <div class="d-flex justify-content-between align-items-center">
                    <select id="filter-type" class="form-select w-25">
                        <option value="day">Theo Ngày</option>
                        <option value="hour">Theo Giờ</option>
                        <option value="weekday">Theo Thứ</option>
                    </select>

                    <select class="form-select w-auto" id="time-range" style="background-color: #f8f9fa; border: 1px solid #ced4da; padding: 5px 27px; border-radius: 5px; font-size: 14px;">
                        <option value="today">Hôm nay</option>
                        <option value="yesterday">Hôm qua</option>
                        <option value="last7days">7 Ngày Qua</option>
                        <option value="thismonth">Tháng này</option>
                        <option value="lastmonth">Tháng trước</option>
                    </select>
                </div>

                <!-- Biểu đồ -->
                <canvas id="thongKeChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Nhúng Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        let chart;
        const dataNgay = @json($dataNgay);
        const dataGio = @json($dataGio);
        const dataThu = @json($dataThu);

        function getChartData(type, timeRange) {
            let filteredData = [];

            function getVietnamDate(offset = 0) {
                let now = new Date();
                now.setHours(now.getHours() + 7); // Chuyển sang múi giờ Việt Nam
                now.setDate(now.getDate() + offset);
                return now.toISOString().split('T')[0]; // YYYY-MM-DD
            }

            function convertDayNumberToLabel(dayNumber) {
                const daysMap = [
                    "Chủ Nhật", "Thứ Hai", "Thứ Ba", "Thứ Tư", "Thứ Năm", "Thứ Sáu", "Thứ Bảy"
                ];
                return daysMap[dayNumber] || "";
            }

            const yesterday = new Date();
            yesterday.setDate(yesterday.getDate() - 1);
            yesterday.setHours(0, 0, 0, 0);

            const today = new Date();
            const todayStr = getVietnamDate(0);
            const yesterdayStr = getVietnamDate(-1);
            const sevenDaysAgoStr = getVietnamDate(-6); // Tính từ 6 ngày trước + hôm nay = 7 ngày

            const currentMonth = today.getMonth() + 1;
            const currentYear = today.getFullYear();

            let lastMonth = new Date();
            lastMonth.setMonth(lastMonth.getMonth() - 1);
            const lastMonthNumber = lastMonth.getMonth() + 1;
            const lastYear = lastMonth.getFullYear();

            if (type === 'day') {
                if (timeRange === 'today') {
                    filteredData = dataNgay.filter(item => item.label === todayStr);
                } else if (timeRange === 'yesterday') {
                    filteredData = dataNgay.filter(item => item.label === yesterdayStr);
                } else if (timeRange === 'last7days') {
                    let sevenDaysAgo = new Date();
                    sevenDaysAgo.setDate(sevenDaysAgo.getDate() - 7);
                    const sevenDaysAgoStr = sevenDaysAgo.toISOString().split('T')[0];
                    filteredData = dataNgay.filter(item => item.label >= sevenDaysAgoStr);
                } else if (timeRange === 'thismonth') {
                    const currentMonth = today.getMonth() + 1;
                    const currentYear = today.getFullYear();
                    filteredData = dataNgay.filter(item => {
                        const [year, month] = item.label.split('-');
                        return parseInt(year) === currentYear && parseInt(month) === currentMonth;
                    });
                } else if (timeRange === 'lastmonth') {
                    let lastMonth = new Date();
                    lastMonth.setMonth(lastMonth.getMonth() - 1);
                    const lastMonthNumber = lastMonth.getMonth() + 1;
                    const lastYear = lastMonth.getFullYear();
                    filteredData = dataNgay.filter(item => {
                        const [year, month] = item.label.split('-');
                        return parseInt(year) === lastYear && parseInt(month) === lastMonthNumber;
                    });
                }

                return {
                    labels: filteredData.map(item => item.label),
                    values: filteredData.map(item => Number(item.total)) // ✅ Chuyển sang kiểu số
                };
            } else if (type === 'hour') {
                let hourlyData = [];

                if (timeRange === 'today') {
                    hourlyData = dataGio.filter(item => item.date === todayStr);
                } else if (timeRange === 'yesterday') {
                    hourlyData = dataGio.filter(item => item.date === yesterdayStr);
                } else if (timeRange === 'last7days' || timeRange === 'thismonth' || timeRange === 'lastmonth') {
                    // Lọc dữ liệu theo bộ lọc
                    const filteredGio = dataGio.filter(item => {
                        let itemDate = item.date.split(' ')[0]; // ✅ Bỏ phần giờ phút giây nếu có
                        const [year, month, day] = itemDate.split('-').map(num => parseInt(num));

                        if (timeRange === 'last7days') {
                            return itemDate >= sevenDaysAgoStr && itemDate <= todayStr;
                        } else if (timeRange === 'thismonth') {
                            return year === currentYear && month === currentMonth;
                        } else if (timeRange === 'lastmonth') {
                            return year === lastYear && month === lastMonthNumber;
                        }
                    });

                    let hourlyTotals = Array(24).fill(0);
                    let uniqueDates = new Set(filteredGio.map(item => item.date)); // Xác định số ngày có dữ liệu
                    let totalDays = uniqueDates.size; // Số ngày thực sự có dữ liệu

                    filteredGio.forEach(item => {
                        let hour = item.label; // 🚀 Dùng luôn giờ từ database, không cần xử lý lại
                        let total = Number(item.total);

                        if (hour >= 0 && hour < 24) {
                            hourlyTotals[hour] += total;
                        }
                    });

                    // ✅ Không cần chia trung bình, giữ nguyên tổng doanh thu theo giờ
                    hourlyData = hourlyTotals.map((total, index) => ({
                        label: `${index}h`,
                        total: total
                    }));
                }

                return {
                    labels: hourlyData.map(item => item.label),
                    values: hourlyData.map(item => item.total)
                };
            } else if (type === 'weekday') {
                let filteredWeekData = [];

                // ✅ Danh sách thứ bằng tiếng Việt
                const weekDays = ["Chủ Nhật", "Thứ Hai", "Thứ Ba", "Thứ Tư", "Thứ Năm", "Thứ Sáu", "Thứ Bảy"];

                if (timeRange === 'today') {
                    const currentDayLabel = weekDays[today.getDay()];
                    filteredWeekData = dataThu.filter(item => item.label === currentDayLabel);
                } if (timeRange === 'yesterday') {
                    const yesterdayDayLabel = convertDayNumberToLabel(yesterday.getDay());
                    filteredWeekData = dataThu.filter(item => item.label == yesterdayDayLabel);
                }

                else if (timeRange === 'last7days') {
                    const startOfPeriod = new Date();
                    startOfPeriod.setDate(today.getDate() - 6); // Lấy 6 ngày trước hôm nay
                    startOfPeriod.setHours(0, 0, 0, 0); // Đặt về 00:00:00

                    const endOfPeriod = new Date();
                    endOfPeriod.setHours(23, 59, 59, 999); // Kết thúc vào 23:59:59 hôm nay

                    console.log(`📌 Lọc dữ liệu từ: ${startOfPeriod.toISOString()} đến ${endOfPeriod.toISOString()}`);

                    filteredWeekData = dataThu.filter(item => {
                        if (!item.min_date) {
                            console.warn("⚠️ Lỗi: item.min_date bị thiếu!", item);
                            return false;
                        }

                        let itemDate = new Date(item.min_date);
                        itemDate.setHours(0, 0, 0, 0); // Đảm bảo không bị lệch giờ khi so sánh

                        return itemDate >= startOfPeriod && itemDate <= endOfPeriod;
                    });

                    console.log("✅ Dữ liệu sau khi lọc (7 ngày qua, tính cả hôm nay):", filteredWeekData);
                }
                else if (timeRange === 'thismonth') {
                    const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
                    startOfMonth.setHours(0, 0, 0, 0);

                    const endOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                    endOfMonth.setHours(23, 59, 59, 999);

                    console.log(`📌 Lọc dữ liệu tháng này: ${startOfMonth.toISOString()} -> ${endOfMonth.toISOString()}`);

                    filteredWeekData = dataThu.filter(item => {
                        if (!item.min_date) {
                            console.warn("⚠️ Lỗi: item.min_date bị thiếu!", item);
                            return false;
                        }

                        let itemDate = new Date(item.min_date);
                        return itemDate >= startOfMonth && itemDate <= endOfMonth;
                    });

                    console.log("✅ Dữ liệu sau khi lọc (tháng này):", filteredWeekData);
                } else if (timeRange === 'lastmonth') {
                    const startOfLastMonth = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                    startOfLastMonth.setHours(0, 0, 0, 0);

                    const endOfLastMonth = new Date(today.getFullYear(), today.getMonth(), 0);
                    endOfLastMonth.setHours(23, 59, 59, 999);

                    console.log(`📌 Lọc dữ liệu tháng trước: ${startOfLastMonth.toISOString()} -> ${endOfLastMonth.toISOString()}`);

                    filteredWeekData = dataThu.filter(item => {
                        if (!item.min_date) {
                            console.warn("⚠️ Lỗi: item.min_date bị thiếu!", item);
                            return false;
                        }

                        let itemDate = new Date(item.min_date);
                        return itemDate >= startOfLastMonth && itemDate <= endOfLastMonth;
                    });


                    console.log("✅ Dữ liệu sau khi lọc (tháng trước):", filteredWeekData);
                }


                // 🔥 ✅ **GỘP DOANH THU THEO THỨ** (Giải quyết vấn đề cộng dồn tiền sai)
                let weekDataGrouped = {};
                filteredWeekData.forEach(item => {
                    if (!weekDataGrouped[item.label]) {
                        weekDataGrouped[item.label] = 0;
                    }
                    weekDataGrouped[item.label] += Number(item.total);
                });

                console.log("✅ Dữ liệu sau khi gộp tổng doanh thu theo thứ:", weekDataGrouped);

                return {
                    labels: Object.keys(weekDataGrouped), // Danh sách thứ
                    values: Object.values(weekDataGrouped) // Tổng doanh thu tương ứng
                };
            }

        }

        function loadChart(type = 'day', timeRange = 'today') {
            const { labels, values } = getChartData(type, timeRange);
            console.log("Labels:", labels);
            console.log("Values:", values);
            const ctx = document.getElementById('thongKeChart').getContext('2d');

            // Nếu có biểu đồ cũ, hủy trước khi vẽ mới
            if (chart) {
                chart.destroy();
            }

            chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Doanh Số (VND)',
                        data: values,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                        barPercentage: 0.8,        // Điều chỉnh độ rộng cột
                        categoryPercentage: 0.9,   // Điều chỉnh khoảng cách giữa các cột
                        minBarLength: 5            // Đảm bảo cột nhỏ vẫn hiển thị
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            suggestedMax: 12000000, // 👈 Đặt trục Y cao hơn 10 triệu một chút
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString('vi-VN') + ' đ';
                                }
                            }
                        }
                    }
                }
            });
            chart.update();
        }

        document.getElementById('filter-type').addEventListener('change', function() {
            const timeRange = document.getElementById('time-range').value;
            loadChart(this.value, timeRange);
        });

        document.getElementById('time-range').addEventListener('change', function() {
            const chartType = document.getElementById('filter-type').value;
            loadChart(chartType, this.value);
        });

        loadChart();
    </script>
@endsection
