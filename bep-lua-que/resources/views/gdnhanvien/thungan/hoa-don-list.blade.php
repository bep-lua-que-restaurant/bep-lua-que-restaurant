<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<!-- CDN SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .select2-results__option {
        background-color: #f0f0f0 !important;
        color: black !important;
    }

    .select2-results__option--highlighted {
        background-color: #d1e7fd !important;
        color: black !important;
    }

    .so-luong-tach {
        width: 60px;
        /* Giới hạn độ rộng */
        text-align: center;
        /* Căn giữa số */
        border: none;
        /* Xóa viền để trông gọn gàng */
        outline: none;
        /* Xóa viền xanh khi focus */
        font-size: 16px;
        /* Cỡ chữ lớn hơn */
    }

    .input-group {
        display: flex;
        align-items: center;
        gap: 5px;
        /* Tạo khoảng cách giữa các nút */
    }

    .table-responsive {
        max-height: 400px;
        overflow-y: auto;
        border: 1px solid #dee2e6;
        position: relative;
    }

    .table thead,
    .table tfoot {
        position: sticky;
        background-color: #fff;
        /* Giữ nền trắng khi cuộn */
        z-index: 10;
    }

    .table thead {
        top: 0;
    }

    .table tfoot {
        bottom: 0;
    }

    .is-invalid {
        border: 1px solid red;
    }

    .error-message {
        font-size: 12px;
        margin-top: 5px;
    }
</style>
<div class="table-responsive" style="max-height: 400px; overflow-y: auto; border: 1px solid #dee2e6;">
    <table class="table table-bordered table-sm">
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
            <!-- Dữ liệu hóa đơn sẽ được thêm vào đây -->
        </tbody>
        <tfoot id="tfoot-hoaDon">
            <tr>
                <td colspan="6" class="text-start text-muted" style="font-size: 14px; font-weight: 400;">
                    Mã hóa đơn: <span id="maHoaDon"></span>
                </td>
            </tr>
        </tfoot>
    </table>
</div>

<div class="d-flex justify-content-end align-items-center mt-3">
    <span class="text-muted mx-4">Tổng tiền:</span>
    <span class="fs-4 fw-bold text-success" id="tong-tien">0</span>
</div>

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

<div class="d-flex justify-content-between align-items-center mt-3">
    <!-- Nút bấm -->
    <div class="nut-hoa-don">
        <button class="btn btn-success btn-sm px-4" type="button" data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasRight" aria-controls="offcanvasRight" id="thanhToan-btn">Thanh toán</button>
        <button class="btn-thong-bao btn btn-primary btn-sm px-4">Thông báo</button>
    </div>

    <!-- Chú thích trạng thái -->
    <div class="text-end small">
        <h6 class="mb-2 fw-bold">Trạng thái món</h6>
        <div class="d-flex align-items-center mb-1">
            <span class="rounded-circle bg-danger d-inline-block"
                style="width: 10px; height: 10px; margin-right: 6px;"></span>
            <span class="text-muted">Chờ chế biến</span>
        </div>
        <div class="d-flex align-items-center mb-1">
            <span class="rounded-circle bg-warning d-inline-block"
                style="width: 10px; height: 10px; margin-right: 6px;"></span>
            <span class="text-muted">Đang nấu</span>
        </div>
        <div class="d-flex align-items-center">
            <span class="rounded-circle bg-success d-inline-block"
                style="width: 10px; height: 10px; margin-right: 6px;"></span>
            <span class="text-muted">Hoàn thành</span>
        </div>
    </div>
</div>


<!-- Offcanvas -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel"
    style="width: 70%; padding: 20px;">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasRightLabel">
            Phiếu thanh toán - <span id="tableInfo"> </span> - <span id="maHoaDonInFo">Chưa có hóa đơn</span>
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
        <div id="hoa-don-thanh-toan" class="table-responsive mb-3">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Sản phẩm</th>
                        <th scope="col">Số lượng</th>
                        <th scope="col">Đơn giá</th>
                        <th scope="col">Tổng cộng</th>
                    </tr>
                </thead>
                <thead id="hoa-don-thanh-toan-body">
                    <!-- Dữ liệu hóa đơn sẽ được hiển thị ở đây -->
                </thead>
            </table>
        </div>

        {{-- <div class="mb-3">
            <label for="discountCode" class="form-label">Mã giảm giá</label>
            <input type="text" class="form-control" id="discountCode" placeholder="Nhập mã giảm giá">
        </div> --}}

        <!-- Đặt Khách cần trả và Phương thức thanh toán nằm ngang -->
        <div class="d-flex mb-3 align-items-stretch">
            <div class="flex-fill me-2">
                <label for="totalAmount" class="form-label">Khách cần trả</label>
                <input type="text" class="form-control form-control-lg" id="totalAmount" value="" readonly>
            </div>
            <div class="flex-fill ms-2">
                <label for="paymentMethod" class="form-label">Phương thức thanh toán</label>
                <select class="form-select form-select-lg" id="paymentMethod">
                    <option value="tien_mat">Tiền mặt</option>
                    <option value="the">Thẻ tín dụng</option>
                    <option value="tai_khoan">Chuyển khoản</option>
                </select>
            </div>
        </div>


        <!-- Chi tiết thanh toán -->
        <div class="mb-3">
            <label for="paymentDetails" class="form-label">Chi tiết thanh toán</label>
            <textarea class="form-control" id="paymentDetails" rows="3" placeholder="Nhập chi tiết thanh toán..."></textarea>
        </div>

        <!-- Số tiền khách đưa và Tiền thừa trả khách nằm ngang -->
        <div class="d-flex mb-3 align-items-stretch">
            <div class="flex-fill me-2">
                <label class="form-label">Số tiền khách đưa</label>
                <input type="number" class="form-control form-control-lg" id="amountGiven"
                    placeholder="Nhập số tiền khách đưa" oninput="calculateChange()">
            </div>
            <div class="flex-fill ms-2">
                <label class="form-label">Tiền thừa trả khách</label>
                <input type="text" class="form-control form-control-lg" id="changeToReturn" value="0"
                    readonly>
            </div>
        </div>


        <!-- Nút xác nhận thanh toán -->
        <button class="btn btn-success btn-sm" id="btnThanhToan">Thanh toán</button>
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
                    <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
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
                <!-- Ghi chú bắt buộc -->
                <p class="text-muted">Chú ý: Các trường có dấu <span class="text-danger">*</span> là bắt buộc.</p>
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
                <h5 class="modal-title" id="modalGhepBanLabel">Ghép Bàn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label class="form-label">Ghép tới:</label>
                <select id="chonBanGhep" class="form-control select2" multiple="multiple">
                    <option value="">-- Chọn bàn --</option>
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
                            <th>Số khách</th>
                            <th>Tổng tiền (VNĐ)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td id="khachHang">Khách lẻ</td>
                            <td id="tenBan"></td>
                            <td id="maHoaDon"></td>
                            <td id="soLuongMon"></td>
                            <th id="so_nguoi"></th>
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
                <h5 class="modal-title" id="modalTachBanLabel">
                    Tách hóa đơn
                    <small class="text-muted ms-2">Hóa đơn hiện tại: <span id="tenHoaDon"></span></small>
                </h5>
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

                        <!-- Cột chọn bàn để tách sang -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="banGoc" class="form-label">Chọn bàn</label>
                                <select class="form-select" id="banGoc" multiple="multiple" style="width: 100%;">
                                    <option value="" selected hidden>Chọn bàn...</option>
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
                                    <th>Số lượng trên đơn gốc</th>
                                    <th>Số lượng tách</th>
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
                        <button id="xacNhanTach-btn" type="button" class="btn btn-primary btn-sm">Tách</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


{{-- giao diện lưu hóa đơn In --}}

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/print-js/1.6.0/print.min.js"></script>

<script>
    // Hàm lấy dữ liệu thanh toán
    $(document).ready(function() {
        $('#thanhToan-btn').click(function() {
            let maHoaDonElm = document.getElementById("maHoaDon");
            let maHoaDon = maHoaDonElm.innerText;
            let maHoaDonInFo = document.getElementById("maHoaDonInFo");
            maHoaDonInFo.innerText = maHoaDon

            if (maHoaDon === 'Chưa có hóa đơn') {
                return;
            }

            $.ajax({
                url: '/thu-ngan/hoa-don-info',
                type: 'GET',
                data: {
                    maHoaDon: maHoaDon
                },
                success: function(response) {
                    if (response.khachHang) {
                        // Nếu có khách hàng, cập nhật thông tin khách hàng vào dropdown
                        $('#customerSelect').html(`
                        <option value="${response.khachHang.id}" selected>
                            ${response.khachHang.ho_ten} - ${response.khachHang.so_dien_thoai}
                        </option>
                        <option value="new">Thêm mới khách</option>
                    `);
                    } else {
                        // Nếu không có khách hàng, hiển thị mặc định là "Khách lẻ"
                        $('#customerSelect').html(`
                        <option value="0" selected>Khách lẻ</option>
                        <option value="new">Thêm mới khách</option>
                    `);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Lỗi khi lấy thông tin hóa đơn:", xhr.responseText);
                }
            })
        });
    });


    function calculateChange() {
        let totalAmount = parseInt(document.getElementById("totalAmount").value.replace(/\D/g, "")) || 0;
        let amountGiven = parseInt(document.getElementById("amountGiven").value) || 0;
        let change = amountGiven - totalAmount;

        document.getElementById("changeToReturn").value = change > 0 ? change.toLocaleString() + " VND" : "0 VND";
    }

    $(document).ready(function() {
        $("#customerSelect").change(function() {
            if ($(this).val() === "new") {
                $("#addCustomerModal").modal("show");
            }
        });

        $("#saveCustomerBtn").click(function() {
            var name = $("#customerNameInput").val().trim();
            var email = $("#customerEmail").val().trim() || "Chưa cập nhật";
            var address = $("#customerAddress").val().trim() || "Chưa cập nhật";
            var phone = $("#customerPhone").val().trim() || "Chưa cập nhật";
            var isValid = true;

            // Xóa thông báo lỗi cũ
            $(".error-message").remove();
            $(".is-invalid").removeClass("is-invalid");

            // Kiểm tra họ tên (bắt buộc)
            if (!name) {
                isValid = false;
                $("#customerNameInput").addClass("is-invalid")
                    .after(
                        '<div class="error-message text-danger">Họ và tên không được để trống!</div>');
            }

            // Kiểm tra email hợp lệ (nếu không phải "Chưa cập nhật")
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (email !== "Chưa cập nhật" && !emailRegex.test(email)) {
                isValid = false;
                $("#customerEmail").addClass("is-invalid")
                    .after('<div class="error-message text-danger">Email không hợp lệ!</div>');
            }

            // Kiểm tra số điện thoại hợp lệ (nếu không phải "Chưa cập nhật")
            var phoneRegex = /^[0-9]{10,15}$/;
            if (phone !== "Chưa cập nhật" && !phoneRegex.test(phone)) {
                isValid = false;
                $("#customerPhone").addClass("is-invalid")
                    .after(
                        '<div class="error-message text-danger">Số điện thoại phải có từ 10 đến 15 chữ số!</div>'
                    );
            }

            if (!isValid) return; // Nếu có lỗi thì dừng lại

            $.ajax({
                url: "/add-customer",
                type: "POST",
                contentType: "application/json",
                data: JSON.stringify({
                    name: name,
                    email: email,
                    address: address,
                    phone: phone
                }),
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                success: function(response) {
                    if (response.success) {
                        $("#customerSelect option[value='new']").remove();
                        let newOption =
                            `<option value="${response.customer_id}" selected>${name}</option>`;
                        $("#customerSelect").append(newOption).val(response.customer_id);
                        $("#addCustomerModal").modal("hide");
                        $("#customerNameInput, #customerEmail, #customerAddress, #customerPhone")
                            .val("");
                    } else {
                        alert("Lỗi: " + response.message);
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseJSON);
                    alert("Có lỗi xảy ra: " + (xhr.responseJSON?.message ||
                        "Vui lòng thử lại!"));
                }
            });
        });
    });


    $('#btnThanhToan').on('click', function() {
        var banId = $('#ten-ban').data('currentBan');
        var soNguoi = $(".so-nguoi").data("soNguoi") || 1;
        var khachHangId = $("#customerSelect").val();
        var phuongThucThanhToan = $('#paymentMethod').val();
        var paymentDetails = $("#paymentDetails").val();
        var totalAmount = parseFloat($('#totalAmount').val().replace(/\./g, '').trim()) || 0;
        var amountGiven = parseFloat($('#amountGiven').val().replace(/\./g, '').trim()) || 0;
        var changeToReturn = parseFloat($('#changeToReturn').val().replace(/\./g, '').trim()) || 0;
        let maHoaDonInFo = document.getElementById("maHoaDonInFo");
        let maHoaDonFind = maHoaDonInFo.innerText;

        var danhSachSanPham = [];
        $("#hoa-don-thanh-toan-body tr").each(function() {
            var sanPham = {
                ten_san_pham: $(this).find("td:nth-child(2)").text()
                    .trim(), // Không loại bỏ dấu nữa
                so_luong: parseInt($(this).find("td:nth-child(3)").text().trim()) || 0,
                don_gia: parseFloat($(this).find("td:nth-child(4)").text().replace(/\./g, '')
                    .trim()) || 0,
                tong_cong: parseFloat($(this).find("td:nth-child(5)").text().replace(/\./g, '')
                    .trim()) || 0
            };
            danhSachSanPham.push(sanPham);
        });

        if (banId) {
            $.ajax({
                url: "/update-ban-status",
                method: "POST",
                data: {
                    ban_an_id: banId,
                    khach_hang_id: khachHangId,
                    so_nguoi: soNguoi,
                    phuong_thuc_thanh_toan: phuongThucThanhToan,
                    chi_tiet_thanh_toan: paymentDetails,
                    tong_tien: totalAmount,
                    ma_hoa_don_cua_ban: maHoaDonFind,
                    _token: $('meta[name="csrf-token"]').attr("content")
                },
                success: function(response) {
                    if (response.success) {
                        hoaDonId = response.hoaDon.id;
                        var maHoaDon = response.hoaDon.ma_hoa_don;
                        var tenKhachHang = response.khachHang.ho_ten;
                        var ngayBan = response.hoaDon.created_at ? new Date(response.hoaDon
                            .created_at).toLocaleString('vi-VN', {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        }) : new Date().toLocaleString('vi-VN', {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                        var soDienThoai = response.khachHang.so_dien_thoai || 'Chưa cập nhật';

                        // Tạo nội dung in, không dùng cấu trúc HTML đầy đủ
                        var printContent = `
                    <div class="container">
                        <h1 class="store-name">NHÀ HÀNG BẾP LỬA QUÊ</h1>
                        <h2 class="invoice-title">HÓA ĐƠN THANH TOÁN</h2>
                        <p><strong>Mã hóa đơn:</strong> ${maHoaDon}</p>
                        <p><strong>Ngày:</strong> ${ngayBan}</p>
                        <p><strong>Khách hàng:</strong> Bàn ${banId}</p>
                        <p><strong>Khách hàng:</strong> ${tenKhachHang}</p>
                        <p><strong>Số điện thoại:</strong> ${soDienThoai}</p>

                        <div class="divider">-----------------------------------------</div>

                        <div class="row header-row">
                            <div class="col-stt">STT</div>
                            <div class="col-mon-an">Món ăn</div>
                            <div class="col-sl">SL.</div>
                            <div class="col-gia">Giá</div>
                            <div class="col-tong">Tổng</div>
                        </div>

                        ${
                            danhSachSanPham && danhSachSanPham.length > 0 
                            ? danhSachSanPham.map((item, index) => `
                                <div class="row">
                                    <div class="col-stt">${index + 1}</div>
                                    <div class="col-mon-an">${item.ten_san_pham}</div>
                                    <div class="col-sl">${item.so_luong}</div>
                                    <div class="col-gia">${(item.tong_cong / item.so_luong).toLocaleString('vi-VN', { minimumFractionDigits: 0 })} VND</div>
                                    <div class="col-tong">${item.tong_cong.toLocaleString('vi-VN', { minimumFractionDigits: 0 })} VND</div>
                                </div>
                            `).join('')
                            : `<div class="row"><div class="col-full">Không có dữ liệu</div></div>`
                        }

                        <div class="divider">-----------------------------------------</div>

                        <p class="total"><strong>Tổng cộng:</strong> ${totalAmount.toLocaleString('vi-VN', { minimumFractionDigits: 0 })} VND</p>
                        <p class="amount-given"><strong>Tiền khách đưa:</strong> ${amountGiven.toLocaleString('vi-VN', { minimumFractionDigits: 0 })} VND</p>
                        <p class="change-return"><strong>Tiền thừa trả khách:</strong> ${changeToReturn.toLocaleString('vi-VN', { minimumFractionDigits: 0 })} VND</p>
                        <p class="thank-you">Cảm ơn quý khách! Hẹn gặp lại! 😊</p>
                    </div>
                `;

                        // Tạo phần tử tạm trong DOM để chứa nội dung in
                        var tempDiv = document.createElement('div');
                        tempDiv.innerHTML = printContent;

                        // Thêm phần tử vào body
                        document.body.appendChild(tempDiv);

                        // In nội dung bằng printJS
                        printJS({
                            printable: tempDiv,
                            type: 'html',
                            showModal: true,
                            modalTitle: 'Cài Đặt In',
                            style: `
                        body {
                            font-family: Arial, sans-serif;
                            font-size: 14px;
                            line-height: 1.5;
                        }
                        .container {
                            width: 100%;
                            max-width: 300px; /* Khổ giấy in nhỏ, thường 80mm */
                            margin: 0 auto;
                            padding: 10px;
                            text-align: left;
                        }
                        .store-name {
                            text-align: center;
                            font-size: 16px;
                            font-weight: bold;
                            margin-bottom: 5px;
                        }
                        .invoice-title {
                            text-align: center;
                            font-size: 14px;
                            font-weight: bold;
                            margin-bottom: 10px;
                        }
                        p {
                            margin: 5px 0;
                        }
                        .divider {
                            text-align: center;
                            margin: 10px 0;
                            font-size: 12px;
                        }
.row {
    display: flex;
    justify-content: flex-start;
    gap: 5px;
    margin-bottom: 0; /* Giảm khoảng cách dưới của mỗi hàng */
    line-height: 1; /* Giảm khoảng cách dọc giữa các dòng văn bản */
}
                        .header-row {
                            font-weight: bold;
                        }
                        .col-stt {
                            width: 8%; /* Giảm chiều rộng */
                            text-align: left;
                        }
                        .col-mon-an {
                            width: 35%; /* Tăng chiều rộng để chứa tên món dài */
                            text-align: left;
                            white-space: nowrap; /* Ngăn tên món xuống dòng */
                            overflow: hidden; /* Ẩn phần thừa */
                            text-overflow: ellipsis; /* Thêm dấu ... nếu tên quá dài */
                        }
                        .col-sl {
                            width: 10%; /* Giảm chiều rộng */
                            text-align: center;
                        }
                        .col-gia {
                            width: 22%; /* Giảm chiều rộng */
                            text-align: center;
                        }
                        .col-tong {
                            width: 20%; /* Giảm chiều rộng */
                            text-align: right;
                        }
                        .col-full {
                            width: 100%;
                            text-align: center;
                        }
                        .total, .amount-given, .change-return {
                            text-align: right;
                            font-weight: bold;
                            margin-top: 5px;
                        }
                        .thank-you {
                            text-align: center;
                            margin-top: 10px;
                            font-size: 12px;
                        }
                    `,
                            options: {
                                orientation: 'portrait',
                                color: true,
                                duplex: false,
                                margins: {
                                    top: 10,
                                    left: 10,
                                    right: 10,
                                    bottom: 10
                                }
                            }
                        });

                        // Xóa phần tử tạm
                        document.body.removeChild(tempDiv);

                        showToast("Đã thanh toán đơn hàng", "success");
                        var offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById(
                            "offcanvasRight"));
                        offcanvas.hide();
                        resetGiaoDienHoaDon();
                        var maHoaDonElement = document.getElementById("maHoaDon");
                        maHoaDonElement.innerText = "Chưa có hóa đơn";
                        maHoaDonElement.style.color = "red";
                    } else {
                        showToast("Thanh toán không thành công.", "danger");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Lỗi chi tiết:", xhr.responseText);
                    showToast("Lỗi khi cập nhật trạng thái bàn: " + xhr.responseText, "danger");
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
    }

    $(document).ready(function() {
        let banId = null
        let modal = new bootstrap.Modal(document.getElementById('modalSoNguoi'));
        $(".so-nguoi").click(function() {
            banId = $('#ten-ban').data('currentBan'); // Lấy ID bàn từ #ten-ban

            soNguoiEl = $(this).text().replace("👥", "").trim(); // Loại bỏ emoji và lấy số người

            // Cập nhật giá trị trong #soNguoiInput nếu cần
            $("#soNguoiInput").val(soNguoiEl); // Gán vào ô input

            if (!banId || banId === 0) {
                alert("Vui lòng chọn bàn trước khi nhập số người!");
            } else {
                modal.show(); // Chỉ mở modal khi bàn đã được chọn
            }
        });

        // Dùng jQuery để thêm sự kiện cho nút Lưu
        $('#btnLuuSoNguoi').click(function() {
            let soNguoi = $("#soNguoiInput").val();

            $.ajax({
                url: 'thu-ngan-save-so-nguoi',
                method: 'POST',
                data: {
                    banId: banId,
                    soNguoi: soNguoi,
                    _token: $('meta[name="csrf-token"]').attr("content")
                },

                success: function(response) {
                    modal.hide();
                    $('.so-nguoi').text(`👥 ${response.soNguoi}`);
                },

                error: function(xhr, status, error) {
                    console.error("Có lỗi xảy ra:", error);
                }
            })
        });
    });


    $(document).ready(function() {
        // Khởi tạo Select2 với multiple
        $('#chonBanGhep').select2({
            width: '100%',
            placeholder: "Chọn bàn cần ghép",
            allowClear: true,
            templateResult: formatOption, // Màu trong danh sách sổ xuống
            templateSelection: formatOption // Màu khi chọn bàn
        });

        function formatOption(option) {
            if (!option.id) return option.text; // Nếu là option trống thì giữ nguyên

            let color = $(option.element).data('color'); // Lấy màu từ data
            return $('<span style="color:' + color + '; font-weight: bold;">' + option.text + '</span>');
        }

        // Khi modal mở, load danh sách bàn
        let luuIdBan = null;

        $('#modalGhepBan').on('shown.bs.modal', function() {
            var idBanHienTai = $('#ten-ban').data('currentBan');
            luuIdBan = idBanHienTai;
            $.ajax({
                url: "{{ route('thungan.getBanDeGhep') }}",
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    let select = $('#chonBanGhep');
                    select.empty();
                    select.append('<option value="">-- Chọn bàn --</option>');

                    data.forEach(function(ban) {
                        if (ban.id != idBanHienTai) {
                            let trangThai = ban.trang_thai === "co_khach" ?
                                " (Đang sử dụng)" : " (Trống)";
                            let statusClass = ban.trang_thai === "co_khach" ?
                                "co-khach" :
                                "trong"; // Sử dụng trạng thái đúng trong database
                            select.append(
                                `<option value="${ban.id}" class="${statusClass}">${ban.ten_ban} ${trangThai}</option>`
                            );
                        }
                    });
                    select.trigger('change');
                },
                error: function() {
                    alert('Lỗi khi tải danh sách bàn!');
                }
            });
        });

        // Khi chọn bàn, lấy thông tin từng bàn
        $('#chonBanGhep').on('change', function() {
            let idBanList = $(this).val() || []; // Lấy danh sách ID bàn được chọn
            let $tbody = $('#thongTinBan tbody');

            $tbody.html(''); // Xóa dữ liệu cũ trước khi thêm mới
            $('#thongTinBan').hide(); // Ẩn trước khi load dữ liệu mới

            if (idBanList.length > 0) {
                idBanList.forEach(function(idBan) {
                    let apiUrlGetBill = "{{ route('thungan.getBillBan', ':id') }}".replace(
                        ':id', idBan);

                    $.ajax({
                        url: apiUrlGetBill,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            if (response.bill) {
                                let tongTien = parseFloat(response.bill
                                    .tong_tien) || 0; // Chuyển về số

                                // Tạo hàng mới
                                let newRow = `
                            <tr>
                                <td>Khách lẻ</td>
                                <td>${response.bill.ten_ban}</td>
                                <td>${response.bill.ma_hoa_don}</td>
                                <td>${response.bill.tong_so_luong_mon_an || '0'}</td>
                                <td>${response.bill.so_nguoi || '0'}</td>    
                                <td>${tongTien.toLocaleString()} VNĐ</td>
                            </tr>
                        `;
                                // Thêm hàng mới vào tbody
                                $tbody.append(newRow);

                                // Hiển thị bảng nếu có dữ liệu
                                $('#thongTinBan').show();
                            }
                        },
                        error: function() {
                            $('#thongTinBan').hide();
                        }
                    });
                });
            }
        });

        // Xác nhận ghép bàn
        $('#btnXacNhanGhepBan').click(function() {
            let idBanHienTai = luuIdBan;
            let idBanList = $('#chonBanGhep').val();
            if (idBanList.length === 0) {
                alert('Vui lòng chọn ít nhất một bàn để ghép!');
                return;
            }

            $.ajax({
                url: "{{ route('thungan.ghepBan') }}",
                type: 'POST',
                data: {
                    id_ban_hien_tai: idBanHienTai,
                    danh_sach_ban: JSON.stringify(idBanList),
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#modalGhepBan').modal('hide');
                    showToast(response.message, "success");
                },
                error: function(xhr) {
                    console.log(xhr.responseText);

                }
            });
        });
    });
</script>
