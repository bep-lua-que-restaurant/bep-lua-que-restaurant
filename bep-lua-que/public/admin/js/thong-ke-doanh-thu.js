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
                    pointPadding: 0.1,
                    groupPadding: 0.2,
                    borderWidth: 0,
                    pointWidth: 32
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
    capNhatBieuDo(window.labels, window.data, 'day');
});
