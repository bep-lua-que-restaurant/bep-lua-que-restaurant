@php
    use Carbon\Carbon;

    $today = Carbon::today(); // Ngày hôm nay
    $endOfWeek = Carbon::parse('Sunday')->endOfDay(); // Lấy ngày Chủ Nhật, kết thúc vào cuối ngày

    $daysOfWeek = ['Chủ Nhật', 'Thứ Hai', 'Thứ Ba', 'Thứ Tư', 'Thứ Năm', 'Thứ Sáu', 'Thứ Bảy'];
    $dates = [];

    // Lấy tất cả các ngày từ hôm nay đến hết tuần
    for ($i = 0; $i <= $endOfWeek->diffInDays($today); $i++) {
        $date = Carbon::now()->addDays($i)->format('Y-m-d');
        $dayOfWeek = $daysOfWeek[Carbon::parse($date)->dayOfWeek];
        $dates[] = ['date' => $date, 'day' => $dayOfWeek];
    }
@endphp

<div class="row">
    <!-- Cột bên trái (Cố định, giữ căn chỉnh với bên phải) -->
    <div class="col-3" style="position: sticky; left: 0; background: white; z-index: 1">
        <div class="left-column">
            <div class="left-header"
                style="min-height: 95px;display: flex; align-items: center; justify-content: center;">Phòng Bàn</div>
            @foreach ($banPhong as $item)
                <div class="left-item">{{ $item->ten_ban }}</div>
            @endforeach
        </div>
    </div>

    <!-- Cột bên phải (Thanh cuộn ngang duy nhất) -->
    <div class="col-9 overflow-x-auto">
        <div class="right-container">
            <div style="white-space: nowrap;">
                @foreach ($dates as $d)
                    <div style="display: inline-block;">
                        <div>
                            <div class="thungaythang text-center">
                                <b>{{ $d['day'] }} {{ \Carbon\Carbon::parse($d['date'])->format('d/m/Y') }}</b>
                            </div>

                            <div class="grid-row">
                                @for ($i = 8; $i < 23; $i++)
                                    <div class="grid-item time-item">
                                        {{ sprintf('%02d', $i) }}:00
                                    </div>
                                @endfor
                            </div>

                            @foreach ($banPhong as $item)
                                <div class="grid-row">
                                    @for ($j = 8; $j <= 22; $j++)
                                        @php
                                            $timeSlot = sprintf('%02d', $j) . ':00';
                                            // Kiểm tra thông tin đơn đặt bàn cho ngày và thời gian trong tuần
                                            $datBanWeek = $datBansWeek->firstWhere(function ($datBan) use (
                                                $item,
                                                $d,
                                                $timeSlot,
                                            ) {
                                                return $datBan->ban_an_id == $item->id &&
                                                    Carbon::parse($datBan->thoi_gian_den)->format('Y-m-d H') ==
                                                        Carbon::parse($d['date'] . ' ' . $timeSlot)->format('Y-m-d H');
                                            });
                                        @endphp

                                        <!-- Truyền tham số vào URL với đúng định dạng -->
                                        <a href="{{ route('dat-ban.create', [
                                            'ten_ban' => $item->ten_ban ?? 'Không xác định',
                                            'id_ban' => $item->id ?? '',
                                            'time' => $timeSlot,
                                            'date' => $d['date'],
                                        ]) }}"
                                            class="grid-item time-item 
                                @if ($datBanWeek) @switch($datBanWeek->trang_thai)
                                        @case('dang_xu_ly')
                                            bg-warning
                                            @break
                                        @case('xa_nhan')
                                            bg-success
                                            @break
                                        @case('da_huy')
                                            bg-danger
                                            @break
                                        @default
                                            bg-secondary
                                    @endswitch @endif">

                                            <!-- Hiển thị giờ phút nếu có -->
                                            @if ($datBanWeek)
                                                <span>{{ Carbon::parse($datBanWeek->thoi_gian_den)->format('H:i') }}</span>
                                            @endif
                                        </a>
                                    @endfor
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach


            </div>
        </div>
    </div>
</div>




<style>
    .thungaythang {
        display: flex;
        justify-content: space-around;
        padding: 10px 0px 10px 0px;
        border: 1px solid rgb(173, 84, 251);
    }

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
