function capNhatBieuDoTronSoLuongKhach(namNay, namTruoc) {
    const ctx = document.getElementById('bieuDoTronSoLuongKhachHangNam').getContext('2d');

    // const total = namNay + namTruoc;
    const tiLe = namTruoc > 0 ? ((namNay - namTruoc) / namTruoc * 100).toFixed(1) : 0;

    new Chart(ctx, {
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

    // Cập nhật phần trăm nâng cao
    let chenhLechText = '';
    if (namNay > namTruoc) {
        chenhLechText = `Tăng ${tiLe}% so với năm trước`;
    } else if (namNay < namTruoc) {
        chenhLechText = `Giảm ${Math.abs(tiLe)}% so với năm trước`;
    } else {
        chenhLechText = 'Không thay đổi so với năm trước';
    }
    // const chenhLechText = tiLe >= 0 ? `Tăng ${tiLe}%` : `Giảm ${Math.abs(tiLe)}%`;
    // console.log(chenhLechText)
    document.getElementById('phanTramChenhLechSoLuongKhachHang').innerText = chenhLechText;
}
