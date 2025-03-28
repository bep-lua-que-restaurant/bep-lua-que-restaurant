@extends('gdnhanvien.datban.layout')

@section('content')
    @php
        use Carbon\Carbon;
    @endphp
    <div class="container">
        <h1 class="text-center my-4">Quản lý Đặt Bàn</h1>

        <!-- Bảng đặt bàn -->
        <div id="ngay-content" class="table-responsive">
            <div class="table-responsive">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center align-middle">
                        <thead class="table-dark">
                            <tr>
                                <!-- Cố định cột "Bàn / Giờ" -->
                                <th class="sticky-col">Bàn / Giờ</th>
                                @for ($i = 8; $i <= 22; $i++)
                                    <th>{{ sprintf('%02d', $i) }}:00</th>
                                    <th>{{ sprintf('%02d', $i) }}:30</th>
                                @endfor
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($banAns as $banAn)
                                <tr>
                                    <!-- Cố định cột tên bàn -->
                                    <td class="fw-bold sticky-col">{{ $banAn->ten_ban }}</td>

                                    <!-- Các khung giờ từ 8:00 - 22:30 -->
                                    {{-- Table hiển thị các ô thời gian --}}
                                    @for ($i = 8; $i <= 22; $i++)
                                        @foreach ([0, 30] as $minute)
                                            @php
                                                $thoiGianHienTai = sprintf('%02d:%02d', $i, $minute);
                                                $class = '';
                                                $isDisabled = false;
                                                $buttonText = '+';

                                                // Kiểm tra datBanCurrent
                                                $isCurrentBooked = $datBanCurrent->contains(function ($datBan) use (
                                                    $banAn,
                                                    $thoiGianHienTai,
                                                ) {
                                                    if ($datBan->ban_an_id !== $banAn->id) {
                                                        return false;
                                                    }

                                                    $thoiGianDen = date('H:i', strtotime($datBan->thoi_gian_den));
                                                    $gioDuKien = $datBan->gio_du_kien;

                                                    return $thoiGianHienTai >= $thoiGianDen &&
                                                        $thoiGianHienTai < $gioDuKien;
                                                });

                                                // Kiểm tra datBansOther
                                                $isOtherBooked = $datBansOther->contains(function ($datBan) use (
                                                    $banAn,
                                                    $thoiGianHienTai,
                                                ) {
                                                    if ($datBan->ban_an_id !== $banAn->id) {
                                                        return false;
                                                    }

                                                    $thoiGianDen = date('H:i', strtotime($datBan->thoi_gian_den));
                                                    $gioDuKien = $datBan->gio_du_kien;

                                                    return $thoiGianHienTai >= $thoiGianDen &&
                                                        $thoiGianHienTai < $gioDuKien;
                                                });

                                                // Gán class màu sắc cho slot
                                                if ($isCurrentBooked) {
                                                    $class = 'bg-info text-white'; // Màu xanh cho datBanCurrent
                                                } elseif ($isOtherBooked) {
                                                    $class = 'bg-danger text-white'; // Màu đỏ cho datBansOther
                                                    $isDisabled = true;
                                                    $buttonText = 'X';
                                                }
                                            @endphp

                                            <td class="time-slot {{ $class }} {{ $isDisabled ? 'disabled' : '' }}"
                                                data-ban="{{ $banAn->id }}" data-time="{{ $thoiGianHienTai }}"
                                                {{ $isDisabled ? 'data-disabled="true"' : '' }}
                                                data-current="{{ $isCurrentBooked ? 'true' : 'false' }}">

                                                {{-- Ô màu đỏ chỉ hiển thị X, không có nút --}}
                                                @if ($isOtherBooked)
                                                    <span>{{ $buttonText }}</span>
                                                @else
                                                    {{-- Ô màu xanh hoặc thường đều có nút để click --}}
                                                    <button
                                                        class="btn btn-sm {{ $isCurrentBooked ? 'btn-info' : 'btn-outline-success' }} select-slot"
                                                        data-ban="{{ $banAn->id }}" data-time="{{ $thoiGianHienTai }}"
                                                        {{ $isDisabled ? 'disabled' : '' }}>
                                                        {{ $buttonText }}
                                                    </button>
                                                @endif
                                            </td>
                                        @endforeach
                                    @endfor
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
        {{ $banAns->links('pagination::bootstrap-5') }}




        <button id="open-modal-btn" class="btn btn-primary fixed-bottom m-3 right-align">
            Xem chi tiết đặt bàn
        </button>
        <style>
            #open-modal-btn {
                width: max-content;
                float: left;
            }
        </style>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            data-bs-backdrop="static">



            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title text-center" id="exampleModalLabel">Chi tiết đặt bàn</h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <!-- Form để gửi dữ liệu -->
                    <form id="booking-form" action="{{ route('dat-ban.update', $maDatBan) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Các input ẩn để đẩy dữ liệu vào form -->
                        <input type="hidden" name="ban_an_ids" id="banAnIds">
                        <input type="hidden" name="thoi_gian_den" id="thoiGianDen">
                        <input type="hidden" name="gio_du_kien" id="gioDuKien">
                        <input type="hidden" name="khach_hang_id" value="{{ $datBan->khach_hang_id }}">
                        <input type="hidden" name="so_dien_thoai" value="{{ $datBan->khachHang->so_dien_thoai }}">
                        <input type="hidden" name="ngay_den" id="ngay_den"
                            value="{{ $datBan->thoi_gian_den ? date('Y-m-d', strtotime($datBan->thoi_gian_den)) : '' }}">



                        <!-- Nội dung modal -->
                        <div class="modal-body">
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="customerName" class="form-label fw-bold">Họ tên:</label>
                                    <input type="text" class="form-control" id="customerName" name="customer_name"
                                        value="{{ $datBan->khachHang->ho_ten }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="customerEmail" class="form-label fw-bold">Email:</label>
                                    <input type="email" class="form-control" id="customerEmail" name="customer_email"
                                        value="{{ $datBan->khachHang->email }}" readonly>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="soNguoiInput" class="form-label fw-bold">Số người:</label>
                                    <input type="number" class="form-control" id="soNguoiInput" name="so_nguoi"
                                        min="1" value="{{ $datBan->so_nguoi }}">
                                </div>
                            </div>

                            <!-- Mô tả -->
                            <div class="mb-3">
                                <label for="description" class="form-label fw-bold">Mô tả:</label>
                                <textarea class="form-control" name="mo_ta" id="description" rows="3">{{ $datBan->mo_ta }}</textarea>
                            </div>

                            <!-- Dữ liệu từ JS -->
                            <div id="modal-body">
                                <!-- Nội dung sẽ được cập nhật bởi JS -->
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary mt-3" id="confirm-btn">Xác nhận</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </div>

    <script>
        let selectedSlots = [];

        // Tự động thêm vào selectedSlots nếu là datBanCurrent
        // Tự động thêm vào selectedSlots nếu là datBanCurrent
        document.querySelectorAll('.time-slot[data-current="true"]').forEach(slot => {
            const banId = slot.getAttribute('data-ban');
            const time = slot.getAttribute('data-time');
            selectedSlots.push({
                banId,
                time
            });

            const button = slot.querySelector('.select-slot');
            if (button) {
                button.classList.remove('btn-outline-success');
                button.classList.add('btn-success');
            }
        });

        // 👉 Gọi ngay để cập nhật modal khi tải trang
        updateModalData();


        // Đặt khoảng cách giữa các giờ là 30 phút
        const timeGap = 30;

        // Hàm cộng thêm 30 phút vào thời gian
        function addMinutesToTime(time, minutesToAdd) {
            const [hour, minute] = time.split(':').map(Number);
            const date = new Date();
            date.setHours(hour);
            date.setMinutes(minute + minutesToAdd);

            const newHour = date.getHours().toString().padStart(2, '0');
            const newMinute = date.getMinutes().toString().padStart(2, '0');
            return `${newHour}:${newMinute}`;
        }

        // Kiểm tra xem thời gian A có cách nhau ±30 phút với các thời gian đã chọn chưa
        function isAdjacentToAnySelectedTime(time, banId) {
            // Lọc các slot có cùng banId
            const sameBanSlots = selectedSlots.filter(slot => slot.banId === banId);

            for (let i = 0; i < sameBanSlots.length; i++) {
                const slotTime = sameBanSlots[i].time;
                const slotPlus30 = addMinutesToTime(slotTime, timeGap);
                const slotMinus30 = addMinutesToTime(slotTime, -timeGap);

                if (time === slotPlus30 || time === slotMinus30) {
                    return true; // Nếu thời gian A cách ±30 phút thì hợp lệ
                }
            }

            return false; // Nếu không tìm thấy thì không hợp lệ
        }

        // Xử lý khi click vào slot
        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('select-slot')) {
                const banId = event.target.getAttribute('data-ban');
                const time = event.target.getAttribute('data-time');

                const index = selectedSlots.findIndex(slot =>
                    slot.banId === banId && slot.time === time
                );

                if (index > -1) {
                    // Đã chọn -> Hủy chọn
                    selectedSlots.splice(index, 1);
                    event.target.classList.remove('btn-success');
                    event.target.classList.add('btn-outline-success');
                } else {
                    // Kiểm tra xem có slot nào với banId này chưa?
                    const sameBanSlots = selectedSlots.filter(slot => slot.banId === banId);

                    if (sameBanSlots.length > 0) {
                        // Nếu đã có slot với banId này thì kiểm tra thời gian liên tiếp
                        if (!isAdjacentToAnySelectedTime(time, banId)) {
                            alert(
                                `Giờ ${time} không hợp lệ. Vui lòng chọn giờ cách nhau ±30 phút với giờ đã chọn trước đó.`
                            );
                            return;
                        }
                    }

                    // Nếu chưa có hoặc đã kiểm tra hợp lệ -> Thêm vào
                    selectedSlots.push({
                        banId,
                        time
                    });
                    event.target.classList.remove('btn-outline-success');
                    event.target.classList.add('btn-success');
                }

                updateModalData(); // Cập nhật lại modal khi chọn/hủy
            }
        });






        // Xử lý hợp nhất thời gian cho cùng id bàn
        function mergeSlots() {
            const merged = {};

            selectedSlots.forEach(slot => {
                const {
                    banId,
                    time
                } = slot;
                const endTime = addMinutesToTime(time, 25);

                if (!merged[banId]) {
                    merged[banId] = {
                        start: time,
                        end: endTime
                    };
                } else {
                    if (time < merged[banId].start) merged[banId].start = time;
                    if (endTime > merged[banId].end) merged[banId].end = endTime;
                }
            });

            return Object.entries(merged).map(([banId, {
                start,
                end
            }]) => ({
                banId,
                start,
                end
            }));
        }

        // Hàm cộng thêm 25 phút vào thời gian
        function addMinutesToTime(time, minutesToAdd) {
            const [hour, minute] = time.split(':').map(Number);
            const date = new Date();
            date.setHours(hour);
            date.setMinutes(minute + minutesToAdd);

            const newHour = date.getHours().toString().padStart(2, '0');
            const newMinute = date.getMinutes().toString().padStart(2, '0');
            return `${newHour}:${newMinute}`;
        }

        // Cập nhật dữ liệu vào modal
        function updateModalData() {
            const mergedSlots = mergeSlots();
            const modalBody = document.getElementById('modal-body');
            modalBody.innerHTML = '';

            if (mergedSlots.length === 0) {
                modalBody.innerHTML = '<p>Chưa có bàn nào được chọn.</p>';
                return;
            }

            mergedSlots.forEach(slot => {
                modalBody.innerHTML += `
            <div>
                <strong>Bàn ${slot.banId}:</strong> ${slot.start} - ${slot.end}
            </div>
        `;
            });
        }




        // Mở modal khi click vào nút mở modal
        // Mở modal khi nhấn vào nút mở modal
        document.addEventListener('DOMContentLoaded', () => {
            const modalElement = document.getElementById('exampleModal');
            const modal = new bootstrap.Modal(modalElement);

            document.getElementById('open-modal-btn').addEventListener('click', () => {
                modal.show();
            });

            // Đặt lại focus khi modal mở
            modalElement.addEventListener('shown.bs.modal', () => {
                modalElement.focus();
            });
        });



        //Xử lý sự kiện khi nhấn vào nút xác nhận



        document.getElementById('confirm-btn').addEventListener('click', (event) => {
            event.preventDefault(); // Chặn hành động mặc định để xử lý thủ công

            const mergedSlots = mergeSlots();

            if (mergedSlots.length === 0) {
                alert('Vui lòng chọn ít nhất một bàn!');
                return;
            }


            // Sắp xếp các slot theo thời gian bắt đầu
            mergedSlots.sort((a, b) => a.start.localeCompare(b.start));

            // Kiểm tra khoảng trống giữa các slot
            for (let i = 0; i < mergedSlots.length - 1; i++) {
                const endTime = mergedSlots[i].end;
                const nextStartTime = mergedSlots[i + 1].start;

                if (endTime < nextStartTime) {
                    alert(`Có khoảng trống từ ${endTime} đến ${nextStartTime}. Vui lòng chọn đầy đủ!`);
                    return;
                }
            }

            // Format lại giờ theo định dạng HH:MM:SS
            const formatTime = (time) => {
                if (!time) return null;
                const [hour, minute] = time.split(':');
                return `${hour}:${minute}:00`;
            };


            const soNguoiInput = document.getElementById('soNguoiInput');
            const soNguoi = soNguoiInput ? soNguoiInput.value : '';

            // Đẩy dữ liệu vào input hidden
            document.getElementById('banAnIds').value = JSON.stringify(mergedSlots.map(slot => slot.banId));
            document.getElementById('thoiGianDen').value = mergedSlots.length > 0 ? mergedSlots[0].start : '';
            document.getElementById('gioDuKien').value = mergedSlots.length > 0 ? mergedSlots[0].end : '';

            console.log({
                banAnIds: mergedSlots.map(slot => slot.banId),
                thoiGianDen: mergedSlots.length > 0 ? mergedSlots[0].start : '',
                gioDuKien: mergedSlots.length > 0 ? mergedSlots[0].end : '',
                khachHangId: document.getElementById('khachHangIdInput')?.value,
                soDienThoai: document.getElementById('soDienThoaiInput')?.value
            });
            document.getElementById('booking-form').submit();

            const formData = new FormData(document.getElementById('booking-form'));

            fetch(`/dat-ban/${document.getElementById('maDatBanInput').value}`, {
                    method: 'POST', // Laravel xử lý PUT/PATCH qua _method
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        window.location.reload(); // Reload trang hoặc chuyển hướng nếu cần
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Đã xảy ra lỗi khi gửi dữ liệu!');
                });
        });
    </script>


    <style>
        /* Cố định tiêu đề khi cuộn */
        thead th {
            position: sticky;
            top: 0;
            background-color: #343a40;
            color: #fff;
            z-index: 3;
        }

        /* Cố định cột tên bàn */
        .sticky-col {
            position: sticky;
            left: 0;
            background-color: #f8f9fa;
            z-index: 2;
            white-space: nowrap;
        }

        /* Kích thước bảng co giãn theo nội dung */
        .table {
            width: max-content;
        }

        /* Kiểu cho các ô giờ */
        .time-slot {
            background-color: #e9ecef;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .time-slot:hover {
            background-color: #dee2e6;
        }

        .time-slot.active {
            background-color: #ffc107;
            color: #fff;
        }






        .time-slot.bg-danger {
            background-color: #dc3545 !important;
            /* Màu đỏ */
            pointer-events: none;
            /* Ngăn click */
            cursor: not-allowed;
            opacity: 0.6;
            text-align: center;
            font-weight: bold;
        }

        .time-slot.bg-info {
            background-color: #17a2b8 !important;
            /* Màu xanh */
            cursor: pointer;
        }

        .time-slot.disabled {
            pointer-events: none;
        }

        .select-slot {
            margin-top: 3px;
            width: 40px;
            height: 30px;
            padding: 0;
            text-align: center;
            font-size: 14px;
        }

        .btn-success {
            background-color: #28a745 !important;
            color: #fff !important;
        }

        .btn-outline-success {
            border-color: #28a745 !important;
            color: #28a745 !important;
        }
    </style>
@endsection
