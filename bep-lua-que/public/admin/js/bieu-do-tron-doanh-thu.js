let bieuDoDoanhThu = null; // Biến toàn cục lưu trữ đối tượng biểu đồ

function capNhatBieuDoTronDoanhThu(namNay, namTruoc) {
    console.log("Cập nhật biểu đồ tròn với dữ liệu:", namNay, namTruoc);  // Kiểm tra dữ liệu đầu vào

    const ctx = document.getElementById('bieuDoTronDoanhThuNam').getContext('2d');

    // Nếu đã có biểu đồ, hủy nó đi
    if (bieuDoDoanhThu) {
        bieuDoDoanhThu.destroy();
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

    // Cập nhật biểu đồ tròn mới
    bieuDoDoanhThu = new Chart(ctx, {
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

function formatTrieu(so) {
    return (so / 1_000_000).toFixed(1) + ' triệu';
}
