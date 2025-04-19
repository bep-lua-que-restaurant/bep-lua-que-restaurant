import "./bootstrap";
window.Echo.channel("banan-channel").listen("BanAnUpdated", (data) => {
    let updatedBan = $("#ban-" + data.id);
    // console.log("Bàn ăn đã được cập nhật:", data);
    if (updatedBan.length) {
        // Nếu bàn ăn đã có trên giao diện, cập nhật thông tin
        updatedBan.find(".card-title").text(data.ten_ban);

        // Xử lý class trạng thái bàn
        let badge = updatedBan.find(".badge");
        badge.removeClass(
            "badge-success badge-danger badge-warning badge-primary badge-secondary"
        );

        if (data.trang_thai === "trong") {
            badge.addClass("badge-success").text("Có sẵn");
        } else if (data.trang_thai === "co_khach") {
            badge.addClass("badge-warning").text("Có khách");
        } else if (data.trang_thai === "da_dat_truoc") {
            badge.addClass("badge-success").text("Có sẵn");
        } else {
            badge.addClass("badge-secondary").text("Không xác định");
        }
    } else {
        // Nếu bàn ăn chưa có trên UI, chỉ gọi fetchUpdatedList() một lần
        fetchUpdatedList();
    }

    if (data.deleted) {
        // Nếu bàn ăn bị xóa mềm, xóa khỏi giao diện
        updatedBan.fadeOut(100, function () {
            $(this).remove();
        });
    }
});

function fetchUpdatedList() {
    $.ajax({
        url: apiUrl,
        method: "GET",
        success: function (response) {
            $("#list-container").html(response.html);
            reinitializeSwiper();
        },
        error: function (xhr) {
            console.error("Lỗi khi tải dữ liệu:", xhr);
        },
    });
}

// thực đơn

window.Echo.channel("thucdon-channel").listen("ThucDonUpdated", (data) => {
    let updatedItem = $("#mon-" + data.id);

    if (updatedItem.length) {
        // Nếu món ăn đã có trên giao diện, cập nhật thông tin
        updatedItem.find(".card-title").text(data.ten_mon);
        updatedItem.find(".gia").text(data.gia + " đ");

        // Cập nhật hình ảnh món ăn
        updatedItem.find("img").attr("src", data.hinh_anh);

        // Kiểm tra trạng thái món ăn
        let badge = updatedItem.find(".badge");
        badge.removeClass("badge-success badge-danger");

        if (data.trang_thai === "con_hang") {
            badge.addClass("badge-success").text("Còn hàng");
        } else {
            badge.addClass("badge-danger").text("Hết hàng");
        }
    } else {
        // Nếu món ăn chưa có trên UI, gọi AJAX để tải lại danh sách thực đơn
        fetchUpdatedMenu();
    }

    if (data.deleted) {
        // Nếu món ăn bị xóa, loại bỏ khỏi giao diện
        updatedItem.fadeOut(100, function () {
            $(this).remove();
        });
    } else {
        fetchUpdatedMenu();
    }
});

function fetchUpdatedMenu() {
    $.ajax({
        url: apiUrlThucDon, // Đặt URL API của bạn ở đây
        method: "GET",
        success: function (response) {
            $("#list-container").html(response.html);
        },
        error: function (xhr) {
            console.error("Lỗi khi tải thực đơn:", xhr);
        },
    });
}

window.Echo.channel("hoa-don-channel")
    .listen("HoaDonAdded", (data) => {
        if (data.type === "hoa_don_added") {
            let hoaDonId = data.hoa_don.id;
            loadChiTietHoaDon(hoaDonId);
            // loadHoaDonThanhToan(hoaDonId);
            // console.log("Hóa đơn mới được thêm:", data.hoa_don);
        }
    })
    .listen("HoaDonUpdated", (data) => {
        if (data.type === "hoa_don_updated") {
            let hoaDonId = $("#ten-ban").data("hoaDonId");
            if (hoaDonId && hoaDonId == data.hoa_don.id) {
                loadChiTietHoaDon(hoaDonId);

                // loadHoaDonThanhToan(hoaDonId);
            }
        }
    });

function loadChiTietHoaDon(hoaDonId) {
    $.ajax({
        url: "/hoa-don/get-details",
        method: "GET",
        data: {
            hoa_don_id: hoaDonId,
        },
        success: function (response) {
            let hoaDonBody = $("#hoa-don-body");
            hoaDonBody.empty();
            let offcanvasBody = $(".offcanvas-body tbody"); // Lấy phần bảng trong offcanvas
            offcanvasBody.empty(); // Xóa nội dung cũ
            var soNguoi = response.so_nguoi;

            let tongTien = 0;
            if (response.chi_tiet_hoa_don.length > 0) {
                let index = 1;
                response.chi_tiet_hoa_don.forEach((item) => {
                    let row = `
                <tr data-id-mon="${item.mon_an_id}"  id="mon-${item.id}">
    <td class="small">${index}</td>
    <td class="small">
        <!-- Thêm điều kiện để thay đổi màu tên món tùy theo trạng thái -->
        <span class="${
            item.trang_thai === "cho_che_bien"
                ? "text-danger"
                : item.trang_thai === "dang_nau"
                ? "text-warning"
                : item.trang_thai === "hoan_thanh"
                ? "text-success"
                : ""
        }">
            ${item.tenMon}
        </span>
    </td>
<td class="text-center">
                                ${item.trang_thai === "cho_xac_nhan" ? `
                                    <i class="bi bi-dash-circle text-danger giam-soluong" style="cursor: pointer; font-size: 20px;" data-id="${item.id}"></i>
                                    <span class="so-luong mx-2 small">${item.so_luong}</span>
                                    <i class="bi bi-plus-circle text-success tang-soluong" style="cursor: pointer; font-size: 20px;" data-id="${item.id}"></i>
                                ` : `
                                    <span class="so-luong mx-2 small">${item.so_luong}</span>
                                `}
                            </td>

<td class="text-end small don-gia">
    ${parseFloat(item.don_gia).toLocaleString("vi-VN")} VNĐ
</td>

<td class="text-end small thanh-tien">
    ${(item.so_luong * item.don_gia).toLocaleString("vi-VN")} VNĐ
</td>
        ${
            item.trang_thai === "cho_xac_nhan"
                ? `<td class="text-center">
                    <button class="btn btn-sm btn-outline-danger xoa-mon" data-id="${item.id}">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>`
                : `<td class="text-center"></td>`
        }
</tr>
`;
                    hoaDonBody.append(row);
                    offcanvasBody.append(row);
                    tongTien += item.so_luong * item.don_gia;
                    index++;
                });
            } else {
                let emptyRow = `
                        <tr>
    <td colspan="5" class="text-center">
        <div class="empty-invoice w-100 p-5 border border-2 rounded bg-light">
            <i class="bi bi-receipt text-muted" style="font-size: 50px;"></i>
            <div class="mt-2">Chưa có món nào trong đơn</div>
            <div>🍔 Mời bạn chọn món!</div>
        </div>
    </td>
</tr>
`;
                hoaDonBody.html(emptyRow);
                offcanvasBody.html(emptyRow);
            }

            // Đảm bảo định dạng lại số tiền đúng cách
            $("#tong-tien").text(
                parseFloat(tongTien).toLocaleString("vi-VN") + " VNĐ"
            );
            $(".so-nguoi").text(`👥 ${soNguoi}`);
            $("#totalAmount").val(
                parseFloat(tongTien).toLocaleString("vi-VN") + " VNĐ"
            );

            if (response.da_ghep == true) {
                $("#ten-ban").text(response.ten_ban_an.join(" + "));
            }

            // Thêm sự kiện cho nút tăng giảm số lượng
            $(".tang-soluong").click(function () {
                let monAnId = $(this).data("id");
                updateSoLuong(monAnId, 1);
            });

            $(".giam-soluong").click(function () {
                let monAnId = $(this).data("id");
                updateSoLuong(monAnId, -1);
            });

            // Hàm cập nhật số lượng món ăn
            function updateSoLuong(monAnId, thayDoi) {
                let dongChuaNo = $("i[data-id='" + monAnId + "']").closest(
                    "tr"
                ); // Tìm dòng chứa món ăn
                let soLuongSpan = dongChuaNo.find(".so-luong").first(); // Tìm thẻ <span> số lượng
                let soLuongHienTai = parseInt(soLuongSpan.text().trim()) || 0;
                // Tính toán số lượng mới
                let soLuongMoi = soLuongHienTai + thayDoi;
                if (soLuongMoi < 1) soLuongMoi = 1; // Đảm bảo số lượng không nhỏ hơn 1
                // Cập nhật số lượng mới trong <span>
                soLuongSpan.text(soLuongMoi);
                let thanhTien = dongChuaNo.find(".thanh-tien").first();
                $.ajax({
                    url: "/hoa-don/update-quantity",
                    method: "POST",
                    data: {
                        mon_an_id: monAnId,
                        thay_doi: thayDoi,
                        _token: $('meta[name="csrf-token"]').attr("content"), // Nếu dùng Laravel
                    },
                    success: function (response) {
                        // Cập nhật tổng tiền

                        let formattedThanhTien =
                            Number(response.thanh_tien).toLocaleString(
                                "vi-VN"
                            ) + " VNĐ";

                        thanhTien.text(formattedThanhTien);

                        let tongTien = 0;
                        $("#hoa-don-body tr").each(function () {
                            let tongTienMon = $(this)
                                .find("td.text-end:last")
                                .text()
                                .replace(/[^0-9]/g, "");
                            tongTien += parseInt(tongTienMon);
                        });

                        // Đảm bảo định dạng lại số tiền đúng cách
                        $("#tong-tien").text(
                            parseFloat(tongTien).toLocaleString("vi-VN") +
                                " VNĐ"
                        );
                    },
                    error: function (xhr) {
                        console.error(
                            "❌ Lỗi khi cập nhật số lượng:",
                            xhr.responseText
                        );
                    },
                });
            }
        },
        error: function (xhr) {
            console.error("🔥 Lỗi khi tải chi tiết hóa đơn:", xhr.responseText);
        },
    });
}

let isRequesting = false;

// Gắn sự kiện xóa vào các nút xóa món ăn
$(document).ready(function () {
    $(document).on("click", ".xoa-mon", function () {
        const monAnId = $(this).data("id"); // Lấy ID món ăn từ thuộc tính data-id
        deleteMonAn(monAnId); // Gọi hàm xóa món ăn
    });
});

function deleteMonAn(monAnId) {
    if (isRequesting) return;

    isRequesting = true;
    $.ajax({
        url: apiUrlXoaMon,
        method: "POST",
        data: {
            mon_an_id: monAnId,
            check_status_only: true,
            _token: $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            isRequesting = false;

            if (!response.success) {
                Swal.fire(
                    "Lỗi!",
                    response.error || "Không thể lấy trạng thái món ăn.",
                    "error"
                );
                return;
            }

            const trangThai = response.trang_thai;
            const message = response.message;

            if (trangThai === "cho_xac_nhan") {
                Swal.fire({
                    title: "Bạn có chắc chắn?",
                    text: "Bạn muốn xóa món này?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: "Xóa",
                    cancelButtonText: "Thoát",
                }).then((result) => {
                    if (result.isConfirmed) {
                        sendDeleteRequest(monAnId, null, true);
                    }
                });
            } else {
                Swal.fire({
                    title: "Xác nhận hủy món",
                    text: message,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Hủy món",
                    cancelButtonText: "Thoát",
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: "Lý do hủy món:",
                            input: "text",
                            inputLabel: "Ghi chú lý do hủy món",
                            inputPlaceholder: "VD: Khách đổi ý...",
                            inputValidator: (value) => {
                                if (!value) return "Vui lòng nhập lý do!";
                            },
                            showCancelButton: true,
                            confirmButtonText: "Xác nhận",
                            cancelButtonText: "Thoát",
                        }).then((inputResult) => {
                            if (inputResult.isConfirmed) {
                                sendDeleteRequest(
                                    monAnId,
                                    inputResult.value,
                                    true
                                );
                            }
                        });
                    }
                });
            }
        },
        error: function (xhr) {
            isRequesting = false;
            console.error("Lỗi khi lấy trạng thái món:", xhr);
            Swal.fire("Lỗi!", "Không thể lấy trạng thái món ăn.", "error");
        },
    });
}

function sendDeleteRequest(monAnId, lyDo, forceDelete = false) {
    isRequesting = true;
    $.ajax({
        url: apiUrlXoaMon,
        method: "POST",
        data: {
            mon_an_id: monAnId,
            ly_do: lyDo,
            force_delete: forceDelete,
            _token: $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            isRequesting = false;

            if (response.requires_confirmation) {
                return;
            }

            $(`#mon-${monAnId}`).remove();

            // Đảm bảo định dạng lại số tiền đúng cách
            $("#tong-tien").text(
                parseFloat(response.tong_tien).toLocaleString("vi-VN") + " VNĐ"
            );

            Swal.fire({
                title: "Xóa món thành công!",
                icon: "success",
                confirmButtonText: "Đóng",
            });
        },
        error: function (xhr, status, error) {
            isRequesting = false;
            console.error("Lỗi AJAX:");
            console.error("Status:", status);
            console.error("Error:", error);
            console.error("Response:", xhr.responseText);

            Swal.fire("Lỗi!", "Không thể xử lý món ăn.", "error");
        },
    });
}

toastr.options = {
    closeButton: true,
    debug: false,
    newestOnTop: true, // Toast mới nhất hiển thị ở trên
    progressBar: true,
    positionClass: "toast-top-right", // Vị trí: góc trên bên phải
    preventDuplicates: false,
    onclick: null,
    showDuration: "300",
    hideDuration: "1000",
    timeOut: "5000", // Toast tự ẩn sau 5 giây
    extendedTimeOut: "1000",
    showEasing: "swing",
    hideEasing: "linear",
    showMethod: "fadeIn",
    hideMethod: "fadeOut",
};

function showToast(message, type) {
    switch (type) {
        case "success":
            toastr.success(message);
            break;
        case "danger":
            toastr.error(message);
            break;
        case "warning":
            toastr.warning(message);
            break;
        default:
            toastr.info(message);
            break;
    }
}


