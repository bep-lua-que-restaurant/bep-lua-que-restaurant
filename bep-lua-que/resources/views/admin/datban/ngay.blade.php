<div class="row">
    <!-- Cột bên trái (Cố định, giữ căn chỉnh với bên phải) -->
    <div class="col-3" style="position: sticky; left: 0; background: white; z-index: 1">
        <div class="left-column">
            <div class="left-header text-center" style="height: 97px; line-height: 77px;">Phòng Bàn</div>
            @foreach ($banPhong as $item)
                <div class="left-item">{{ $item->ten_ban }}</div>
            @endforeach
        </div>
    </div>

    <!-- Cột bên phải (Thanh cuộn ngang duy nhất) -->
    <div class="col-9 overflow-x-auto">
        <div class="right-container">
            @php
                use Carbon\Carbon;

                $today = Carbon::today(); // Ngày hôm nay
                $fullDate = $today->format('Y-m-d'); // Lấy ngày hiện tại dạng YYYY-MM-DD
            @endphp

            <!-- Hiển thị ngày hôm nay -->
            <div class="text-center d-flex justify-content-evenly" style="padding: 0px 10px 10px 0px">
                <h4 style="padding-left:200px">Hôm nay: {{ $today->format('d/m/Y') }}</h4>
            </div>

            <!-- Hàng giờ -->
            <div class="grid-row">
                @for ($i = 8; $i < 23; $i++)
                    <!-- Hiển thị giờ từ 8:00 đến 22:00 -->
                    <div class="grid-item time-item">{{ sprintf('%02d', $i) }}:00</div>
                @endfor
            </div>

            <!-- Dữ liệu bên phải (phòng và số thứ tự) -->
            @foreach ($banPhong as $item)
                <div class="grid-row">
                    @for ($j = 8; $j <= 22; $j++)
                        @php
                            $timeSlot = sprintf('%02d', $j) . ':00'; // Giờ tương ứng
                            $datBanToday = $datBansToday->firstWhere(function ($datBan) use (
                                $item,
                                $fullDate,
                                $timeSlot,
                            ) {
                                return $datBan->ban_an_id == $item->id &&
                                    Carbon::parse($datBan->thoi_gian_den)->format('Y-m-d H') ==
                                        Carbon::parse($fullDate . ' ' . $timeSlot)->format('Y-m-d H');
                            });
                        @endphp

                        <a href="{{ route('dat-ban.create', [
                            'ten_ban' => $item->ten_ban ?? 'Không xác định',
                            'id_ban' => $item->id ?? '',
                            'time' => $timeSlot,
                            'date' => $fullDate,
                        ]) }}"
                            class="grid-item time-item
                    @if ($datBanToday) @switch($datBanToday->trang_thai)
                        @case('dang_xu_ly') bg-warning @break
                        @case('xa_nhan') bg-success @break
                        @case('da_huy') bg-danger @break
                        @default bg-secondary
                    @endswitch @endif">

                            <!-- Hiển thị giờ phút nếu có -->
                            @if ($datBanToday)
                                <span>{{ Carbon::parse($datBanToday->thoi_gian_den)->format('H:i') }}</span>
                            @endif
                        </a>
                    @endfor
                </div>
            @endforeach

        </div>
    </div>
</div>



<style>
    /* Thanh cuộn ngang duy nhất */
    .overflow-x-auto {
        overflow-x: auto;
        white-space: nowrap;
    }

    /* Căn chỉnh phần bên trái */
    .left-column {
        display: flex;
        flex-direction: column;
        padding-right: 0;
        /* Đảm bảo không có khoảng cách phải ở cột bên trái */
    }

    .left-header,
    .left-item {
        border: 1px solid rgb(173, 84, 251);
        padding: 10px;
        text-align: center;
        width: 220px;
        /* Đảm bảo đồng bộ với cột bên phải */
        min-height: 50px;
    }

    /* Căn chỉnh toàn bộ phần bên phải */
    .right-container {
        display: flex;
        flex-direction: column;
        padding-left: 0;
        /* Đảm bảo không có khoảng cách trái ở cột bên phải */
    }

    /* Mỗi hàng trong bảng */
    .grid-row {
        display: grid;
        grid-template-columns: repeat(15, 1fr);
        /* 15 cột đều nhau */
        gap: 0;
        /* Loại bỏ khoảng cách thừa giữa các ô */
        width: 100%;
        /* Chắc chắn hàng sẽ lấp đầy không gian */
    }

    /* Ô trong bảng */
    .grid-item {
        border: 1px solid rgb(215, 168, 255);
        padding: 10px;
        text-align: center;
        min-height: 50px;
        width: 100%;
        /* Đảm bảo tất cả ô có cùng chiều rộng */
        display: flex;
        justify-content: center;
        align-items: center;
    }

    /* Chiều rộng mặc định cho ô giờ */
    .time-item {
        width: 120px;
        /* Bạn có thể thay đổi giá trị này để điều chỉnh chiều rộng */
    }

    /* Style cho link trong các ô */
    .grid-item a {
        text-decoration: none;
        color: black;
        /* Màu chữ cho link */
    }

    /* Thêm style hover cho link */
    .grid-item a:hover {
        color: blue;
    }
</style>
