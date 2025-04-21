function capNhatBieuDoTronDoanhThu(namNay, namTruoc) {
    const ctx = document.getElementById('bieuDoTronDoanhThuNam').getContext('2d');

    function formatTrieu(so) {
        return (so / 1_000_000).toFixed(1) + ' triệu';
    }

    // Đảm bảo giá trị là số
    namNay = Number(namNay);
    namTruoc = Number(namTruoc);

    // Tính tỉ lệ thay đổi
    let tiLe = 0;
    if (namTruoc === 0 && namNay > 0) {
        tiLe = 100;
    } else if (namTruoc > 0) {
        tiLe = ((namNay - namTruoc) / namTruoc * 100).toFixed(1);
    }

    // Cập nhật biểu đồ tròn
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: [
                `Năm nay (${formatTrieu(namNay)})`,
                `Năm trước (${formatTrieu(namTruoc)})`
            ],
            datasets: [{
                data: [namNay, namTruoc],
                backgroundColor: ['rgba(54, 162, 235, 1)', '#4caf50']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Xử lý hiển thị phần trăm chênh lệch
    let chenhLechText = '';
    if (namTruoc === 0 && namNay === 0) {
        chenhLechText = 'Không có dữ liệu doanh thu cho cả hai năm';
    } else if (namTruoc === 0 && namNay > 0) {
        chenhLechText = 'Tăng 100% so với năm trước';
    } else if (namNay > namTruoc) {
        chenhLechText = `Tăng ${tiLe}% so với năm trước`;
    } else if (namNay < namTruoc) {
        chenhLechText = `Giảm ${Math.abs(tiLe)}% so với năm trước`;
    } else {
        chenhLechText = 'Không thay đổi so với năm trước';
    }

    // Hiển thị ra giao diện
    document.getElementById('phanTramChenhLechDoanhThu').innerText = chenhLechText;
}
