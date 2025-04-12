<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<canvas id="salesChart" style="margin-top: 20px;"></canvas>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let labels = @json($labels ?? []);
        let datasets = @json($datasets ?? []);

        let ctx = document.getElementById('salesChart').getContext('2d');

        // Kiểm tra nếu biểu đồ cũ đã tồn tại thì hủy nó trước khi vẽ lại
        // if (window.salesChart instanceof Chart) {
        //     window.salesChart.destroy();
        // }

        window.salesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Số lượng bán',
                    data: datasets,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
