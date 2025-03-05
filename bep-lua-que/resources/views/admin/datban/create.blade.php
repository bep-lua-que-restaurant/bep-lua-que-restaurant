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

                                <!-- Thông tin khách hàng -->
                                <div class="mb-3">
                                    <label class="form-label">Họ và Tên:</label>
                                    <input type="text" class="form-control" id="customerName" name="customer_name">
                                    @error('customer_name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Số điện thoại:</label>
                                    <input type="text" class="form-control" id="customerPhone" name="customer_phone">
                                    @error('customer_phone')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Email:</label>
                                    <input type="email" class="form-control" id="customerEmail" name="customer_email">
                                    @error('customer_email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>


                                <div class="mb-3">
                                    <label class="form-label">Mô tả:</label>
                                    <input type="textare" class="form-control" id="mo_ta" name="mo_ta">

                                </div>

                                <!-- Thông tin đặt bàn -->
                                <div class="mb-3">
                                    <label class="form-label">Thời gian đến:</label>
                                    <input type="datetime-local" class="form-control" name="thoi_gian_den"
                                        id="thoi_gian_den"
                                        value="{{ old('thoi_gian_den', \Carbon\Carbon::parse($thoiGianDen)->format('Y-m-d\TH:i')) }}">
                                    @error('thoi_gian_den')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- 02/14/2025 11:50 AM --}}
                                <div class="mb-3">
                                    <label class="form-label">Số người:</label>
                                    <input type="number" class="form-control" name="num_people" id="numPeople" required
                                        min="1">
                                    @error('num_people')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
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
                                var thoiGianDen = "{{ $thoiGianDen ?? '' }}"; // Lấy thời gian từ server
                                var idBan = "{{ $idBan ?? '' }}"; // Lấy ID bàn từ server

                                // Hàm tải danh sách bàn theo thời gian
                                async function loadBanAn(thoiGian) {
                                    var selectedTime = moment(thoiGian, "YYYY-MM-DDTHH:mm");
                                    var currentTime = moment();

                                    if (selectedTime.isBefore(currentTime)) {
                                        $('#banAnButtons').html('');
                                        alert(
                                            "⚠️ Thời gian được chọn không hợp lệ! Vui lòng chọn thời gian sau thời gian hiện tại."
                                        );
                                        return;
                                    }

                                    $('#selectedTime').text(thoiGian);
                                    $('#thoi_gian_den').val(thoiGian);
                                    $('#selectedTimeInput').val(thoiGian);

                                    try {
                                        const response = await $.ajax({
                                            url: '{{ route('admin.datban.filter') }}',
                                            method: 'GET',
                                            data: {
                                                thoi_gian_den: selectedTime.format("YYYY-MM-DD HH:mm:ss")
                                            }
                                        });

                                        $('#banAnButtons').html(''); // Xóa bàn cũ để cập nhật bàn mới

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

                                            banAns.sort(function(a, b) {
                                                return a.so_ghe - b.so_ghe;
                                            });

                                            $.each(banAns, function(index, banAn) {
                                                var colClass = "col-2";
                                                if (banAn.so_ghe == 8) colClass = "col-3";
                                                else if (banAn.so_ghe == 10) colClass = "col-6";

                                                var button = $(
                                                    '<button type="button" class="btn btn-primary w-100 p-2" data-id="' +
                                                    banAn.id + '" data-so-ghe="' + banAn.so_ghe + '">' +
                                                    banAn.ten_ban + ' (' + banAn.so_ghe + ')' + '</button>'
                                                );

                                                if (banAn.da_duoc_dat == 1) {
                                                    button.removeClass('btn-primary').addClass('btn-danger')
                                                        .prop('disabled', true);
                                                }

                                                var gridItem = $('<div class="' + colClass + '"></div>');
                                                gridItem.append(button);
                                                rowContainer.append(gridItem);
                                            });

                                            roomContainer.append(rowContainer);
                                            $('#banAnButtons').append(roomContainer);
                                        });

                                        // Nếu có bàn đã chọn trước đó, đánh dấu lại (nếu còn trống)
                                        if (idBan) {
                                            var selectedButton = $('#banAnButtons button[data-id="' + idBan + '"]');
                                            if (selectedButton.length > 0) {
                                                var tenBan = selectedButton.text();
                                                var soGhe = selectedButton.data('so-ghe');
                                                selectedButton.addClass('selected'); // Thêm class để làm nổi bật bàn đã chọn

                                                $('#selectedTableName').text(tenBan);
                                                $('#selectedTableSeats').text(soGhe);
                                                $('#selectedTableInfo').show();
                                                $('#selectedTableId').val(idBan);

                                                // Nếu bàn hợp lệ, thêm vào danh sách selectedIds
                                                if (!selectedIds.includes(idBan)) {
                                                    selectedIds.push(idBan);
                                                }

                                                updateHiddenInputs(); // Cập nhật input ẩn ngay sau khi chọn bàn
                                            } else {
                                                idBan = null; // Nếu bàn không còn khả dụng, bỏ chọn
                                                $('#selectedTableInfo').hide();
                                            }
                                        }

                                    } catch (error) {
                                        console.error('Lỗi khi xử lý AJAX: ', error);
                                        alert('Có lỗi xảy ra, vui lòng thử lại sau.');
                                    }
                                }

                                // Khi trang tải lần đầu, load dữ liệu từ server
                                if (thoiGianDen && idBan) {
                                    loadBanAn(thoiGianDen);
                                }

                                // Khi chọn thời gian mới, load lại danh sách bàn
                                $('#thoi_gian_den').on('change', function() {
                                    var newTime = $(this).val();
                                    loadBanAn(newTime);
                                });

                                // Khi click vào bàn, chọn bàn mới và bỏ chọn bàn cũ
                                $(document).on('click', '#banAnButtons button', function() {
                                    if ($(this).hasClass('btn-danger')) return; // Không cho chọn bàn đã đặt

                                    var idBan = $(this).data('id');

                                    if ($(this).hasClass('selected')) {
                                        // Nếu đã chọn, thì hủy chọn
                                        $(this).removeClass('selected');

                                        // Cập nhật lại danh sách bàn đã chọn
                                        updateSelectedTables();
                                    } else {
                                        // Nếu chưa chọn, thì thêm vào danh sách chọn
                                        $(this).addClass('selected');
                                        updateSelectedTables();
                                    }
                                });

                                let selectedIds = [];

                                function toggleBanAn(id) {
                                    let index = selectedIds.indexOf(id);
                                    if (index === -1) {
                                        selectedIds.push(id); // Thêm nếu chưa có
                                    } else {
                                        selectedIds.splice(index, 1); // Xóa nếu đã chọn
                                    }

                                    updateSelectedTables();
                                    updateHiddenInputs(); // Cập nhật input ẩn sau khi chọn bàn
                                }



                                function updateHiddenInputs() {
                                    let selectedBanInputs = document.getElementById("selectedBanInputs");

                                    if (!selectedBanInputs) {
                                        console.error("Không tìm thấy #selectedBanInputs trong DOM!");
                                        return;
                                    }

                                    selectedBanInputs.innerHTML = ""; // Xóa input cũ

                                    selectedIds.forEach(id => {
                                        let input = document.createElement("input");
                                        input.type = "hidden";
                                        input.name = "selectedIds[]"; // Laravel sẽ nhận dạng mảng
                                        input.value = id;
                                        selectedBanInputs.appendChild(input);
                                    });

                                    //console.log("Danh sách bàn đã chọn:", selectedIds);
                                    //console.log("Hidden inputs cập nhật:", selectedBanInputs.innerHTML);
                                }


                                // Hàm cập nhật danh sách bàn đã chọn
                                function updateSelectedTables() {
                                    var selectedTables = $('#banAnButtons button.selected');
                                    selectedIds = []; // Sử dụng biến global
                                    var selectedInfo = [];

                                    selectedTables.each(function() {
                                        selectedIds.push($(this).data('id'));
                                        selectedInfo.push({
                                            tenBan: $(this).text(),
                                            soGhe: $(this).data('so-ghe')
                                        });
                                    });

                                    console.log("Danh sách bàn đã chọn:", selectedIds);
                                    //console.log("Phần tử chứa input ẩn:", document.getElementById("selectedBanInputs"));

                                    updateHiddenInputs(); // Cập nhật input ẩn

                                    if (selectedTables.length > 0) {
                                        $('#selectedTableInfo').show();
                                        $('#selectedTableId').val(selectedIds.join(',')); // Lưu nhiều ID cách nhau dấu phẩy
                                        var html = selectedInfo.map(table => `<p>${table.tenBan} (${table.soGhe} ghế)</p>`).join(
                                            '');
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
