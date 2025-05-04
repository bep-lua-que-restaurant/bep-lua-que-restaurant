@extends('gdnhanvien.datban.layout')

@section('content')
    <div class="container-fluid">
        <!-- Chọn ngày -->
        <div class="row d-flex align-items-center mb-3">
            <div class="col-auto">
                <label for="datePicker" class="fw-bold">Hiển thị theo ngày:</label>
            </div>
            <div class="col-auto">
                <input type="date" class="form-control shadow-sm" id="datePicker"
                    value="{{ \Carbon\Carbon::today()->toDateString() }}">
            </div>
        </div>

        <!-- Bảng đặt bàn -->
        <div id="ngay-content" class="table-responsive">
            <table class="table table-bordered table-hover text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th class="sticky-col text-nowrap">Bàn / Giờ</th>
                        @for ($i = 7; $i <= 21; $i++)
                            <th>{{ sprintf('%02d', $i) }}:00</th>
                            <th>{{ sprintf('%02d', $i) }}:30</th>
                        @endfor
                    </tr>
                </thead>

                <tbody id="ngay-tabs">
                    <tr>
                        <td colspan="16" class="text-center">Đang tải dữ liệu...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>


    <div id="pagination-controls" class="mt-3"></div> <!-- Hiển thị các nút phân trang -->

    <!-- Nút mở modal -->
    <button id="openModalButton" class="btn btn-primary d-none">Thông tin đặt bàn</button>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl"> <!-- Đổi modal-lg thành modal-xl -->
            <div class="modal-content">

                <form id="bookingForm" method="POST" action="{{ route('dat-ban.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Thông tin đặt bàn </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <!-- Ô tìm kiếm -->
                        <div class="mb-3">
                            {{-- <input type="text" class="form-control" id="searchCustomer" placeholder="Tìm kiếm..."> --}}

                            <input type="text" class="form-control" id="searchCustomer"
                                placeholder="Tìm kiếm theo họ tên hoặc số điện thoại">
                            <ul id="customerList" class="list-group mt-2" style="display: none;"></ul>
                        </div>

                        <!-- Form nhập thông tin -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="fullName" class="fw-bold"> <span class="text-danger fs-5">*</span> Họ
                                    tên:</label>
                                <input type="text" class="form-control" id="customerName" name="customer_name">
                                {{-- @error('customer_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror --}}
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="fw-bold"> <span class="text-danger fs-5">*</span>Email:</label>
                                <input type="email" class="form-control" id="customerEmail" name="customer_email">
                                {{-- @error('customer_email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror --}}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="phone" class="fw-bold"><span class="text-danger fs-5">*</span>Số điện
                                    thoại:</label>
                                <input type="tel" class="form-control" id="customerPhone" name="customer_phone">
                                {{-- @error('customer_phone')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror --}}
                            </div>
                            <div class="col-md-6">
                                <label for="numberOfGuests" class="fw-bold"><span class="text-danger fs-5">*</span>Số
                                    người:</label>
                                <input type="number" class="form-control" name="num_people" id="numPeople" min="1">
                                {{-- @error('num_people')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror --}}
                            </div>
                        </div>
                        <div class="mt-3">
                            <label class="fw-bold">Mô tả:</label>
                            <textarea class="form-control" name="mo_ta" id="moTa" rows="3"></textarea>
                        </div>

                        <!-- Danh sách các bàn đã chọn -->
                        <ul id="modalContent" class="list-unstyled"></ul>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        {{-- <button id="clearButton" class="btn btn-danger"></button> --}}
                        <button type="submit" id="confirmButton" class="btn btn-primary">Xác nhận đặt bàn</button>

                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            function loadDatBan(date) {
                $("#ngay-tabs").html('<tr><td colspan="30" class="text-center">Đang tải dữ liệu...</td></tr>');

                $.ajax({
                    url: `/api/datban`, // Không còn `?page`
                    type: 'GET',
                    data: {
                        date: date
                    },
                    success: function(response) {
                        let html = '';

                        response.banPhong.forEach(ban => { // Không còn `.data`
                            const tableClass = ban.trang_thai === "co_khach" ? "bg-info" : "";
                            html +=
                                `<tr class="${tableClass}"><td class="fw-bold sticky-col">${ban.ten_ban}</td>`;

                            for (let i = 7; i <= 21; i++) {
                                ["00", "30"].forEach(minute => {
                                    const timeSlot =
                                        `${i.toString().padStart(2, '0')}:${minute}`;
                                    const thoiGianHienTai = new Date(
                                        `${date}T${timeSlot}:00`);

                                    const datBan = response.datBans.find(d => {
                                        if (d.ban_an_id !== ban.id)
                                            return false;
                                        const thoiGianDen = new Date(d
                                            .thoi_gian_den);
                                        const [hours, minutes] = d.gio_du_kien
                                            .split(':').map(Number);
                                        const thoiGianKetThuc = new Date(
                                            thoiGianDen);
                                        thoiGianKetThuc.setHours(hours);
                                        thoiGianKetThuc.setMinutes(minutes);
                                        return thoiGianHienTai >= thoiGianDen &&
                                            thoiGianHienTai <= thoiGianKetThuc;
                                    });

                                    const statusClass = datBan ? (datBan.trang_thai ===
                                            'xac_nhan' ? 'btn-success' : 'btn-danger') :
                                        'bg-light';
                                    const maDatBan = datBan ? datBan.ma_dat_ban : '';
                                    const content = `
                                        <button class="btn btn-sm ${statusClass} text-dark btn-view-details selectable-slot" 
                                            data-ma-dat-ban="${maDatBan}" 
                                            data-ban-id="${ban.id}" 
                                            data-ten-ban="${ban.ten_ban}" 
                                            data-time-slot="${timeSlot}" 
                                            data-date="${date}"
                                            data-bs-toggle="tooltip" 
                                            data-bs-title="Đang tải...">
                                            +
                                        </button>`;

                                    html +=
                                        `<td class="text-center ${tableClass}" data-ban-id="${ban.id}">${content}</td>`;
                                });
                            }
                            html += `</tr>`;
                        });

                        $("#ngay-tabs").html(html);
                        $('[data-bs-toggle="tooltip"]').tooltip(); // Kích hoạt tooltip
                        attachTooltipEvents();
                    },
                    error: function() {
                        $("#ngay-tabs").html(
                            '<tr><td colspan="30" class="text-center text-danger">Lỗi tải dữ liệu</td></tr>'
                        );
                    }
                });
            }

            function attachTooltipEvents() {
                $(".selectable-slot").on("mouseenter", function() {
                    const button = $(this);
                    const maDatBan = button.data("ma-dat-ban");

                    if (maDatBan) {
                        fetch(`/api/datban/${maDatBan}`)
                            .then(response => response.json())
                            .then(data => {
                                if (!data.ho_ten) return;
                                const title = `
                                    <strong>Khách:</strong> ${data.ho_ten} <br>
                                    <strong>SĐT:</strong> ${data.so_dien_thoai} <br>
                                    <strong>Số người:</strong> ${data.so_nguoi} <br>
                                    <strong>Mô tả:</strong> ${data.mo_ta || "Không có mô tả"} <br>
                                    <strong>Bàn:</strong> ${data.ban_ans ? data.ban_ans.join(", ") : "Không có bàn"}`;
                                button.attr("data-bs-title", title);
                                const oldTooltip = bootstrap.Tooltip.getInstance(button[0]);
                                if (oldTooltip) oldTooltip.dispose();
                                const tooltip = new bootstrap.Tooltip(button[0], {
                                    html: true
                                });
                                tooltip.show();
                                setTimeout(() => {
                                    tooltip.dispose();
                                }, 1500);
                            })
                            .catch(error => console.error("Lỗi khi lấy dữ liệu đặt bàn:", error));
                    }
                });

                $(".selectable-slot").on("mouseleave", function() {
                    const tooltip = bootstrap.Tooltip.getInstance(this);
                    if (tooltip) tooltip.dispose();
                });
            }

            $(document).ready(function() {
                loadDatBan($("#datePicker").val());
                $("#datePicker").on("change", function() {
                    loadDatBan($(this).val());
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            let selectedSlots = {};

            // Xử lý sự kiện click để chọn hoặc bỏ chọn slot
            $(document).on("click", ".selectable-slot", function() {
                if ($(this).hasClass("btn-success") || $(this).hasClass("btn-danger")) {
                    return;
                }

                let banId = $(this).data("ban-id");
                let tenBan = $(this).data("ten-ban");
                let timeSlot = $(this).data("time-slot");
                let date = $("#datePicker").val();

                if (!date) {
                    alert("Vui lòng chọn ngày!");
                    return;
                }

                let [hour, minute] = timeSlot.split(":").map(Number);
                let thoiGianDen = new Date(date);
                thoiGianDen.setHours(hour);
                thoiGianDen.setMinutes(minute);

                let now = new Date();
                if (thoiGianDen < now) {
                    alert("Không thể chọn thời gian trong quá khứ!");
                    return;
                }

                if (!isValidNewSlot(timeSlot, banId)) {
                    alert(
                        "Vui lòng chọn tối đa 3 tiếng (6 slot) liên tiếp, mỗi slot cách nhau đúng 30 phút!"
                    );
                    return;
                }

                if (!selectedSlots[banId]) {
                    selectedSlots[banId] = [];
                }

                let existingIndex = selectedSlots[banId].findIndex(slot =>
                    slot.timeSlot === timeSlot &&
                    slot.ngayThangNam.toDateString() === thoiGianDen.toDateString()
                );

                if (existingIndex !== -1) {
                    $(this).removeClass("selected");
                    selectedSlots[banId].splice(existingIndex, 1);
                    if (selectedSlots[banId].length === 0) {
                        delete selectedSlots[banId]; // Xóa bàn khỏi danh sách nếu không còn slot nào
                    }
                } else {
                    $(this).addClass("selected");
                    selectedSlots[banId].push({
                        banId,
                        tenBan,
                        timeSlot,
                        thoiGianDen,
                        thoiGianKetThuc: new Date(thoiGianDen.getTime() + 30 * 60 * 1000),
                        ngayThangNam: thoiGianDen
                    });
                }

                updateModalButton();
            });

            function isValidNewSlot(newTimeSlot, banId) {
                let [newHour, newMinute] = newTimeSlot.split(":").map(Number);
                let newTime = newHour * 60 + newMinute;

                // Nếu slot này đã được chọn => cho phép bỏ chọn
                if (selectedSlots[banId]) {
                    let existingIndex = selectedSlots[banId].findIndex(slot => slot.timeSlot === newTimeSlot);
                    if (existingIndex !== -1) {
                        return true; // Cho phép huỷ chọn slot đã chọn
                    }
                }

                // Nếu bàn đã chọn trước đó
                if (selectedSlots[banId] && selectedSlots[banId].length > 0) {
                    if (selectedSlots[banId].length < 6) {
                        let times = selectedSlots[banId].map(slot => {
                            let [h, m] = slot.timeSlot.split(":").map(Number);
                            return h * 60 + m;
                        });

                        times.push(newTime);
                        times.sort((a, b) => a - b);

                        let totalDuration = times[times.length - 1] - times[0] + 30;
                        if (totalDuration > 180) {
                            return false;
                        }

                        for (let i = 1; i < times.length; i++) {
                            if (times[i] - times[i - 1] !== 30) {
                                return false;
                            }
                        }

                        return true;
                    } else {
                        return false;
                    }
                }

                // Nếu là bàn mới, kiểm tra đồng bộ thời gian với các bàn khác
                let selectedBanList = Object.keys(selectedSlots).map(banId => {
                    return {
                        banId: banId,
                        tenBan: selectedSlots[banId][0].tenBan
                    };
                });

                if (selectedBanList.length > 0) {
                    let firstSelectedSlot = selectedSlots[selectedBanList[0].banId][0];
                    let [firstHour, firstMinute] = firstSelectedSlot.timeSlot.split(":").map(Number);
                    let firstTime = firstHour * 60 + firstMinute;

                    if (newTime !== firstTime) {
                        alert("Thời gian của các bàn phải giống nhau!");
                        return false;
                    }
                }

                return true;
            }


            // Hiển thị hoặc ẩn nút mở modal
            function updateModalButton() {
                if (Object.keys(selectedSlots).length > 0) {
                    $("#openModalButton").removeClass("d-none");
                } else {
                    $("#openModalButton").addClass("d-none");
                }
            }

            // Hiển thị modal và thông tin chi tiết
            $("#openModalButton").on("click", function() {
                let groupedSlots = {};
                let earliestGioBatDau = null;
                let latestGioKetThuc = null;
                let selectedBanList = [];

                // Lặp qua tất cả các bàn đã chọn
                Object.keys(selectedSlots).forEach(banId => {
                    if (selectedSlots[banId].length > 0) {
                        selectedSlots[banId].forEach(slot => {
                            if (!earliestGioBatDau || slot.thoiGianDen <
                                earliestGioBatDau) {
                                earliestGioBatDau = slot.thoiGianDen;
                            }
                            if (!latestGioKetThuc || slot.thoiGianKetThuc >
                                latestGioKetThuc) {
                                latestGioKetThuc = slot.thoiGianKetThuc;
                            }
                        });

                        selectedBanList.push({
                            banId: banId,
                            tenBan: selectedSlots[banId][0].tenBan
                        });
                    }
                });

                if (selectedBanList.length === 0) return; // Nếu không có bàn nào thì thoát luôn

                function formatTime(date) {
                    let pad = n => n.toString().padStart(2, "0");
                    return `${pad(date.getHours())}:${pad(date.getMinutes())}`;
                }

                let firstBanId = Object.keys(selectedSlots)[0];
                if (!selectedSlots[firstBanId] || selectedSlots[firstBanId].length === 0) return;
                let ngayThangNam = selectedSlots[firstBanId][0].ngayThangNam;

                let formattedDate = ngayThangNam.toLocaleDateString("vi-VN", {
                    year: "numeric",
                    month: "2-digit",
                    day: "2-digit"
                }).split("/").reverse().join("-");

                let formattedGioBatDau = formatTime(earliestGioBatDau);
                // let formattedGioKetThuc = formatTime(latestGioKetThuc);
                latestGioKetThuc.setMinutes(latestGioKetThuc.getMinutes() - 1);
                let formattedGioKetThuc = formatTime(latestGioKetThuc);


                let timeKey = `${formattedDate} ${formattedGioBatDau} → ${formattedGioKetThuc}`;

                groupedSlots[timeKey] = {
                    date: formattedDate,
                    gioBatDau: formattedGioBatDau,
                    gioKetThuc: formattedGioKetThuc,
                    banList: selectedBanList
                };

                let modalContent = "";

                Object.keys(groupedSlots).forEach(timeKey => {
                    let slot = groupedSlots[timeKey];

                    modalContent += `
                        <li class="d-flex justify-content-between align-items-center">
                            <p><strong>Ngày:</strong> ${slot.date.split("-").reverse().join("/")}</p>
                            <p><strong>Thời gian:</strong> ${slot.gioBatDau} → ${slot.gioKetThuc}</p>
                            <p><strong>Bàn:</strong> ${slot.banList.map(ban => ban.tenBan).join(", ")}</p>
                        </li>
                        <input type="hidden" name="thoi_gian_den" value="${slot.date} ${slot.gioBatDau}:00">
                        <input type="hidden" name="gio_du_kien" value="${slot.gioKetThuc}:00">
                        <input type="hidden" name="ngay" value="${slot.date}">
                    `;

                    slot.banList.forEach(ban => {
                        modalContent += `
                            <input type="hidden" name="selectedIds[]" value="${ban.banId}">
                        `;
                    });

                });

                $("#modalContent").html(modalContent);
                $("#exampleModal").modal("show");
            });

            // Xử lý sự kiện "Xóa tất cả"
            $("#clearButton").on("click", function() {
                selectedSlots = {};
                $(".selectable-slot").removeClass("selected");
                $("#exampleModal").modal("hide");
            });

        });
    </script>

    <script>
        $('#confirmButton').on('click', function(event) {
            event.preventDefault();

            // Ngăn chặn click liên tục bằng cách vô hiệu hóa nút
            let $button = $(this);
            if ($button.prop('disabled')) return; // Nếu đã disabled thì bỏ qua

            $button.prop('disabled', true); // Vô hiệu hóa nút khi bắt đầu xử lý

            // Lấy dữ liệu từ form
            // Hàm lấy giá trị input, kiểm tra null/undefined trước khi trim
            function getInputValue(name) {
                let input = $(`input[name="${name}"]`);
                return input.length ? input.val().trim() : "";
            }

            // Lấy dữ liệu từ form
            let customerName = getInputValue("customer_name");
            let customerPhone = getInputValue("customer_phone");
            let customerEmail = getInputValue("customer_email");
            let numPeople = getInputValue("num_people");
            let thoiGianDen = getInputValue("thoi_gian_den");
            let gioDuKien = getInputValue("gio_du_kien");
            let moTa = $('textarea[name="mo_ta"]').val().trim();
            console.log("Giá trị mô tả:", moTa);

            // Lấy danh sách bàn (mảng)
            let selectedIds = $('input[name="selectedIds[]"]').map(function() {
                return $(this).val();
            }).get();

            // Kiểm tra hợp lệ
            let errors = [];
            if (customerName === '') {
                errors.push("Họ tên không được để trống.");
            }
            if (customerPhone === '' || !/^\d{10}$/.test(customerPhone)) {
                errors.push("Số điện thoại không hợp lệ. (10 chữ số)");
            }
            if (customerEmail === '' || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(customerEmail)) {
                errors.push("Email không hợp lệ.");
            }
            if (numPeople === '' || isNaN(numPeople) || numPeople <= 0) {
                errors.push("Số người phải lớn hơn 0.");
            }
            if (thoiGianDen === '') {
                errors.push("Vui lòng chọn thời gian đến.");
            }
            if (gioDuKien === '') {
                errors.push("Vui lòng chọn giờ dự kiến.");
            }
            if (selectedIds.length === 0) {
                errors.push("Vui lòng chọn ít nhất một bàn.");
            }

            // Nếu có lỗi, hiển thị thông báo và kích hoạt lại nút
            if (errors.length > 0) {
                alert("\n" + errors.join("\n"));
                $button.prop('disabled', false); // Kích hoạt lại nút nếu có lỗi
                return;
            }

            // Dữ liệu hợp lệ, tiếp tục gửi AJAX
            let formData = {
                customer_name: customerName,
                customer_phone: customerPhone,
                customer_email: customerEmail,
                num_people: numPeople,
                thoi_gian_den: thoiGianDen,
                gio_du_kien: gioDuKien,
                selectedIds: selectedIds,
                mo_ta: moTa,
                _token: '{{ csrf_token() }}'
            };

            $.ajax({
                url: '{{ route('dat-ban.store') }}',
                type: 'POST',
                data: formData,
                success: function(response) {
                    console.log('Success:', response);
                    alert('Đặt bàn thành công!');
                    window.location.href = '{{ route('dat-ban.index') }}';
                },
                error: function(xhr) {
                    console.error('Error:', xhr);
                    console.error('Response:', xhr.responseText);

                    let errorMsg = "Có lỗi xảy ra!"; // Thông báo mặc định

                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        let errors = xhr.responseJSON.errors;
                        errorMsg = Object.values(errors).flat().join("\n");
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message; // Lấy message nếu có
                    } else {
                        errorMsg = xhr.responseText || "Lỗi không xác định.";
                    }

                    alert('Lỗi:\n' + errorMsg);
                },

            });
        });
    </script>

    @vite('resources/js/datban.js')


    <script>
        $(document).ready(function() {
            $(document).ready(function() {
                // Khi người dùng nhập tìm kiếm
                $('#searchCustomer').on('keyup', function() {
                    var query = $(this).val(); // Lấy giá trị tìm kiếm từ ô input

                    if (query.length >= 3) { // Chỉ tìm kiếm khi có ít nhất 3 ký tự
                        $.ajax({
                            url: '{{ route('admin.khachhang.search') }}', // Gọi route tìm kiếm khách hàng
                            method: 'GET',
                            data: {
                                search: query
                            },
                            success: function(data) {
                                // Cập nhật danh sách khách hàng tìm được
                                $('#customerList')
                                    .empty(); // Xóa danh sách trước khi cập nhật
                                if (data.customers.length > 0) {
                                    data.customers.forEach(function(customer) {
                                        $('#customerList').append(
                                            '<li class="list-group-item" data-id="' +
                                            customer.id +
                                            '" data-name="' + customer
                                            .ho_ten +
                                            '" data-phone="' + customer
                                            .so_dien_thoai +
                                            '" data-email="' + customer
                                            .email +
                                            '" data-cancuoc="' + customer
                                            .can_cuoc + '">' +
                                            customer.ho_ten + ' (' +
                                            customer.so_dien_thoai +
                                            ')</li>'
                                        );
                                    });
                                    $('#customerList').show();
                                } else {
                                    $('#customerList')
                                        .hide(); // Nếu không tìm thấy khách hàng, ẩn danh sách
                                }
                            }
                        });
                    } else {
                        $('#customerList')
                            .hide(); // Nếu không có gì trong ô tìm kiếm, ẩn danh sách khách hàng
                    }
                });

                // Khi click vào khách hàng từ danh sách, điền thông tin vào form
                $(document).on('click', '#customerList li', function() {
                    var customerName = $(this).data('name');
                    var customerPhone = $(this).data('phone');
                    var customerEmail = $(this).data('email');
                    var customerCanCuoc = $(this).data('cancuoc');

                    $('#customerName').val(customerName);
                    $('#customerPhone').val(customerPhone);
                    $('#customerEmail').val(customerEmail);
                    $('#customerCanCuoc').val(customerCanCuoc);

                    $('#customerList').hide();
                });
            });
        });
    </script>
    <style>
        .table th,
        .table td {
            text-align: center;
            vertical-align: middle;
        }

        .sticky-col {
            position: sticky;
            left: 0;
            background: #fff;
            z-index: 2;
        }

        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }


        .border-left-rounded {
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
        }

        .border-right-rounded {
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        .select-slot {
            cursor: pointer;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            transition: background-color 0.2s ease;
        }

        .select-slot:hover {
            background-color: #e2e6ea;
        }

        .select-slot.selected {
            background-color: #007bff;
            color: #fff;
            border-color: #007bff;
        }

        .selected {
            background-color: #ffc107 !important;
            /* Màu vàng để làm nổi bật */
            color: #000;
            font-weight: bold;
        }

        #openModalButton {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }

        .modal-xl {
            max-width: 50%;
            /* Chiều rộng modal */
        }

        .modal-body {
            max-height: 70vh;
            /* Giới hạn chiều cao để có thể cuộn */
            overflow-y: auto;
        }
    </style>
@endsection
