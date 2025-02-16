<div class="row">
    <h3>Chọn ngày trong tháng</h3>

    {{-- Lấy ngày hiện tại --}}
    @php
        use Carbon\Carbon;

        $currentDate = Carbon::today(); // Ngày hôm nay
        $selectedDate = request()->get('date', $currentDate->toDateString()); // Lấy ngày từ request hoặc mặc định là hôm nay
        $daysInMonth = Carbon::parse($selectedDate)->daysInMonth; // Số ngày trong tháng

        // Lấy danh sách ngày đã đặt bàn
        $bookedDates = $datBansMonth
            ->pluck('thoi_gian_den')
            ->map(function ($date) {
                return Carbon::parse($date)->format('Y-m-d'); // Chuyển định dạng về Y-m-d
            })
            ->toArray();
    @endphp

    {{-- Danh sách ngày còn lại trong tháng --}}
    <div class="calendar-grid">
        @for ($day = $currentDate->day; $day <= $daysInMonth; $day++)
            @php
                $fullDate = $currentDate->format('Y-m') . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
                $isBooked = in_array($fullDate, $bookedDates); // Kiểm tra ngày đã đặt
            @endphp

            <a href="{{ route('dat-ban.create', [
                'date' => $fullDate,
                'time' => '08:00', // Mặc định thời gian, có thể tùy chỉnh
                'ten_ban' => 'Không xác định', // Giá trị mặc định cho tên bàn
                'id_ban' => '', // Giá trị mặc định cho id bàn
            ]) }}"
                class="date-button {{ $isBooked ? 'booked-date' : '' }}">
                {{ $day }}/{{ $currentDate->format('m/Y') }}
            </a>
        @endfor
    </div>
</div>








<style>
    .calendar-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .date-button {
        padding: 10px;
        background-color: #449eff;
        color: white;
        border: none;
        cursor: pointer;
        border-radius: 5px;
        width: 120px;
    }


    .date-button:hover {
        background-color: #85b3e4;
    }

    .date-button {
        display: inline-block;
        padding: 10px;
        margin: 5px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: bold;
        transition: background-color 0.3s ease;
    }

    .booked-date {
        background-color: #ffd966;
        /* Màu vàng để phân biệt */
        color: #333;
        /* Chữ đậm hơn để nhìn rõ */
        border: 2px solid #ff9800;
        /* Viền cam nổi bật */
    }

    .booked-date:hover {
        background-color: #ffcc33;
        /* Đậm hơn khi hover */
    }
</style>
