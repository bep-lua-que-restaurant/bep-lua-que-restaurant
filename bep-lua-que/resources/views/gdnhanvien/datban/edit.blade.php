@extends('gdnhanvien.datban.layout')

@section('content')
    @php
        use Carbon\Carbon;

    @endphp
    <div class="container">
        <h1 class="text-center my-4">Quản lý Đặt Bàn</h1>
        {{-- {{ dd($maDatBan) }} --}}

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
                                @php
                                    // Lấy ngày đặt bàn đầu tiên từ danh sách DatBansOther nếu có
                                    $date = $datBanCurrent->first()
                                        ? \Carbon\Carbon::parse($datBanCurrent->first()->thoi_gian_den)->format('Y-m-d')
                                        : $date;
                                @endphp
                                <tr class="{{ $banAn->trang_thai == 'co_khach' ? 'bg-info' : '' }}"
                                    data-ban-id="{{ $banAn->id }}">
                                    <td class="fw-bold sticky-col">{{ $banAn->ten_ban }}</td>

                                    @for ($i = 8; $i <= 22; $i++)
                                        @foreach ([0, 30] as $minute)
                                            @php
                                                $timeSlot = sprintf('%02d:%02d', $i, $minute);
                                                $class = 'bg-light';
                                                $maDatBan = '';
                                                foreach ($datBansOther as $datBan) {
                                                    if ($datBan->ban_an_id == $banAn->id) {
                                                        $start = \Carbon\Carbon::parse($datBan->thoi_gian_den)->format(
                                                            'H:i',
                                                        );
                                                        $end = \Carbon\Carbon::parse($datBan->gio_du_kien)->format(
                                                            'H:i',
                                                        );

                                                        if ($timeSlot >= $start && $timeSlot <= $end) {
                                                            // Kiểm tra trạng thái đặt bàn
                                                            if ($datBan->trang_thai === 'dang_xu_ly') {
                                                                $class = 'btn-danger';
                                                            } elseif ($datBan->trang_thai === 'xac_nhan') {
                                                                $class = 'btn-success';
                                                            }
                                                            break; // Thoát vòng lặp sau khi tìm thấy đặt bàn phù hợp
                                                        }
                                                    }
                                                }

                                                foreach ($datBanCurrent as $datBan) {
                                                    if ($datBan->ban_an_id == $banAn->id) {
                                                        $start = \Carbon\Carbon::parse($datBan->thoi_gian_den)->format(
                                                            'H:i',
                                                        );
                                                        $end = \Carbon\Carbon::parse($datBan->gio_du_kien)->format(
                                                            'H:i',
                                                        );

                                                        if ($timeSlot >= $start && $timeSlot <= $end) {
                                                            $class = 'btn-warning';
                                                            $maDatBan = $datBan->ma_dat_ban;
                                                            break;
                                                        }
                                                    }
                                                }
                                            @endphp

                                            <td class="text-center {{ $banAn->trang_thai == 'co_khach' ? 'bg-info' : '' }}"
                                                data-ban-id="{{ $banAn->id }}">
                                                <button class="btn btn-sm text-dark {{ $class }} selectable-slot"
                                                    data-ma-dat-ban="{{ $maDatBan }}" data-ban-id="{{ $banAn->id }}"
                                                    data-ten-ban="{{ $banAn->ten_ban }}"
                                                    data-time-slot="{{ $timeSlot }}" data-date="{{ $date }}">
                                                    +
                                                </button>
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

        <!-- Modal hiển thị danh sách đã chọn -->
        <div id="bookingModal" class="modal fade" tabindex="-1">

            <div class="modal-dialog modal-lg">
                <div class="modal-content p-3">
                    <form method="POST" action="{{ route('dat-ban.update', ['maDatBan' => $datBan->ma_dat_ban]) }}">


                        @csrf
                        @method('PUT')
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">





                        <!-- Header -->
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title fw-bold">Xác nhận đặt bàn</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <!-- Thông tin khách hàng -->
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="fw-bold">Họ tên:</label>
                                    <input type="text" class="form-control" value="{{ $datBan->khachHang->ho_ten }}"
                                        readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="fw-bold">Email:</label>
                                    <input type="email" class="form-control" value="{{ $datBan->khachHang->email }}"
                                        readonly>
                                </div>
                            </div>

                            <div class="row g-3 mt-2">
                                <div class="col-md-6">
                                    <label class="fw-bold">Số điện thoại:</label>
                                    <input type="text" class="form-control"
                                        value="{{ $datBan->khachHang->so_dien_thoai }}" name="so_dien_thoai" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="fw-bold">Số người:</label>
                                    <input type="number" class="form-control" name="so_nguoi" min="1"
                                        value="{{ $datBan->so_nguoi }}">
                                </div>
                            </div>

                            <!-- Mô tả -->
                            <div class="mt-3">
                                <label class="fw-bold">Mô tả:</label>
                                <textarea class="form-control" name="mo_ta" rows="3">{{ $datBan->mo_ta }}</textarea>
                            </div>

                            <!-- Danh sách bàn đã chọn -->
                            <div class="mt-3">
                                <h6 class="fw-bold">Danh sách bàn đã chọn</h6>
                                <div class="table-responsive"> <!-- Thêm div này để bảng có thể co giãn tốt hơn -->
                                    <table class="table table-bordered table-striped w-100">

                                        <tbody id="selectedSlots"></tbody>
                                    </table>
                                </div>
                            </div>

                        </div>

                        <!-- Input ẩn để gửi dữ liệu -->
                        <input type="hidden" name="ban_an_ids" id="banAnIds">
                        <input type="hidden" name="thoi_gian_den" id="thoiGianDen">
                        <input type="hidden" name="gio_du_kien" id="gioDuKien">
                        <input type="hidden" name="khach_hang_id" value="{{ $datBan->khach_hang_id }}">
                        <input type="hidden" name="ma_dat_ban" id="maDatBan" value="{{ $datBan->ma_dat_ban }}">

                        <!-- Footer -->
                        <div class="modal-footer d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-success">Xác nhận</button>
                        </div>


                    </form>
                </div>
            </div>
        </div>

        @vite('resources/js/datban.js')

        <!-- Nút mở modal -->
        <button id="openModalBtn" class="btn btn-primary position-fixed end-0 m-3" style="bottom: 80px; display: none;">
            Xem đặt bàn
        </button>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const selectedSlots = new Map(); // Lưu danh sách đã chọn
                const openModalBtn = document.getElementById("openModalBtn");
                const selectedSlotsList = document.getElementById("selectedSlots");

                const banAnIdsInput = document.getElementById("banAnIds");
                const thoiGianDenInput = document.getElementById("thoiGianDen");
                const gioDuKienInput = document.getElementById("gioDuKien");
                const ngayDenInput = document.getElementById("ngay_den");

                document.querySelectorAll(".selectable-slot").forEach((button) => {
                    const banId = button.getAttribute("data-ban-id");
                    const tenBan = button.getAttribute("data-ten-ban");
                    const timeSlot = button.getAttribute("data-time-slot");
                    const maDatBan = button.getAttribute("data-ma-dat-ban");
                    const date = button.getAttribute("data-date");

                    const slotKey = `${banId}-${timeSlot}-${date}`;

                    // Nếu button có class btn-warning => Thêm vào danh sách đã chọn ngay từ đầu
                    if (button.classList.contains("btn-warning")) {
                        selectedSlots.set(slotKey, {
                            banId,
                            tenBan,
                            timeSlot,
                            maDatBan,
                            date
                        });
                    }

                    // Xử lý chọn / hủy chọn khi click
                    button.addEventListener("click", function() {
                        if (button.classList.contains("btn-danger") || button.classList.contains(
                                "btn-success"))
                            return; // Không chọn được btn-danger

                        if (!button.classList.contains("btn-warning")) {
                            // Kiểm tra thời gian trong quá khứ nếu button chưa được chọn
                            const now = new Date();
                            const currentHour = now.getHours();
                            const currentMinute = now.getMinutes();
                            const formattedCurrentTime =
                                `${String(currentHour).padStart(2, "0")}:${String(currentMinute).padStart(2, "0")}`;
                            const todayDate = now.toISOString().split("T")[0];

                            if (date === todayDate && timeSlot < formattedCurrentTime) {
                                alert("Không thể chọn thời gian trong quá khứ!");
                                return;
                            }

                            // Kiểm tra hơn kém nhau 30 phút trong cùng bàn
                            const selectedTimes = [...selectedSlots.values()]
                                .filter(slot => slot.banId === banId)
                                .map(slot => slot.timeSlot)
                                .sort();

                            if (selectedTimes.length > 0) {
                                const [hour, minute] = timeSlot.split(":").map(Number);
                                const newSlotMinutes = hour * 60 + minute;
                                let isValid = false;

                                for (const selectedTime of selectedTimes) {
                                    const [selectedHour, selectedMinute] = selectedTime.split(":").map(
                                        Number);
                                    const selectedSlotMinutes = selectedHour * 60 + selectedMinute;

                                    if (Math.abs(newSlotMinutes - selectedSlotMinutes) === 30) {
                                        isValid = true;
                                        break;
                                    }
                                }

                                if (!isValid) {
                                    alert(
                                        "Chỉ có thể chọn giờ hơn kém nhau 30 phút trong cùng một bàn!"
                                    );
                                    return;
                                }
                            }
                        }

                        if (button.classList.contains("btn-warning")) {
                            // Hủy chọn
                            button.classList.remove("btn-warning");
                            button.classList.add("bg-light");
                            selectedSlots.delete(slotKey);
                        } else {
                            // Chọn
                            button.classList.remove("bg-light");
                            button.classList.add("btn-warning");
                            selectedSlots.set(slotKey, {
                                banId,
                                tenBan,
                                timeSlot,
                                maDatBan,
                                date
                            });
                        }

                        updateModal();
                    });
                });

                // Cập nhật modal ngay khi vào trang
                updateModal();


                function updateModal() {
                    const selectedSlotsList = document.getElementById("selectedSlots");
                    selectedSlotsList.innerHTML = "";

                    if (selectedSlots.size === 0) {
                        openModalBtn.style.display = "none";
                        return;
                    }

                    openModalBtn.style.display = "block";

                    const groupedSlots = {};
                    let allBanAnIds = [];
                    let allNgayDen = new Set();
                    let earliestTime = null;
                    let latestTime = null;

                    selectedSlots.forEach((slot) => {
                        if (!groupedSlots[slot.banId]) {
                            groupedSlots[slot.banId] = {
                                tenBan: slot.tenBan,
                            };
                        }
                        groupedSlots[slot.banId].date = slot.date;
                        groupedSlots[slot.banId].times = groupedSlots[slot.banId].times || [];
                        groupedSlots[slot.banId].times.push(slot.timeSlot);
                        allNgayDen.add(slot.date);
                    });

                    Object.entries(groupedSlots).forEach(([banId, group]) => {
                        // 🟢 Sắp xếp thời gian theo thứ tự tăng dần
                        group.times.sort();

                        const startTime = group.times[0]; // Giờ bắt đầu nhỏ nhất
                        const endTime = group.times[group.times.length - 1]; // Giờ kết thúc lớn nhất

                        // 🟢 Cập nhật giờ bắt đầu sớm nhất và muộn nhất
                        if (!earliestTime || startTime < earliestTime) earliestTime = startTime;
                        if (!latestTime || endTime > latestTime) latestTime = endTime;

                        // 🟢 Lưu ID bàn ăn vào danh sách
                        allBanAnIds.push(banId);
                    });

                    // 🟢 Nếu có nhiều ngày → Không hợp lệ
                    const uniqueDates = [...allNgayDen];
                    if (uniqueDates.length > 1) {
                        alert("Không thể chọn các khung giờ từ nhiều ngày khác nhau.");
                        return;
                    }
                    const selectedDate = uniqueDates[0];

                    // 🟢 Format giờ kết thúc + 25 phút
                    const [hour, minute] = latestTime.split(':').map(Number);
                    let dateObj = new Date();
                    dateObj.setHours(hour);
                    dateObj.setMinutes(minute + 25);
                    const formattedEndTime =
                        `${String(dateObj.getHours()).padStart(2, '0')}:${String(dateObj.getMinutes()).padStart(2, '0')}:00`;

                    // 🟢 Thêm dòng ngày & thời gian
                    // 🟢 Tạo div chính để chứa ngày & thời gian và danh sách bàn ăn
                    // 🟢 Tạo div chính chứa tất cả (Dùng `d-flex` + `flex-wrap` để tối ưu hiển thị)
                    const mainRow = document.createElement("div");
                    mainRow.classList.add("d-flex", "flex-wrap", "py-2", "gap-2");

                    // 🟢 Div chứa ngày & thời gian (Luôn nằm trên cùng)
                    const timeInfo = document.createElement("div");
                    timeInfo.classList.add("fw-bold", "w-100"); // Đảm bảo full width
                    timeInfo.innerHTML = `Ngày: ${selectedDate} | Giờ: ${earliestTime} → ${formattedEndTime}`;

                    // 🟢 Div chứa danh sách bàn ăn (Sẽ wrap xuống nếu quá dài)
                    const banList = document.createElement("div");
                    banList.classList.add("d-flex", "flex-wrap", "gap-2");

                    // 🟢 Dùng Set để tránh trùng bàn ăn
                    const displayedBanIds = new Set();

                    Object.entries(groupedSlots).forEach(([banId, group]) => {
                        if (!displayedBanIds.has(banId)) {
                            displayedBanIds.add(banId);

                            // 🟢 Badge hiển thị tên bàn
                            const banItem = document.createElement("span");
                            banItem.classList.add("badge", "bg-primary", "text-white", "px-3", "py-2");
                            banItem.innerText = group.tenBan;

                            banList.appendChild(banItem);

                            // 🟢 Tạo input ẩn cho từng bàn
                            const hiddenInput = document.createElement("input");
                            hiddenInput.type = "hidden";
                            hiddenInput.name = "selectedIds[]";
                            hiddenInput.value = banId;
                            selectedSlotsList.appendChild(hiddenInput);
                        }
                    });

                    // 🟢 Gộp tất cả vào div chính
                    mainRow.appendChild(timeInfo);
                    mainRow.appendChild(banList);

                    // 🟢 Thêm vào danh sách hiển thị
                    selectedSlotsList.appendChild(mainRow);

                    // 🟢 Gán giá trị cho input ẩn
                    document.getElementById("banAnIds").value = allBanAnIds.join(",");
                    document.getElementById("thoiGianDen").value = `${selectedDate} ${earliestTime}:00`;
                    document.getElementById("gioDuKien").value = formattedEndTime;
                }


                // Mở modal khi click nút xem danh sách
                openModalBtn.addEventListener("click", function() {
                    const bookingModal = new bootstrap.Modal(document.getElementById("bookingModal"));
                    bookingModal.show();
                });

            });
        </script>



        <script>
            document.getElementById('datBanForm').addEventListener('submit', function(event) {
                event.preventDefault();

                let formData = new FormData(this);
                fetch(this.action, {
                        method: 'PUT',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => alert('Cập nhật thành công!'))
                    .catch(error => console.error('Lỗi:', error));

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


            .btn-danger {
                pointer-events: none;
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
