@extends('admin.datban.layout')

@section('title')
    Danh mục Bàn Ăn
@endsection

@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Đặt bàn</h4>

                        <!-- Modal Form Đặt Bàn -->
                        <div class="row p-5">
                            <form action="{{ route('dat-ban.store') }}" method="POST">
                                @csrf
                                <!-- Tìm kiếm khách hàng -->
                                <div class="mb-3">
                                    <label class="form-label">Tìm khách hàng:</label>
                                    <input type="text" class="form-control" id="searchCustomer"
                                        placeholder="Nhập tên hoặc số điện thoại">
                                    <ul id="customerList" class="list-group mt-2" style="display: none;"></ul>
                                </div>

                                <div class="row">
                                    <!-- Họ và Tên -->
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Họ và Tên:</label>
                                        <input type="text" class="form-control" id="customerName" name="customer_name">
                                        @error('customer_name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Số điện thoại -->
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Số điện thoại:</label>
                                        <input type="text" class="form-control" id="customerPhone" name="customer_phone">
                                        @error('customer_phone')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Email -->
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Email:</label>
                                        <input type="email" class="form-control" id="customerEmail" name="customer_email">
                                        @error('customer_email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Số người -->
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Số người:</label>
                                        <input type="number" class="form-control" name="num_people" id="numPeople" required
                                            min="1">
                                        @error('num_people')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Thời gian đến -->
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Thời gian đến:</label>
                                        <input type="datetime-local" class="form-control" name="thoi_gian_den"
                                            id="thoi_gian_den"
                                            value="{{ old('thoi_gian_den', \Carbon\Carbon::parse($thoiGianDen)->format('Y-m-d\TH:i')) }}">
                                        @error('thoi_gian_den')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>


                                    <div class="col-md-6 mb-2">
                                        <label for="gio_du_kien">Giờ dự kiến:</label>
                                        <div class="d-flex">
                                            <select id="gio_du_kien_gio" name="gio_du_kien_gio" class="form-control me-2">
                                                @for ($i = 0; $i < 14; $i++)
                                                    <option value="{{ sprintf('%02d', $i) }}">{{ sprintf('%02d', $i) }}
                                                    </option>
                                                @endfor
                                            </select>

                                            <select id="gio_du_kien_phut" name="gio_du_kien_phut" class="form-control">
                                                @foreach (['00', '15', '30', '45'] as $phut)
                                                    <option value="{{ $phut }}">{{ $phut }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        @error('gio_du_kien')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>


                                    <script>
                                        document.addEventListener("DOMContentLoaded", function() {
                                            let inputTime = document.getElementById("gio_du_kien");

                                            inputTime.addEventListener("input", function() {
                                                // Giữ nguyên định dạng HH:MM, tránh bị trình duyệt tự động thêm ký tự lạ
                                                let timeValue = inputTime.value;
                                                let formattedTime = timeValue.substring(0, 5); // Lấy đúng 5 ký tự đầu (HH:MM)
                                                inputTime.value = formattedTime;
                                            });
                                        });
                                    </script>
                                    <!-- Mô tả -->
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Mô tả:</label>
                                        <textarea class="form-control" id="mo_ta" name="mo_ta" rows="2"></textarea>
                                    </div>
                                </div>


                                <!-- Select Bàn ăn -->
                                <div class="mb-3">
                                    <label class="form-label">Bàn ăn:</label>
                                    <div id="banAnButtons" class="button-group">
                                        <!-- Các button bàn ăn sẽ hiển thị ở đây -->
                                    </div>
                                    @error('ban_an_ids')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <div id="selectedBanInputs">

                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary" id="submitBtn">Đặt bàn</button>
                            </form>
                        </div>



                        <script>
                            $(document).ready(async function() {
                                var thoiGianDen = "{{ $thoiGianDen ?? '' }}";
                                var idBan = "{{ $idBan ?? '' }}";

                                // Mặc định chọn 1 giờ
                                $('#gio_du_kien_gio').val('01');
                                $('#gio_du_kien_phut').val('00');

                                // Load bàn khi thay đổi thời gian hoặc giờ dự kiến
                                async function loadBanAn(thoiGian, gioDuKienGio, gioDuKienPhut) {
                                    var selectedTime = moment(thoiGian, "YYYY-MM-DDTHH:mm");
                                    var currentTime = moment();

                                    if (selectedTime.isBefore(currentTime)) {
                                        $('#banAnButtons').html('');
                                        alert(
                                            "⚠️ Thời gian được chọn không hợp lệ! Vui lòng chọn thời gian sau thời gian hiện tại.");
                                        return;
                                    }

                                    try {
                                        const response = await $.ajax({
                                            url: '{{ route('admin.datban.filter') }}',
                                            method: 'GET',
                                            data: {
                                                thoi_gian_den: selectedTime.format("YYYY-MM-DD HH:mm:ss"),
                                                gio_du_kien_gio: gioDuKienGio,
                                                gio_du_kien_phut: gioDuKienPhut
                                            }
                                        });

                                        $('#banAnButtons').html('');

                                        var groupedBanAns = {};
                                        $.each(response, function(index, banAn) {
                                            if (!groupedBanAns[banAn.ten_phong_an]) {
                                                groupedBanAns[banAn.ten_phong_an] = [];
                                            }
                                            groupedBanAns[banAn.ten_phong_an].push(banAn);
                                        });

                                        $.each(groupedBanAns, function(tenPhongAn, banAns) {
                                            var roomContainer = $('<div class="room-container mb-3"></div>');
                                            var roomTitle = $('<h5 class="text-primary">' + tenPhongAn + '</h5>');
                                            roomContainer.append(roomTitle);
                                            var rowContainer = $('<div class="row g-2"></div>');

                                            $.each(banAns, function(index, banAn) {
                                                var button = $(
                                                    `<button type="button" class="btn ${banAn.da_duoc_dat == 1 ? 'btn-danger' : 'btn-primary'} w-100 p-2" 
                         data-id="${banAn.id}" data-so-ghe="${banAn.so_ghe}" ${banAn.da_duoc_dat == 1 ? 'disabled' : ''}>
                         ${banAn.ten_ban} (${banAn.so_ghe})
                         </button>`
                                                );
                                                rowContainer.append($('<div class="col-3"></div>').append(
                                                    button));
                                            });

                                            roomContainer.append(rowContainer);
                                            $('#banAnButtons').append(roomContainer);
                                        });

                                    } catch (error) {
                                        console.error('Lỗi khi xử lý AJAX: ', error);
                                        alert('Có lỗi xảy ra, vui lòng thử lại sau.');
                                    }
                                }

                                // Khi trang load lần đầu
                                if (thoiGianDen) {
                                    loadBanAn(thoiGianDen, $('#gio_du_kien_gio').val(), $('#gio_du_kien_phut').val());
                                }

                                // Khi chọn thời gian hoặc giờ dự kiến
                                $('#thoi_gian_den, #gio_du_kien_gio, #gio_du_kien_phut').on('change', function() {
                                    loadBanAn(
                                        $('#thoi_gian_den').val(),
                                        $('#gio_du_kien_gio').val(),
                                        $('#gio_du_kien_phut').val()
                                    );
                                });

                                // Khi chọn thời gian mới, load lại danh sách bàn
                                $('#thoi_gian_den, #gio_du_kien').on('change', function() {
                                    var newTime = $('#thoi_gian_den').val();
                                    var newGioDuKien = $('#gio_du_kien').val();
                                    loadBanAn(newTime, newGioDuKien);
                                });

                                // Khi click vào bàn, chọn bàn mới và bỏ chọn bàn cũ
                                $(document).on('click', '#banAnButtons button', function() {
                                    if ($(this).hasClass('btn-danger')) return;

                                    var idBan = $(this).data('id');

                                    if ($(this).hasClass('selected')) {
                                        $(this).removeClass('selected');
                                        updateSelectedTables();
                                    } else {
                                        $(this).addClass('selected');
                                        updateSelectedTables();
                                    }
                                });

                                let selectedIds = [];

                                function updateHiddenInputs() {
                                    let selectedBanInputs = document.getElementById("selectedBanInputs");

                                    if (!selectedBanInputs) {
                                        console.error("Không tìm thấy #selectedBanInputs trong DOM!");
                                        return;
                                    }

                                    selectedBanInputs.innerHTML = "";

                                    selectedIds.forEach(id => {
                                        let input = document.createElement("input");
                                        input.type = "hidden";
                                        input.name = "selectedIds[]";
                                        input.value = id;
                                        selectedBanInputs.appendChild(input);
                                    });
                                }

                                function updateSelectedTables() {
                                    var selectedTables = $('#banAnButtons button.selected');
                                    selectedIds = [];
                                    var selectedInfo = [];

                                    selectedTables.each(function() {
                                        selectedIds.push($(this).data('id'));
                                        selectedInfo.push({
                                            tenBan: $(this).text(),
                                            soGhe: $(this).data('so-ghe'),
                                            gioDuKien: $(this).attr('title') // Lấy từ tooltip
                                        });
                                    });

                                    updateHiddenInputs(); // Cập nhật input ẩn

                                    if (selectedTables.length > 0) {
                                        $('#selectedTableInfo').show();
                                        $('#selectedTableId').val(selectedIds.join(','));

                                        var html = selectedInfo.map(table =>
                                            `<p>${table.tenBan} (${table.soGhe} ghế) - Giờ dự kiến: ${table.gioDuKien}</p>`
                                        ).join('');
                                        $('#selectedTableName').html(html);
                                    } else {
                                        $('#selectedTableInfo').hide();
                                        $('#selectedTableId').val('');
                                        $('#selectedTableName').html('');
                                    }
                                }

                            });
                        </script>


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


                    </div>
                </div>

            </div>
        </div>

        <style>
            .bg-warning {
                background-color: #ffcc00;
                /* Màu vàng cho dang_xu_ly */
            }

            .bg-success {
                background-color: #28a745;
                /* Màu xanh lá cho xa_nhan */
            }

            .bg-danger {
                background-color: #dc3545;
                /* Màu đỏ cho da_huy */
            }

            .bg-secondary {
                background-color: #6c757d;
                /* Màu xám cho trạng thái mặc định */
            }

            .button-group {
                display: flex;
                flex-wrap: wrap;
            }

            .button-group button {
                margin: 5px;
            }

            .room-container {
                margin-bottom: 15px;
            }

            .selected {
                background-color: #28a745 !important;
            }

            #additionalSeats {
                display: none;
            }

            #error-message {
                display: none;
            }
        </style>
    @endsection
