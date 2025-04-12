<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa Đơn</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }

        h2 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>HÓA ĐƠN</h2>
        <p><strong>Mã hóa đơn:</strong> {{ $hoaDon->ma_hoa_don }}</p>
        <p><strong>Khách hàng:</strong> {{ $hoaDon->ten_khach_hang }}</p>
        <p><strong>Ngày lập:</strong> {{ $hoaDon->created_at->format('d/m/Y') }}</p>

        <table>
            <thead>
                <tr>
                    <th>Tên món</th>
                    <th>Số lượng</th>
                    <th>Đơn giá</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @if ($chiTietHoaDon->count() > 0)
                    @foreach ($chiTietHoaDon as $ct)
                        <tr>
                            <td>{{ $ct->ten_mon }}</td>
                            <td>{{ $ct->so_luong }}</td>
                            <td>{{ number_format($ct->don_gia, 0, ',', '.') }} VND</td>
                            <td>{{ number_format($ct->so_luong * $ct->gia, 0, ',', '.') }} VND</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4" style="text-align: center;">Không có dữ liệu</td>
                    </tr>
                @endif


            </tbody>
        </table>

        <p><strong>Tổng tiền:</strong> {{ number_format($hoaDon->tong_tien, 0, ',', '.') }} VND</p>
    </div>
</body>

</html>
