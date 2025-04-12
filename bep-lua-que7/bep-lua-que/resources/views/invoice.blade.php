<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hoa don thanh toan</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            width: 280px; 
            margin: auto; 
            padding: 10px;
            border: 1px solid #000;
            background: #fff;
        }
        h2, h3, p { text-align: center; margin: 5px 0; }
        .store-name { font-size: 18px; font-weight: bold; }
        .invoice-title { font-size: 16px; font-weight: bold; margin-top: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 5px; text-align: left; font-size: 14px; border-bottom: 1px dashed #000; }
        th { text-align: center; }
        .total { font-weight: bold; font-size: 14px; text-align: right; }
        .footer { text-align: center; font-size: 12px; margin-top: 15px; }
        .barcode { text-align: center; margin-top: 10px; }
    </style>
</head>
<body>
    <p class="store-name">NHA HANG BEP LUA QUE</p>
    <p>Dia chi: 123 Nguyen Van A, TP. HN</p>
    <p>SDT: 0332 491 365</p>
    <p class="invoice-title">HOA DON THANH TOAN</p>
    <p><strong>Ban:</strong> {{ $data['ban_an_id'] }} | <strong>So Luong:</strong> {{ $data['so_nguoi'] }} nguoi</p>
    <p><strong> <strong>Khach:</strong> Khach le</p>
    <p><strong>Thanh toan:</strong> {{ $data['phuong_thuc_thanh_toan'] }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>San pham</th>
                <th>SL</th>
                <th>Gia</th>
                <th>TT</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['san_pham'] as $index => $sanPham)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $sanPham['ten_san_pham'] }}</td>
                <td>{{ $sanPham['so_luong'] }}</td>
                <td>{{ number_format($sanPham['don_gia']) }}</td>
                <td>{{ number_format($sanPham['tong_cong']) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p class="total">Tong tien: {{ number_format($data['tong_tien']) }} VND</p>
    <p class="total">Khach dua: {{ number_format($data['tien_khach_dua']) }} VND</p>
    <p class="total">Tien thua: {{ number_format($data['tien_thua']) }} VND</p>

    <p class="barcode"> === * THANK YOU * === </p>
</body>
</html>
