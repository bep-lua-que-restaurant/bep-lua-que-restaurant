function capNhatBieuDoTronDoanhThu(namNay, namTruoc) {
    const ctx = document.getElementById('bieuDoTronDoanhThuNam').getContext('2d');

    function formatTrieu(so) {
        return (so / 1_000_000).toFixed(1) + ' triệu';
    }

    // const total = namNay + namTruoc;
    const tiLe = namTruoc > 0 ? ((namNay - namTruoc) / namTruoc * 100).toFixed(1) : 0;

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

    // Cập nhật phần trăm nâng cao
    let chenhLechText = '';
    if (namNay > namTruoc) {
        chenhLechText = `Tăng ${tiLe}% so với năm trước`;
    } else if (namNay < namTruoc) {
        chenhLechText = `Giảm ${Math.abs(tiLe)}% so với năm trước`;
    } else {
        chenhLechText = 'Không thay đổi so với năm trước';
    }
    // console.log(chenhLechText)
    document.getElementById('phanTramChenhLechDoanhThu').innerText = chenhLechText;
}
