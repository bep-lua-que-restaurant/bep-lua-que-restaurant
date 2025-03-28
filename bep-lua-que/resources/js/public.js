import "./bootstrap";
window.Echo.channel("banan-channel").listen("BanAnUpdated", (data) => {
    let updatedBan = $("#ban-" + data.id);

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
            loadHoaDonThanhToan(hoaDonId);
            // console.log("Hóa đơn mới được thêm:", data.hoa_don);
        }
    })
    .listen("HoaDonUpdated", (data) => {
        if (data.type === "hoa_don_updated") {
            // console.log("Hóa đơn đã được cập nhật:", data.hoa_don);
            let hoaDonId = $("#ten-ban").data("hoaDonId");
            if (hoaDonId && hoaDonId == data.hoa_don.id) {
                loadChiTietHoaDon(hoaDonId);
                loadHoaDonThanhToan(hoaDonId);
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
                <tr id="mon-${item.id}">
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
    <!-- Nút giảm số lượng -->
    <i class="bi bi-dash-circle text-danger giam-soluong" style="cursor: pointer; font-size: 20px;" data-id="${
        item.id
    }"></i>
    <!-- Hiển thị số lượng -->
    <span class="so-luong mx-2 small">${item.so_luong}</span>
    <!-- Nút tăng số lượng -->
    <i class="bi bi-plus-circle text-success tang-soluong" style="cursor: pointer; font-size: 20px;" data-id="${
        item.id
    }"></i>
</td>

    <td class="text-end small">
        ${parseFloat(item.don_gia).toLocaleString("vi-VN", {
            style: "currency",
            currency: "VND",
        })}
    </td>

    <td class="text-end small">
        ${(item.so_luong * item.don_gia).toLocaleString("vi-VN", {
            style: "currency",
            currency: "VND",
        })}
    </td>
    <!-- Nút xóa với icon -->
    <td class="text-center">
        <button class="btn btn-sm btn-outline-danger xoa-mon" data-id="${
            item.id
        }">
            <i class="bi bi-trash"></i> <!-- Biểu tượng xóa -->
        </button>
    </td>
</tr>
`;
                    hoaDonBody.append(row);
                    offcanvasBody.append(row);
                    tongTien += item.so_luong * item.don_gia;
                    index++;
                });
            } else {
                let emptyRow =
                    '<tr><td colspan="4" class="text-center">Chưa có món nào</td></tr>';
                hoaDonBody.html(emptyRow);
                offcanvasBody.html(emptyRow);
            }

            $("#tong-tien").text(tongTien.toLocaleString() + " VNĐ");
            $(".so-nguoi").text(`👥 ${soNguoi}`);
            $("#totalAmount").val(tongTien.toLocaleString() + " VND"); // Cập nhật tổng tiền trong offcanvas

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
        },
        error: function (xhr) {
            console.error("🔥 Lỗi khi tải chi tiết hóa đơn:", xhr.responseText);
        },
    });
}

function loadHoaDonThanhToan(hoaDonId) {
    $.ajax({
        url: "/hoa-don/get-details",
        method: "GET",
        data: { hoa_don_id: hoaDonId },
        success: function (response) {
            let hoaDonThanhToan = $("#hoa-don-thanh-toan-body");
            let offcanvasBody = $(".offcanvas-body tbody"); // Lấy phần bảng trong offcanvas

            hoaDonThanhToan.empty();
            offcanvasBody.empty();

            var soNguoi = response.so_nguoi;
            let tongTien = 0;
            let rows = [];

            if (response.chi_tiet_hoa_don.length > 0) {
                let index = 1;
                response.chi_tiet_hoa_don.forEach((item) => {
                    let row = `
                        <tr id="mon-${item.id}">
                            <td class="small">${index}</td>
                            <td class="small">
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
                            <td class="text-start">
                                <span class="so-luong mx-2 small">${
                                    item.so_luong
                                }</span>
                            </td>
                            <td class="text-start small">
                                ${parseFloat(item.don_gia).toLocaleString(
                                    "vi-VN",
                                    { style: "currency", currency: "VND" }
                                )}
                            </td>
                            <td class="text-start small">
                                ${(item.so_luong * item.don_gia).toLocaleString(
                                    "vi-VN",
                                    { style: "currency", currency: "VND" }
                                )}
                            </td>
                        </tr>
                    `;
                    rows.push(row);
                    tongTien += item.so_luong * item.don_gia;
                    index++;
                });

                // Cập nhật bảng bằng cách dùng .html() thay vì .append()
                hoaDonThanhToan.html(rows.join(""));
                offcanvasBody.html(rows.join(""));
            } else {
                let emptyRow =
                    '<tr><td colspan="5" class="text-center">Chưa có món nào</td></tr>';
                hoaDonThanhToan.html(emptyRow);
                offcanvasBody.html(emptyRow);
            }

            if (response.ma_hoa_don) {
                $("#ma_hoa_don").text(response.ma_hoa_don);
            }

            $("#tong-tien").text(tongTien.toLocaleString() + " VNĐ");
            $(".so-nguoi").text(`👥 ${soNguoi}`);
            $("#totalAmount").val(tongTien.toLocaleString() + " VND");

            if (response.ten_ban) {
                $("#tableInfo").text(`Bàn ${response.ten_ban}`);
            }
        },
        error: function (xhr) {
            console.error("🔥 Lỗi khi tải chi tiết hóa đơn:", xhr.responseText);
        },
    });
}

// Hàm cập nhật số lượng món ăn
function updateSoLuong(monAnId, thayDoi) {
    $.ajax({
        url: "/hoa-don/update-quantity",
        method: "POST",
        data: {
            mon_an_id: monAnId,
            thay_doi: thayDoi,
            _token: $('meta[name="csrf-token"]').attr("content"), // Nếu dùng Laravel
        },
        success: function (response) {
            loadChiTietHoaDon(response.hoa_don_id); // Load lại chi tiết hóa đơn sau khi cập nhật
            loadHoaDonThanhToan(response.hoa_don_id);
        },
        error: function (xhr) {
            console.error("❌ Lỗi khi cập nhật số lượng:", xhr.responseText);
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
    if (isRequesting) return; // Nếu đang gửi yêu cầu, không gửi lại

    isRequesting = true;
    $.ajax({
        url: apiUrlXoaMon, // Đường dẫn đến action xử lý xóa trong controller của bạn
        method: "POST",
        data: {
            mon_an_id: monAnId,
            _token: $('meta[name="csrf-token"]').attr("content"), // CSRF token nếu dùng Laravel
        },
        success: function (response) {
            isRequesting = false;
            // Xóa món ăn khỏi bảng
            $(`#mon-${monAnId}`).remove(); // Loại bỏ dòng có ID tương ứng
            $("#tong-tien").text(
                response.tong_tien.toLocaleString("vi-VN", {
                    style: "currency",
                    currency: "VND",
                })
            );
        },
    });
}

window.Echo.channel("bep-channel").listen(".trang-thai-cap-nhat", (e) => {
    // Tìm phần tử <span> trong hàng <tr> chứa món ăn
    let monElement = document.querySelector(`#mon-${e.monAn.id} span`);

    if (monElement) {
        // Xóa màu cũ
        monElement.classList.remove(
            "text-danger",
            "text-warning",
            "text-success"
        );

        // Thêm màu mới theo trạng thái
        if (e.monAn.trang_thai === "cho_che_bien") {
            monElement.classList.add("text-danger");
        } else if (e.monAn.trang_thai === "dang_nau") {
            monElement.classList.add("text-warning");
        } else if (e.monAn.trang_thai === "hoan_thanh") {
            monElement.classList.add("text-success");
        }
    }
});
