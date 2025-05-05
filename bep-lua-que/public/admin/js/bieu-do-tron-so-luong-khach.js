let bieuDoLuongKhach = null; // Biến toàn cục lưu trữ đối tượng biểu đồ khách hàng

function capNhatBieuDoTronSoLuongKhach(namNay, namTruoc) {
    const ctx = document.getElementById('bieuDoTronSoLuongKhachHangNam').getContext('2d');

    // Nếu đã có biểu đồ, hủy nó đi
    if (bieuDoLuongKhach) {
        bieuDoLuongKhach.destroy();
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
    bieuDoLuongKhach = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: [
                `Năm nay (${namNay.toLocaleString('vi-VN')} khách)`,
                `Năm trước (${namTruoc.toLocaleString('vi-VN')} khách)`
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
        chenhLechText = 'Không có dữ liệu số lượng khách cho cả hai năm';
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
    document.getElementById('phanTramChenhLechSoLuongKhachHang').innerText = chenhLechText;
}
