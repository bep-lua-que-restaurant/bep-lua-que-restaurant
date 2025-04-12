function capNhatBieuDoTronDoanhThu(namNay, namTruoc) {
    const ctx = document.getElementById('bieuDoTronDoanhThuNam').getContext('2d');

    // const total = namNay + namTruoc;
    const tiLe = namTruoc > 0 ? ((namNay - namTruoc) / namTruoc * 100).toFixed(1) : 0;


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
    document.getElementById('phanTramChenhLechDoanhThu').innerText = chenhLechText;
}
