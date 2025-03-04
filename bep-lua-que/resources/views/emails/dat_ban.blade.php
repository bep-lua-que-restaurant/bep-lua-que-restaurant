<!DOCTYPE html>
<html>

<head>
    <title>Xác nhận đặt bàn</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">

    <div
        style="max-width: 600px; margin: auto; background: #ffffff; padding: 20px; border-radius: 10px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);">

        <!-- Logo từ URL -->
        <div style="text-align: center;">
            <img src="https://th.bing.com/th/id/R.dd4e0ef500b38471ba3bfe674e66e7c1?rik=gG6GRr%2fmc1bAgw&pid=ImgRaw&r=0"
                alt="Nhà hàng ABC" width="200" style="border-radius: 10px;">
        </div>

        <h2 style="color: #333;">Xin chào {{ $customer->ho_ten }},</h2>
        <p style="font-size: 16px; color: #555;">Cảm ơn bạn đã đặt bàn tại <strong>Nhà hàng Bếp Lửa Quê</strong>. Dưới
            đây là
            thông tin đặt bàn của bạn:</p>

        <div style="background: #f9f9f9; padding: 15px; border-radius: 5px; margin: 15px 0;">
            <p><strong>Thời gian đến:</strong> {{ $danhSachBanDat[0]->thoi_gian_den }}</p>
            <p><strong>Số điện thoại:</strong> {{ $danhSachBanDat[0]->so_dien_thoai }}</p>
            <p><strong>Số người:</strong> {{ $danhSachBanDat[0]->so_nguoi }}</p>
            {{-- <h3 style="color: #333;">Bàn đã đặt:</h3> --}}
            {{-- <ul>
                @foreach ($danhSachBanDat as $datBan)
                    <li>Bàn số: {{ $datBan->ban_an_id }}</li>
                @endforeach
            </ul> --}}
        </div>

        <p style="text-align: center;">
            <a href="https://yourrestaurant.com"
                style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Xem
                chi tiết</a>
        </p>

        <p style="text-align: center; font-size: 14px; color: #999;">Nhà hàng Bếp Lửa Quê - Chúng tôi mong được phục vụ
            bạn!</p>
    </div>

</body>

</html>
