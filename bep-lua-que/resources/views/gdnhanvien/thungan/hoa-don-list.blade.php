{{-- <div class="alert alert-warning d-flex align-items-center" role="alert">
    <span class="me-2">🔔</span>
    <span>Bạn vừa cập nhật đơn hàng. Click <a href="#" class="fw-bold text-decoration-none">Thông báo</a> để gửi
        thông tin chế biến đến bar bếp.</span>
</div> --}}

<div class="d-flex justify-content-between align-items-center mt-3">
    <div class="d-flex align-items-center">
        <span style="cursor: pointer;" class="mx-2 so-nguoi">👥 0</span>

        <span style="cursor: pointer;" class="mx-2">✏️</span>
        <span style="cursor: pointer;" class="mx-2" data-bs-toggle="modal" data-bs-target="#modalGhepBan">➕ Ghép
            bàn</span>
        <span style="cursor: pointer;" class="mx-2 openTachBan" data-bs-toggle="modal" data-bs-target="#modalTachBan"
            data-ban="1">➖ Tách bàn</span>
    </div>
</div>

<div class="my-2 p-2 border rounded bg-white">
    <h6 class="text-success fw-semibold">Mã hóa đơn:</h6>
    <p class="fs-6 text-dark mb-0" id="ma_hoa_don">Chưa có</p>
</div>

<table class="table table-bordered">
    <thead class="table-light">
        <tr>
            <th>#</th>
            <th>Sản phẩm</th>
            <th class="text-center">SL</th>
            <th class="text-end">Giá</th>
            <th class="text-end">Tổng</th>
            <th></th>
        </tr>
    </thead>
    <tbody id="hoa-don-body">

    </tbody>
</table>
<div class="d-flex justify-content-end align-items-center mt-3">
    <span class="text-muted mx-4">Tổng tiền:</span>
    <span class="fs-4 fw-bold text-success" id="tong-tien">0</span>
</div>


<div class="text-centerr mt-3">
    <button class="btn btn-success btn-sm px-4" type="button" data-bs-toggle="offcanvas"
        data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">Thanh toán </button>
    <button class="btn-thong-bao btn btn-primary btn-sm px-4"> Thông báo</button>
</div>
<!-- Offcanvas -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel"
    style="width: 70%;">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasRightLabel">
            Phiếu thanh toán - <span id="tableInfo"> </span>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <!-- Nội dung giao diện thanh toán -->
        <div class="mb-3">
            <label class="form-label">Khách</label>
            <select class="form-control" id="customerSelect">
                <option value="0">Khách lẻ</option>
                <option value="new">Thêm mới khách</option>
            </select>
        </div>
        <!-- Bảng hiển thị các món hàng -->
        <div class="table-responsive mb-3">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Sản phẩm</th>
                        <th scope="col">Số lượng</th>
                        <th scope="col">Đơn giá</th>
                        <th scope="col">Tổng cộng</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>GIN FIZZ</td>
                        <td>1</td>
                        <td>30,000</td>
                        <td>30,000</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="mb-3">
            <label for="totalAmount" class="form-label">Khách cần trả</label>
            <input type="text" class="form-control" id="totalAmount" value="30,000 VND" readonly>
        </div>
        <div class="mb-3">
            <label for="paymentMethod" class="form-label">Phương thức thanh toán</label>
            <select class="form-select" id="paymentMethod">
                <option value="cash">Tiền mặt</option>
                <option value="card">Thẻ tín dụng</option>
                <option value="transfer">Chuyển khoản</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="paymentDetails" class="form-label">Chi tiết thanh toán</label>
            <textarea class="form-control" id="paymentDetails" rows="3" placeholder="Nhập chi tiết thanh toán..."></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Số tiền khách đưa</label>
            <input type="number" class="form-control" id="amountGiven" placeholder="Nhập số tiền khách đưa"
                oninput="calculateChange()">
        </div>

        <!-- Tiền thừa trả khách -->
        <div class="mb-3">
            <label class="form-label">Tiền thừa trả khách</label>
            <input type="text" class="form-control" id="changeToReturn" value="0" readonly>
        </div>
        <!-- Nút xác nhận thanh toán -->
        <button class="btn btn-success" id="btnThanhToan">Thanh toán </button>
    </div>
</div>


<!-- Modal nhập số lượng khách -->
<div class="modal fade" id="peopleModal" tabindex="-1" aria-labelledby="peopleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-body">
                <label for="numPeople" class="form-label">Số lượng khách</label>
                <input type="number" id="numPeople" class="form-control" value="0" min="0">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary btn-sm" onclick="saveNumberOfPeople()">Lưu</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal thêm khách hàng -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCustomerModalLabel">Thêm mới khách hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Họ và tên</label>
                    <input type="text" class="form-control" id="customerNameInput">
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" id="customerEmail">
                </div>
                <div class="mb-3">
                    <label class="form-label">Địa chỉ</label>
                    <input type="text" class="form-control" id="customerAddress">
                </div>
                <div class="mb-3">
                    <label class="form-label">Số điện thoại</label>
                    <input type="text" class="form-control" id="customerPhone">
                </div>
                <div class="mb-3">
                    <label class="form-label">Căn cước công dân</label>
                    <input type="text" class="form-control" id="customerCCCD">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary btn-sm" id="saveCustomerBtn">Lưu</button>
            </div>
        </div>
    </div>
</div>

{{-- modal nhập số khách --}}
<div class="modal fade" id="modalSoNguoi" tabindex="-1" aria-labelledby="modalSoNguoiLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <label for="soNguoiInput" class="form-label">Nhập số người:</label>
                <input type="number" class="form-control" id="soNguoiInput" min="1" value="1">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary btn-sm" id="btnLuuSoNguoi">Lưu</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalGhepBan" tabindex="-1" aria-labelledby="modalGhepBanLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- Thay đổi kích thước modal -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalGhepBanLabel">Tách/Ghép Bàn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label class="form-label">Ghép tới:</label>
                <select class="form-select" id="chonBanGhep">
                    <option value="">-- Chọn bàn --</option>
                    <!-- Danh sách bàn sẽ được thêm vào đây bằng JS -->
                </select>
            </div>

            <div id="thongTinBan" class="m-3" style="display: none;">
                <h6 class="text-center">Thông tin </h6>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>khách hàng</th>
                            <th>Bàn</th>
                            <th>Mã hóa đơn</th>
                            <th>Số lượng hàng</th>
                            <th>Tổng tiền (VNĐ)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td id="khachHang">Khách lẻ</td>
                            <td id="tenBan"></td>
                            <td id="maHoaDon"></td>
                            <td id="soLuongMon"></td>
                            <td id="tongTien"></td>
                        </tr>
                    </tbody>
                </table>
            </div>


            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary btn-sm" id="btnXacNhanGhepBan">Xác nhận</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tách Bàn & Món -->
<div class="modal fade" id="modalTachBan" tabindex="-1" aria-labelledby="modalTachBanLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTachBanLabel">Tách bàn & món</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <!-- Chọn bàn mới hoặc tạo hóa đơn -->
                    <div class="row">
                        <!-- Cột chọn hóa đơn -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="banMoi" class="form-label">Chọn hóa đơn</label>
                                <select class="form-select" id="banMoi">
                                    <option value="new" selected>Tạo hóa đơn mới</option>
                                    <option value="2">Bàn 2</option>
                                    <option value="3">Bàn 3</option>
                                </select>
                            </div>
                        </div>

                        <!-- Cột chọn bàn cần tách -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="banGoc" class="form-label">Chọn bàn</label>
                                <select class="form-select" id="banGoc">
                                    <option value="1" selected>Bàn 1</option>
                                    <option value="2">Bàn 2</option>
                                    <option value="3">Bàn 3</option>
                                </select>
                            </div>
                        </div>
                    </div>


                    <!-- Danh sách món ăn -->
                    <div class="mb-3">
                        <label class="form-label">Hóa đơn hiện tại:</label>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tên món</th>
                                    <th>Số lượng</th>
                                    <th>Đơn giá</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody id="hoa-don-tach-body">
                                <tr>
                                    <td colspan="5" class="text-center">Chưa có hóa đơn</td>
                                </tr>
                            </tbody>
                        </table>

                    </div>

                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-primary btn-sm">Tách</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    function calculateChange() {
        let totalAmount = parseInt(document.getElementById("totalAmount").value.replace(/\D/g, "")) || 0;
        let amountGiven = parseInt(document.getElementById("amountGiven").value) || 0;
        let change = amountGiven - totalAmount;

        document.getElementById("changeToReturn").value = change > 0 ? change.toLocaleString() + " VND" : "0 VND";
    }

    $(document).ready(function() {
        // Khi chọn "Thêm mới khách", hiển thị modal
        $("#customerSelect").change(function() {
            if ($(this).val() === "new") {
                $("#addCustomerModal").modal("show");
            }
        });

        // Khi nhấn lưu khách hàng mới
        $("#saveCustomerBtn").click(function() {
            var name = $("#customerNameInput").val();
            var email = $("#customerEmail").val();
            var address = $("#customerAddress").val();
            var phone = $("#customerPhone").val();
            var cccd = $("#customerCCCD").val();

            console.log({
                name: name,
                email: email,
                address: address,
                phone: phone,
                cccd: cccd
            });
            $.ajax({
                url: "/add-customer",
                type: "POST",
                data: {
                    name: name,
                    email: email,
                    address: address,
                    phone: phone,
                    cccd: cccd,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {

                        // console.log("Khách hàng mới có ID:", response.customer_id);
                        // Thêm khách mới vào select
                        $("#customerSelect").append(
                            `<option value="${response.customer_id}" selected>${name}</option>`
                        );
                        $("#addCustomerModal").modal("hide"); // Đóng modal
                    } else {
                        alert("Lỗi: " + response.message);
                    }
                },
                error: function() {
                    alert("Có lỗi xảy ra, vui lòng thử lại!");
                }
            });
        });
    });

    $("#customerSelect").change(function() {
        var selectedCustomerId = $(this).val();
        console.log("Khách hàng được chọn có ID:", selectedCustomerId);
    });

    $('#btnThanhToan').on('click', function() {
        // Lấy ID bàn hiện tại từ data (nếu có)
        var banId = $('#ten-ban').data('currentBan');
        var soNguoi = $(".so-nguoi").data("soNguoi") || 1; // Lấy số người từ data
        // console.log('ID bàn hiện tại: ', banId);
        // Kiểm tra nếu có ID bàn
        if (banId) {
            $.ajax({
                url: "/update-ban-status", // Đường dẫn đến API cập nhật trạng thái bàn
                method: "POST",
                data: {
                    ban_an_id: banId,
                    khach_hang_id: $("#customerSelect").val() || null,
                    so_nguoi: soNguoi,
                    _token: $('meta[name="csrf-token"]').attr("content") // Lấy CSRF token từ meta tag
                },
                success: function(response) {
                    if (response.success) {
                        showToast("Đã thanh toán đơn hàng", "success"); // Thông báo thành công
                        var offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById(
                            "offcanvasRight"));
                        offcanvas.hide();
                        resetGiaoDienHoaDon()
                    } else {
                        showToast("Thanh toán không thành công.", "danger"); // Thông báo lỗi
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Có lỗi xảy ra: ", error);
                    showToast("Lỗi khi cập nhật trạng thái bàn.", "danger");
                }
            });
        } else {
            showToast("Không tìm thấy ID bàn!", "warning");
        }
    });

    // thông báo toast
    function showToast(message, type) {
        var toastEl = $("#toastMessage");

        // Thay đổi màu sắc tùy theo loại thông báo
        toastEl.removeClass("text-bg-success text-bg-danger text-bg-warning");
        toastEl.addClass("text-bg-" + type);

        // Cập nhật nội dung thông báo
        toastEl.find(".toast-body").text(message);

        // Hiển thị Toast
        var toast = new bootstrap.Toast(toastEl[0]);
        toast.show();
    }

    function resetGiaoDienHoaDon() {
        $("#hoa-don-body").html('<tr><td colspan="5" class="text-center">Chưa có hóa đơn nào</td></tr>');
        $(".offcanvas-body tbody").html('<tr><td colspan="5" class="text-center">Chưa có hóa đơn nào</td></tr>');
        $("#ten-ban").text("Bàn");
        $("#ma_hoa_don").text("Chưa có");
        $("#tong-tien").text("0 VNĐ"); // Reset tổng tiền
        $('.so-nguoi').text("👥 0"); // Reset số người
        $("#totalAmount").val("0 VND"); // Reset tổng tiền trong offcanvas
        $("#tableInfo").text("Bàn chưa chọn"); // Reset tên bàn

        // console.log("🔄 Giao diện hóa đơn đã được reset!");
    }

    // số người
    document.addEventListener("DOMContentLoaded", function() {
        let soNguoiElement = document.querySelector(".so-nguoi");

        soNguoiElement.addEventListener("click", function() {
            let banId = $('#ten-ban').data('currentBan'); // Lấy ID bàn từ #ten-ban

            if (!banId || banId === 0) {
                alert("Vui lòng chọn bàn trước khi nhập số người!");
            } else {
                let modal = new bootstrap.Modal(document.getElementById('modalSoNguoi'));
                modal.show(); // Chỉ mở modal khi bàn đã được chọn
            }
        });

        document.getElementById("btnLuuSoNguoi").addEventListener("click", function() {
            let soNguoi = document.getElementById("soNguoiInput").value;
            $(".so-nguoi").html(`👥 ${soNguoi}`); // Cập nhật số người hiển thị
            $(".so-nguoi").data("soNguoi", soNguoi); // Lưu vào jQuery data
            console.log("Số người đã lưu:", $(".so-nguoi").data("soNguoi"));
            $('#modalSoNguoi').modal('hide'); // Đóng modal
        });
    });

    // Tách/Ghép bàn
    $(document).ready(function() {
        // Khi modal mở, load danh sách bàn
        $('#modalGhepBan').on('shown.bs.modal', function() {
            var idBanHienTai = $('#ten-ban').data('currentBan');
            $('#ten-ban').attr('data-currentBan', idBanHienTai);
            var apiUrlShowBanGhep = "{{ route('thungan.getBanDeGhep') }}";
            $.ajax({
                url: apiUrlShowBanGhep, // File PHP lấy danh sách bàn từ database
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    let select = $('#chonBanGhep');
                    select.empty(); // Xóa danh sách cũ
                    select.append('<option value="">-- Chọn bàn --</option>');

                    // Lọc danh sách bàn, bỏ bàn hiện tại
                    data.forEach(function(ban) {
                        if (ban.id != idBanHienTai) { // Bỏ bàn đang chọn
                            select.append(
                                `<option value="${ban.id}">${ban.ten_ban} - ${ban.trang_thai}</option>`
                            );
                        }
                    });
                },
                error: function() {
                    alert('Lỗi khi tải danh sách bàn!');
                }
            });
        });

        // Khi chọn bàn, gọi API để lấy thông tin bill
        $('#chonBanGhep').on('change', function() {
            let idBan = $(this).val();
            $('#chonBanGhep').data('selectedBan', idBan);
            // console.log("Bàn muốn ghép:", $('#chonBanGhep').data('selectedBan'));
            if (idBan) {
                let apiUrlGetBill = "{{ route('thungan.getBillBan', ':id') }}".replace(':id', idBan);
                $.ajax({
                    url: apiUrlGetBill,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.bill) {
                            $('#tenBan').text(response.bill.ten_ban);
                            $('#maHoaDon').text(response.bill.ma_hoa_don);
                            $('#soLuongMon').text(response.bill.tong_so_luong_mon_an ||
                                '0'); // Hiển thị tổng số lượng món ăn
                            $('#tongTien').text(response.bill.tong_tien.toLocaleString() +
                                ' VNĐ');

                            $('#thongTinBan').show(); // Hiển thị thông tin bàn
                        } else {
                            $('#thongTinBan').hide();
                        }
                    },
                    error: function() {
                        alert('Lỗi khi tải thông tin bill!');
                    }
                });
            } else {
                $('#thongTinBan').hide();
            }
        });
    });

    // xác nhận ghép bàn
    $(document).ready(function() {
        $('#btnXacNhanGhepBan').click(function() {
            let urlGhep = "{{ route('thungan.ghepBan') }}"
            let idBanHienTai = $('#ten-ban').attr('data-currentBan');
            let idBanMoi = $('#chonBanGhep').val(); // ID bàn mới được chọn
            if (!idBanMoi) {
                alert('Vui lòng chọn bàn cần ghép!');
                return;
            }

            $.ajax({
                url: urlGhep, // API xử lý ghép bàn trong Laravel
                type: 'POST',
                data: {
                    id_ban_hien_tai: idBanHienTai,
                    id_ban_moi: idBanMoi,
                    _token: $('meta[name="csrf-token"]').attr('content') // CSRF Token
                },
                success: function(response) {
                    $('#modalGhepBan').modal('hide'); // Đóng modal
                    showToast("Ghép bàn thành công", "success"); // Thông báo thành công


                },
                error: function(xhr) {
                    console.log("Lỗi chi tiết:", xhr.responseText);
                    alert(xhr.responseJSON.error);
                }
            });
        });
    });

    //tách bàn
    $(document).ready(function() {
        $('#modalTachBan').on('shown.bs.modal', function() {
            var idBanHienTai = $('#ten-ban').data('currentBan'); // Lấy ID bàn hiện tại
            var apiUrlShowBanGhep = "{{ route('thungan.getBanDeGhep') }}"; // API lấy danh sách bàn
            var hoaDonId = $('#ten-ban').data('hoaDonId'); // Lấy hóa đơn ID đã lưu
            // console.log("🔥 Hóa đơn ID khi mở modal:", hoaDonId);
            $.ajax({
                url: apiUrlShowBanGhep,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    let selectBanGoc = $('#banGoc');
                    selectBanGoc.empty(); // Xóa danh sách cũ

                    // Lọc bỏ bàn hiện tại khỏi danh sách
                    let danhSachBan = data.filter(ban => ban.id != idBanHienTai);

                    if (danhSachBan.length === 0) {
                        selectBanGoc.append('<option value="">Không có bàn nào</option>');
                        return;
                    }

                    // Hiển thị danh sách bàn vào #banGoc
                    danhSachBan.forEach(function(ban, index) {
                        selectBanGoc.append(
                            `<option value="${ban.id}" ${index === 0 ? "selected" : ""}>${ban.ten_ban} - ${ban.trang_thai}</option>`
                        );
                    });
                },
                error: function(xhr) {
                    console.error("Lỗi API:", xhr.status, xhr.responseText);
                    alert('Lỗi khi tải danh sách bàn!');
                }
            });

            // Gọi API để lấy chi tiết hóa đơn và hiển thị vào modal
            if (hoaDonId) {
                $.ajax({
                    url: "/hoa-don/get-details",
                    method: "GET",
                    data: {
                        hoa_don_id: hoaDonId
                    },
                    success: function(response) {
                        let hoaDonTachBody = $("#hoa-don-tach-body");
                        hoaDonTachBody.empty();
                        // console.log("Chi tiết hóa đơn:", response);
                        if (response.chi_tiet_hoa_don.length > 0) {
                            let index = 1;
                            response.chi_tiet_hoa_don.forEach((item) => {
                                let row = `
                            <tr>
                                <td>${index}</td>
                                <td>${item.tenMon}</td>
                                <td class="text-center">${item.so_luong}</td>
                                <td class="text-end">${item.don_gia.toLocaleString()} VNĐ</td>
                                <td class="text-end">${(item.so_luong * item.don_gia).toLocaleString()} VNĐ</td>
                            </tr>`;
                                hoaDonTachBody.append(row);
                                index++;
                            });
                        } else {
                            hoaDonTachBody.html(
                                '<tr><td colspan="5" class="text-center">Chưa có món nào</td></tr>'
                                );
                        }
                    },
                    error: function(xhr) {
                        console.error("🔥 Lỗi khi tải chi tiết hóa đơn:", xhr.responseText);
                    }
                });
            } else {
                $("#hoa-don-tach-body").html(
                    '<tr><td colspan="5" class="text-center">Chưa có hóa đơn</td></tr>');
            }
        });
    });
</script>
