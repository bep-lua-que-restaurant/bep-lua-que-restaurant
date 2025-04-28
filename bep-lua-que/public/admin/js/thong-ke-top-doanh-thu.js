$(document).ready(function() {
    // let chart;
    let isCustomFilterApplied = false; // Biến kiểm tra xem có đang dùng bộ lọc tùy chỉnh không

    function updateChart(labels, data) {

        data = data.map(d => parseFloat(d) || 0);
        // if (chart) {
        //     chart.destroy();
        // }
        // let ctx = document.getElementById('thongKeTopDoanhThu').getContext('2d');
        // chart = new Chart(ctx, {
        //     type: 'bar',
        //     data: {
        //         labels: labels,
        //         datasets: [{
        //             label: 'Doanh thu (VND)',
        //             data: data,
        //             backgroundColor: 'rgba(54, 162, 235, 0.6)',
        //             borderColor: 'rgba(54, 162, 235, 1)',
        //             borderWidth: 1,
        //         }]
        //     },
        //     options: {
        //         responsive: true,
        //         scales: {
        //             x: {
        //                 title: {
        //                     display: true,
        //                     text: 'Thời gian (Giờ)'
        //                 }
        //             },
        //             y: {
        //                 beginAtZero: true
        //             }
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
            },
        });

        Highcharts.chart('thongKeTopDoanhThu', {
            chart: { type: 'column',
                style: {
                    fontFamily: 'Arial, sans-serif' // <- Font bạn muốn
                }},
            title: { text: ' ' },
            xAxis: {
                categories: labels,
                title: {
                    text: 'Thời gian (Giờ)'
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
                pointFormat: '{series.name}: <b>{point.y:,.0f} VND</b>'
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

    function formatDate(dateString) {
        let parts = dateString.split('-'); // Tách chuỗi theo dấu '-'
        return `${parts[2]}-${parts[1]}-${parts[0]}`; // Định dạng DD-MM-YYYY
    }

    function updateTitle(fromDate = null, toDate = null) {
        let chartType = $('#chartType').val();
        let title = chartType === 'gioBanChay' ? 'TOP DOANH THU CAO NHẤT THEO GIỜ' : 'TOP DOANH THU ÍT NHẤT THEO GIỜ';

        if (isCustomFilterApplied && fromDate && toDate) {
            $('#timeRange').text(`TỪ ${formatDate(fromDate)} ĐẾN ${formatDate(toDate)}`);
        } else {
            let filterType = $('#filterType').val();
            let timeText = filterType === 'year' ? 'TRONG NĂM' :
                filterType === 'month' ? 'TRONG THÁNG' :
                    filterType === 'week' ? 'TRONG TUẦN' : 'TRONG NGÀY';
            $('#timeRange').text(timeText);
        }

        $('#chartTypeTitle').text(title);
    }

    function fetchData(params) {
        $.ajax({
            url: "/thong-ke-top-doanh-thu",
            type: "GET",
            data: params,
            success: function(response) {
                updateChart(response.labels, response.data);
            },
            error: function() {
                alert('Lỗi tải dữ liệu, vui lòng thử lại.');
            }
        });
    }

    // Xử lý khi thay đổi bộ lọc năm/tháng/tuần/ngày
    $('#filterType').on('change', function() {
        isCustomFilterApplied = false; // Khi đổi filterType thì bỏ qua lọc tùy chỉnh
        let filterType = $('#filterType').val();
        let chartType = $('#chartType').val();
        updateTitle(); // Cập nhật tiêu đề theo filterType
        fetchData({
            filterType,
            chartType
        });
    });

    // Xử lý khi thay đổi chartType
    $('#chartType').on('change', function() {
        let chartType = $('#chartType').val();

        if (isCustomFilterApplied) {
            // Nếu đang dùng bộ lọc tùy chỉnh thì lấy dữ liệu theo ngày tháng đã chọn
            let fromDate = $('#startDate').val();
            let toDate = $('#endDate').val();
            fetchData({
                fromDate,
                toDate,
                chartType
            });
            updateTitle(fromDate, toDate);
        } else {
            // Nếu không có bộ lọc tùy chỉnh, lọc theo filterType
            let filterType = $('#filterType').val();
            fetchData({
                filterType,
                chartType
            });
            updateTitle();
        }
    });

    // Xử lý khi nhấn nút "Lọc" theo ngày tùy chỉnh
    $('#btnFilter').on('click', function() {
        let fromDate = $('#startDate').val();
        let toDate = $('#endDate').val();
        let chartType = $('#chartType').val();

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

        isCustomFilterApplied = true; // Đánh dấu là đang dùng lọc tùy chỉnh
        updateTitle(fromDate, toDate); // Cập nhật tiêu đề theo ngày tháng
        fetchData({
            fromDate,
            toDate,
            chartType
        });
    });

    // Cập nhật tiêu đề ban đầu theo dữ liệu từ server
    updateTitle();

    // Cập nhật biểu đồ ban đầu
    updateChart(window.labels, window.data);
});
