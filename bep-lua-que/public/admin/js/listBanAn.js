function setActive(element) {
    // Xóa class 'active' khỏi tất cả các bàn
    document
        .querySelectorAll(".ban")
        .forEach((el) => el.classList.remove("active"));

    // Thêm class 'active' vào bàn được chọn
    element.classList.add("active");
}
var swiper = new Swiper(".mySwiper", {
    slidesPerView: 1, // Mỗi lần hiển thị 1 nhóm 12 sản phẩm
    spaceBetween: 20, // Khoảng cách giữa các nhóm
    allowTouchMove: true, // Không cho trượt bằng tay, chỉ dùng nút
});
// Xử lý sự kiện nút bấm
document.getElementById("nextBtn").addEventListener("click", function () {
    swiper.slideNext();
});

document.getElementById("prevBtn").addEventListener("click", function () {
    swiper.slidePrev();
});

$(document).ready(function () {
    $(".ban").on("click", function () {
        var banId = $(this).data("id"); // Lấy ID bàn
        var tenBan = $(this).find(".card-title").text(); // Lấy tên bàn
        // Lưu ID bàn vào dataset để sử dụng khi thêm món
        $("#ten-ban").data("currentBan", banId);
        $("#ten-ban").text(tenBan);
        $("#tableInfo").text(tenBan);
        // Gọi AJAX để lấy hóa đơn ID của bàn này
        $.ajax({
            url: "/hoa-don/get-id",
            method: "GET",
            data: {
                ban_an_id: banId,
            },
            success: function (response) {
                if (response.hoa_don_id) {
                    // console.log("🔥 Hóa đơn ID:", response.hoa_don_id);
                    $("#ten-ban").data("hoaDonId", response.hoa_don_id);
                    // Gọi API để lấy chi tiết hóa đơn
                    loadChiTietHoaDon(response.hoa_don_id);
                    loadHoaDonThanhToan(response.hoa_don_id);
                } else {
                    // console.log("🔥 Bàn này chưa có hóa đơn.");
                    $("#ten-ban").data("hoaDonId", null);
                    $("#hoa-don-body").html(`
                        <tr>
    <td colspan="5" class="text-center">
        <div class="empty-invoice w-100 p-5 border border-2 rounded bg-light">
            <i class="bi bi-receipt text-muted" style="font-size: 50px;"></i>
            <div class="mt-2">Chưa có món nào trong đơn</div>
            <div>🍔 Mời bạn chọn món!</div>
        </div>
    </td>
</tr>
`);

                    $("#tong-tien").text("0 VNĐ");
                    $(".so-nguoi").text("👥 0");
                }
            },
            error: function (xhr) {
                console.error("🔥 Lỗi khi lấy hóa đơn ID:", xhr.responseText);
            },
        });
    });

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
                loadChiTietHoaDon(response.hoa_don_id);
            },
            error: function (xhr) {
                console.error(
                    "❌ Lỗi khi cập nhật số lượng:",
                    xhr.responseText
                );
            },
        });
    }

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
    <button class="btn btn-sm btn-outline-danger xoa-mon" data-id="${item.id}">
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

                if (response.da_ghep == true) {
                    $("#ten-ban").text(response.ten_ban_an.join(" + "));
                }

                if (response.ma_hoa_don) {
                    $("#ma_hoa_don").text(response.ma_hoa_don);
                }

                $("#tong-tien").text(tongTien.toLocaleString() + " VNĐ");
                $(".so-nguoi").text(`👥 ${soNguoi}`);
                $("#totalAmount").val(tongTien.toLocaleString() + " VND"); // Cập nhật tổng tiền trong offcanvas

                if (response.ten_ban) {
                    $("#tableInfo").text(`Bàn ${response.ten_ban}`);
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
                console.error(
                    "🔥 Lỗi khi tải chi tiết hóa đơn:",
                    xhr.responseText
                );
            },
        });
    }

    function loadHoaDonThanhToan(hoaDonId) {
        $.ajax({
            url: "/hoa-don/get-details",
            method: "GET",
            data: {
                hoa_don_id: hoaDonId,
            },
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
                            ${parseFloat(item.don_gia).toLocaleString("vi-VN", {
                                style: "currency",
                                currency: "VND",
                            })}
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
            },
            error: function (xhr) {
                console.error(
                    "🔥 Lỗi khi tải chi tiết hóa đơn:",
                    xhr.responseText
                );
            },
        });
    }
});

function showOrders(element) {
    let banAnId = element.getAttribute("data-id"); // Lấy ID bàn ăn
    let ordersList = document.getElementById("ordersList");
    let tenBanAn = document.getElementById("ten-ban-an");
    // Xóa danh sách cũ trước khi tải mới
    ordersList.innerHTML = "<p>Đang tải...</p>";
    // Gửi AJAX để lấy danh sách đơn đặt trước
    $.ajax({
        url: "/thu-ngan/get-orders", // Đường dẫn API lấy danh sách đơn
        method: "GET",
        data: { ban_an_id: banAnId },
        success: function (response) {
            ordersList.innerHTML = ""; // Xóa dòng "Đang tải..."
            if (response.length > 0) {
                response.forEach((order, index) => {
                    let row = `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${order.ho_ten}</td>
                            <td>${order.thoi_gian_den}</td>
                            <td><span class="badge bg-warning">Đặt trước</span></td>
                        </tr>`;
                    ordersList.innerHTML += row;
                    tenBanAn.innerText = order.ten_ban;
                });
            } else {
                ordersList.innerHTML = `<tr><td colspan="4">Không có đơn đặt trước nào!</td></tr>`;
            }
        },
        error: function () {
            ordersList.innerHTML = `<tr><td colspan="4">Lỗi khi tải danh sách đơn!</td></tr>`;
        },
    });

    // Hiển thị modal
    var modal = new bootstrap.Modal(document.getElementById("ordersModal"));
    modal.show();
}
