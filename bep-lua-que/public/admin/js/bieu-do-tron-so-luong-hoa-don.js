function capNhatBieuDoTronSoLuongHoaDon(namNay, namTruoc) {
    const ctx = document.getElementById('bieuDoTronHoaDonNam').getContext('2d');

    const total = namNay + namTruoc;
    const tiLe = total > 0 ? ((namNay - namTruoc) / total * 100).toFixed(1) : 0;

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Năm nay', 'Năm trước'],
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

    // Cập nhật phần trăm
    const chenhLechText = tiLe >= 0 ? `Tăng ${tiLe}%` : `Giảm ${Math.abs(tiLe)}%`;
    console.log(chenhLechText)
    document.getElementById('phanTramChenhLechHoaDon').innerText = chenhLechText;
}
