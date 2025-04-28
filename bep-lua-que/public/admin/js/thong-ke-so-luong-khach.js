$(document).ready(function () {
    // let chart;

    function updateChart(labels, data, formatType) {
        data = data.map(d => parseFloat(d) || 0);
        // if (chart) {
        //     chart.destroy();
        // }
        // let ctx = document.getElementById('thongKeSoLuongKhach').getContext('2d');
        // chart = new Chart(ctx, {
        //     type: 'line',
        //     data: {
        //         labels: labels,
        //         datasets: [{
        //             label: 'Số lượng khách',
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
        //             x: {
        //                 title: {
        //                     display: true,
        //                     text: formatType === 'day' ? 'Ngày' : formatType === 'month' ? 'Tháng' : formatType === 'year' ? 'Năm' : 'Tuần'
        //                 }
        //             },
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


        Highcharts.chart('thongKeSoLuongKhach', {
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
                    text: 'Số lượng khách'
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
                name: 'Số lượng khách',
                data: data,
                color: '#36A2EB'
            }],
            credits: { enabled: false },

            // responsive: {
            //     rules: [{
            //         condition: {
            //             maxWidth: 500
            //         },
            //         chartOptions: {
            //             legend: {
            //                 layout: 'horizontal',
            //                 align: 'center',
            //                 verticalAlign: 'bottom'
            //             }
            //         }
            //     }]
            // }

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
    updateChart(window.labels, window.data, 'day');
});
