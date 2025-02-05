<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giao Diện Bếp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .kitchen-container {
            display: flex;
            gap: 20px;
            padding: 20px;
        }

        .order-column {
            flex: 1;
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .order-header {
            font-weight: bold;
            padding-bottom: 10px;
            border-bottom: 2px solid #dc3545;
            color: #dc3545;
        }

        .order-card {
            border-left: 5px solid #dc3545;
            margin-top: 10px;
            padding: 10px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <h2 class="text-center">Giao Diện Bếp</h2>
    <div class="container-fluid">
        <div class="kitchen-container">
            <div class="order-column">
                <div class="order-header">Đơn hàng chờ chế biến</div>
                <div class="order-card">Món: Phở bò - Bàn 5</div>
                <div class="order-card">Món: Gà rán - Bàn 2</div>
            </div>
            <div class="order-column">
                <div class="order-header">Đang chế biến</div>
                <div class="order-card">Món: Cơm gà - Bàn 3</div>
            </div>
            <div class="order-column">
                <div class="order-header">Đã hoàn thành</div>
                <div class="order-card">Món: Bún chả - Bàn 1</div>
            </div>
        </div>
    </div>

</body>

</html>
