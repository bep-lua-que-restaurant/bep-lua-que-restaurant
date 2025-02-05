<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giao diện Thu Ngân</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #b71c1c;
            color: white;
        }

        .container {
            max-width: 1200px;
        }

        .card {
            background: white;
            color: black;
            border-radius: 10px;
            padding: 20px;
        }

        .btn-custom {
            background: #388e3c;
            color: white;
        }

        .btn-custom:hover {
            background: #2e7d32;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <h2 class="text-center">Giao Diện Thu Ngân</h2>
        <div class="row mt-4">
            <!-- Danh sách bàn hoặc đơn hàng -->
            <div class="col-md-6">
                <div class="card">
                    <h4>Danh sách đơn hàng</h4>
                    <input type="text" class="form-control mb-2" placeholder="Tìm kiếm đơn hàng...">
                    <ul class="list-group">
                        <li class="list-group-item">Đơn #001 - Bàn 5</li>
                        <li class="list-group-item">Đơn #002 - Bàn 3</li>
                        <li class="list-group-item">Đơn #003 - Mang về</li>
                    </ul>
                </div>
            </div>
            <!-- Chi tiết đơn hàng -->
            <div class="col-md-6">
                <div class="card">
                    <h4>Chi tiết đơn hàng</h4>
                    <p>Khách hàng: Nguyễn Văn A</p>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Món ăn</th>
                                <th>Số lượng</th>
                                <th>Giá</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Phở bò</td>
                                <td>2</td>
                                <td>50,000đ</td>
                            </tr>
                            <tr>
                                <td>Bún chả</td>
                                <td>1</td>
                                <td>40,000đ</td>
                            </tr>
                        </tbody>
                    </table>
                    <h5 class="text-end">Tổng tiền: 90,000đ</h5>
                    <button class="btn btn-custom w-100">Thanh Toán</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
