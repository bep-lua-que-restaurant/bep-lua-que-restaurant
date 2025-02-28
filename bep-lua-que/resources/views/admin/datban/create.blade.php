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

                        <!-- Modal Nhập File -->
                        <div class="modal fade" id="importExcelModal" tabindex="-1" role="dialog"
                            aria-labelledby="importExcelModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="importExcelModalLabel">Nhập dữ liệu từ Excel</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('ban-an.import') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group">
                                                <label for="file">Chọn file Excel (.xlsx, .xls)</label>
                                                <input type="file" name="file" id="file" class="form-control"
                                                    required>
                                                @if ($errors->has('file'))
                                                    <small class="text-danger">*{{ $errors->first('file') }}</small>
                                                @endif
                                            </div>
                                            <div class="text-right">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Hủy</button>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fa fa-upload"></i> Nhập dữ liệu
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
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
                                <label class="form-label">Căn cước công dân:</label>
                                <input type="number" class="form-control" id="customerCanCuoc" name="customer_cancuoc">
                                @error('customer_cancuoc')
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
                                <input type="datetime-local" class="form-control" name="thoi_gian_den" id="thoi_gian_den"
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
                                <div id="selectedBanInputs"></div>
                            </div>

                            <button type="submit" class="btn btn-primary" id="submitBtn">Đặt bàn</button>
                        </form>



                    </div>

                    <script>
                        $(document).ready(function() {
                            var now = new Date();
                            var currentDateTime = now.toISOString().slice(0, 16); // Lấy định dạng YYYY-MM-DDTHH:MM

                            // Đặt min cho input datetime-local
                            $('#thoi_gian_den').attr('min', currentDateTime);

                            // Kiểm tra thời gian đã chọn
                            $('#thoi_gian_den').on('change', function() {
                                var selectedDateTime = $(this).val();
                                if (selectedDateTime < currentDateTime) {
                                    alert('Không thể chọn thời gian trong quá khứ!');
                                    $(this).val(''); // Xóa giá trị không hợp lệ
                                }
                            });
                        });
                    </script>

                    <script>
                        $(document).on('click', '#banAnButtons button', function() {
                            var button = $(this);
                            var banAnId = button.data('id');
                            var soGhe = button.data('so-ghe');

                            var selectedBans = $('#banAnButtons button.selected');
                            var totalSeats = 0;

                            selectedBans.each(function() {
                                totalSeats += parseInt($(this).data('so-ghe'));
                            });

                            if (button.hasClass('selected')) {
                                // Bỏ chọn bàn
                                button.removeClass('selected');
                                //button.prop('disabled', false); // Mở khóa bàn ăn
                                totalSeats -= soGhe;

                                // Xóa input ẩn
                                $('#selectedBanInputs input[value="' + banAnId + '"]').remove();
                            } else {
                                // Chọn bàn mới
                                button.addClass('selected');
                                //button.prop('disabled', true);
                                totalSeats += soGhe;

                                // Thêm input ẩn
                                $('#selectedBanInputs').append(
                                    '<input type="hidden" name="ban_an_ids[]" value="' + banAnId + '">'
                                );
                            }

                            // Cập nhật tổng số ghế đã chọn
                            console.log('Tổng số ghế đã chọn: ', totalSeats);
                        });
                    </script>

                    {{-- <script>
                        // Lắng nghe sự thay đổi thời gian
                        $('#thoi_gian_den').on('change', async function() {
                            var thoiGianDen = $(this).val();
                            var formattedTime = moment(thoiGianDen, "YYYY-MM-DDTHH:mm").format("YYYY-MM-DD HH:mm:ss");

                            try {
                                // Gửi AJAX để lấy các bàn ăn chưa bị đặt trong khoảng thời gian này
                                const response = await $.ajax({
                                    url: '{{ route('admin.datban.filter') }}',
                                    method: 'GET',
                                    data: {
                                        thoi_gian_den: formattedTime
                                    }
                                });

                                // Clear previous buttons
                                $('#banAnButtons').html('');

                                // Gộp các bàn ăn theo phòng và tạo button
                                var groupedBanAns = {};

                                $.each(response, function(index, banAn) {
                                    if (!groupedBanAns[banAn.ten_phong_an]) {
                                        groupedBanAns[banAn.ten_phong_an] = [];
                                    }
                                    groupedBanAns[banAn.ten_phong_an].push(banAn);
                                });

                                // Hiển thị các button cho mỗi phòng
                                $.each(groupedBanAns, function(tenPhongAn, banAns) {
                                    var roomContainer = $('<div class="room-container"></div>');
                                    var roomTitle = $('<h5>' + tenPhongAn + '</h5>');
                                    roomContainer.append(roomTitle);

                                    $.each(banAns, function(index, banAn) {
                                        var button = $(
                                            '<button type="button" class="btn btn-primary m-1" data-id="' +
                                            banAn.id + '" data-so-ghe="' + banAn.so_ghe + '">' +
                                            banAn.ten_ban + ' (' + banAn.so_ghe + ' ghế)' + '</button>');
                                        roomContainer.append(button);
                                    });

                                    $('#banAnButtons').append(roomContainer);
                                });

                            } catch (error) {
                                console.error('Lỗi khi xử lý AJAX: ', error);
                                alert('Có lỗi xảy ra, vui lòng thử lại sau.');
                            }
                        });
                    </script> --}}

                    <script>
                        // Lắng nghe sự thay đổi thời gian
                        $('#thoi_gian_den').on('change', async function() {
                            var thoiGianDen = $(this).val();
                            var selectedTime = moment(thoiGianDen, "YYYY-MM-DDTHH:mm");
                            var currentTime = moment(); // Lấy thời gian hiện tại

                            // **Kiểm tra nếu thời gian được chọn nhỏ hơn thời gian hiện tại**
                            if (selectedTime.isBefore(currentTime)) {
                                // Xóa danh sách bàn đã hiển thị (nếu có)
                                $('#banAnButtons').html('');
                                // Thông báo cho người dùng rằng thời gian không hợp lệ
                                alert(
                                    "⚠️ **Thời gian được chọn không hợp lệ!** Vui lòng chọn thời gian sau thời gian hiện tại."
                                );
                                return; // Dừng xử lý nếu điều kiện không thỏa mãn
                            }

                            // Nếu thời gian hợp lệ, tiếp tục xử lý
                            var formattedTime = selectedTime.format("YYYY-MM-DD HH:mm:ss");

                            try {
                                // Gửi AJAX để lấy danh sách bàn ăn chưa bị đặt trong khoảng thời gian này
                                const response = await $.ajax({
                                    url: '{{ route('admin.datban.filter') }}',
                                    method: 'GET',
                                    data: {
                                        thoi_gian_den: formattedTime
                                    }
                                });

                                // Xóa danh sách bàn trước đó
                                $('#banAnButtons').html('');

                                // **Nhóm các bàn ăn theo phòng**
                                var groupedBanAns = {};
                                $.each(response, function(index, banAn) {
                                    if (!groupedBanAns[banAn.ten_phong_an]) {
                                        groupedBanAns[banAn.ten_phong_an] = [];
                                    }
                                    groupedBanAns[banAn.ten_phong_an].push(banAn);
                                });

                                // **Hiển thị các bàn ăn theo phòng**
                                $.each(groupedBanAns, function(tenPhongAn, banAns) {
                                    var roomContainer = $('<div class="room-container"></div>');
                                    var roomTitle = $('<h5 style="color: #007bff;">' + tenPhongAn + '</h5>');
                                    roomContainer.append(roomTitle);

                                    $.each(banAns, function(index, banAn) {
                                        var button = $(
                                            '<button type="button" class="btn btn-primary m-1" data-id="' +
                                            banAn.id + '" data-so-ghe="' + banAn.so_ghe + '">' +
                                            banAn.ten_ban + ' (' + banAn.so_ghe + ' ghế)' + '</button>');
                                        roomContainer.append(button);
                                    });

                                    $('#banAnButtons').append(roomContainer);
                                });

                            } catch (error) {
                                console.error('Lỗi khi xử lý AJAX: ', error);
                                alert('Có lỗi xảy ra, vui lòng thử lại sau.');
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
