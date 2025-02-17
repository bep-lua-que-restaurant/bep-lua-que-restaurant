@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2 class="text-center">Thống Kê Doanh Số</h2>

        <div class="d-flex justify-content-center">
            <select id="filter-type" class="form-select w-25">
                <option value="day">Theo Ngày</option>
                <option value="hour">Theo Giờ</option>
                <option value="weekday">Theo Thứ</option>
            </select>
            <select id="time-range" class="form-select w-25 ms-3">
                <option value="today">Hôm nay</option>
                <option value="yesterday">Hôm qua</option>
                <option value="last7days">7 Ngày Qua</option>
                <option value="thismonth">Tháng này</option>
                <option value="lastmonth">Tháng trước</option>
            </select>
        </div>

        <canvas id="thongKeChart"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let chart;
        const dataNgay = @json($dataNgay);
        const dataGio = @json($dataGio);
        const dataThu = @json($dataThu);

        function getChartData(type, timeRange) {
            let filteredData = [];

            // Filter data by time range
            if (timeRange === 'today') {
                filteredData = dataNgay.filter(item => item.label === new Date().toLocaleDateString());
            } else if (timeRange === 'yesterday') {
                let yesterday = new Date();
                yesterday.setDate(yesterday.getDate() - 1);
                filteredData = dataNgay.filter(item => item.label === yesterday.toLocaleDateString());
            } else if (timeRange === 'last7days') {
                let sevenDaysAgo = new Date();
                sevenDaysAgo.setDate(sevenDaysAgo.getDate() - 7);
                filteredData = dataNgay.filter(item => new Date(item.label) >= sevenDaysAgo);
            } else if (timeRange === 'thismonth') {
                let currentMonth = new Date().getMonth() + 1;
                filteredData = dataNgay.filter(item => new Date(item.label).getMonth() + 1 === currentMonth);
            } else if (timeRange === 'lastmonth') {
                let lastMonth = new Date();
                lastMonth.setMonth(lastMonth.getMonth() - 1);
                filteredData = dataNgay.filter(item => new Date(item.label).getMonth() + 1 === lastMonth.getMonth() + 1);
            }

            if (type === 'day') {
                return {
                    labels: filteredData.map(item => item.label),
                    values: filteredData.map(item => item.total)
                };
            } else if (type === 'hour') {
                return {
                    labels: dataGio.map(item => `${item.label}h`),
                    values: dataGio.map(item => item.total)
                };
            } else {
                return {
                    labels: dataThu.map(item => item.label),
                    values: dataThu.map(item => item.total)
                };
            }
        }

        function loadChart(type = 'day', timeRange = 'today') {
            const { labels, values } = getChartData(type, timeRange);

            if (chart) chart.destroy();

            const ctx = document.getElementById('thongKeChart').getContext('2d');
            chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Doanh Số (VND)',
                        data: values,
                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                        borderColor: 'rgba(75, 192, 192, 1)',
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
