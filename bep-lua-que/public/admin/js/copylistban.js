function setActive(element) {
    // Xóa class 'active' khỏi tất cả các bàn
    document
        .querySelectorAll(".ban")
        .forEach((el) => el.classList.remove("active"));

    // Thêm class 'active' vào bàn được chọn
    element.classList.add("active");
}
var swiper = new Swiper(".mySwiper", {
    slidesPerView: 1, // Mỗi lần hiển thị 1 nhóm sản phẩm
    spaceBetween: 20, // Khoảng cách giữa các nhóm
    allowTouchMove: true, // Cho phép kéo bằng chuột/tay
    grabCursor: true,
});

// Hàm cập nhật số trang
function updatePageIndicator() {
    var currentPage = swiper.activeIndex + 1; // Trang hiện tại (bắt đầu từ 1)
    var totalPages = swiper.slides.length; // Tổng số trang
    document.getElementById("pageIndicator").textContent =
        currentPage + " / " + totalPages;
}

// Lắng nghe sự kiện khi đổi trang bằng nút bấm
document.getElementById("nextBtn").addEventListener("click", function () {
    swiper.slideNext();
});

document.getElementById("prevBtn").addEventListener("click", function () {
    swiper.slidePrev();
});

// Lắng nghe sự kiện khi kéo/swipe bằng chuột hoặc tay
swiper.on("slideChange", function () {
    updatePageIndicator();
});

// Cập nhật số trang ban đầu
updatePageIndicator();

$(document).ready(function () {
    $(".ban").on("click", function () {
        var banId = $(this).data("id"); // Lấy ID bàn
        var tenBan = $(this).find(".card-title").text(); // Lấy tên bàn
        let nutHoaDon = document.querySelector(".nut-hoa-don");
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
                    $("#ten-ban").data("hoaDonId", response.hoa_don_id);
                    nutHoaDon.style.display = "block";
                    // Gọi API để lấy chi tiết hóa đơn
                    loadChiTietHoaDon(response.hoa_don_id);
                } else {
                    // var hoaDonId = null;
                    nutHoaDon.style.display = "none";
                    var maHoaDonElement = document.getElementById("maHoaDon");
                    // loadChiTietHoaDon(hoaDonId);
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
                    maHoaDonElement.innerText = "Chưa có hóa đơn";
                    maHoaDonElement.style.color = "red";

                    $("#tong-tien").text("0 VNĐ");
                    $(".so-nguoi").text("👥 0");
                }
            },
            error: function (xhr) {
                console.error("🔥 Lỗi khi lấy hóa đơn ID:", xhr.responseText);
            },
        });
    });

    function loadChiTietHoaDon(hoaDonId) {
        var maHoaDonElement = document.getElementById("maHoaDon");
        if (hoaDonId == null) {
            maHoaDonElement.innerText = "Chưa có hóa đơn";
            maHoaDonElement.style.color = "red";
            return;
        }
        $.ajax({
            url: "/hoa-don/get-details",
            method: "GET",
            data: {
                hoa_don_id: hoaDonId,
            },
            success: function (response) {
                maHoaDonElement.innerText = response.maHoaDon;
                maHoaDonElement.style.color = "#28a745";
                let hoaDonBody = $("#hoa-don-body");
                hoaDonBody.empty();
                let offcanvasBody = $(".offcanvas-body tbody"); // Lấy phần bảng trong offcanvas
                offcanvasBody.empty(); // Xóa nội dung cũ
                var soNguoi = response.so_nguoi || 0;
                let tongTien = 0;
                if (response.chi_tiet_hoa_don.length > 0) {
                    let index = 1;
                    response.chi_tiet_hoa_don.forEach((item) => {
                        let row = `
                        <tr data-id-mon="${item.mon_an_id}" id="mon-${item.id}">
<td class="small">${index}</td>
<td class="small">
${
    item.trang_thai === "cho_xac_nhan"
        ? `<i class="bi bi-pencil-square text-primary toggle-ghi-chu" style="cursor: pointer;" data-id="${item.id}"></i>`
        : ""
}


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

        <!-- Ô nhập ghi chú, ẩn ban đầu -->
<div class="ghi-chu-wrapper mt-1" style="display: none;">
    <div class="d-flex align-items-center gap-2">
        <!-- Ô nhập ghi chú -->
        <input type="text" class="form-control form-control-sm ghi-chu-input"
               placeholder="Nhập ghi chú..." 
               value="${item.ghi_chu ?? ""}" 
               data-id="${item.id}" style="flex: 1;">
        
        <!-- Nút lưu (biểu tượng V) -->
        <i class="bi bi-check-circle-fill text-success save-ghi-chu" style="cursor: pointer; font-size: 20px;" data-id="${
            item.id
        }"></i>
    </div>
</div>

</td>
<td class="text-center">
                                ${
                                    item.trang_thai === "cho_xac_nhan"
                                        ? `
                                    <i class="bi bi-dash-circle text-danger giam-soluong" style="cursor: pointer; font-size: 20px;" data-id="${item.id}"></i>
                                    <span class="so-luong mx-2 small">${item.so_luong}</span>
                                    <i class="bi bi-plus-circle text-success tang-soluong" style="cursor: pointer; font-size: 20px;" data-id="${item.id}"></i>
                                `
                                        : `
                                    <span class="so-luong mx-2 small">${item.so_luong}</span>
                                `
                                }
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

                if (response.da_ghep == true) {
                    $("#ten-ban").text(response.ten_ban_an.join(" + "));
                }

                $("#tong-tien").text(tongTien.toLocaleString("vi-VN") + " VNĐ");
                $(".so-nguoi").text(`👥 ${soNguoi}`);

                $("#totalAmount").val(
                    tongTien.toLocaleString("vi-VN") + " VNĐ"
                ); // Cập nhật tổng tiền trong offcanvas

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

                // Hàm cập nhật số lượng món ăn
                function updateSoLuong(monAnId, thayDoi) {
                    let dongChuaNo = $("i[data-id='" + monAnId + "']").closest(
                        "tr"
                    ); // Tìm dòng chứa món ăn
                    let soLuongSpan = dongChuaNo.find(".so-luong").first(); // Tìm thẻ <span> số lượng
                    let soLuongHienTai =
                        parseInt(soLuongSpan.text().trim()) || 0;
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
                            _token: $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
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

                            $("#tong-tien").text(
                                tongTien.toLocaleString("vi-VN") + " VNĐ"
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
                console.error(
                    "🔥 Lỗi khi tải chi tiết hóa đơn:",
                    xhr.responseText
                );
            },
        });
    }

    let nutThanhToan = document.querySelector("#thanhToan-btn");
    nutThanhToan.onclick = function () {
        let maHoaDonElement = document.getElementById("maHoaDon");
        let maHoaDon = maHoaDonElement.textContent;
        loadHoaDonThanhToan(maHoaDon);
    };
    window.mon_an_cho_xac_nhan = [];

    function loadHoaDonThanhToan(maHoaDon) {
        $.ajax({
            url: "thu-ngan/hoa-don-thanh-toan",
            method: "GET",
            data: {
                maHoaDon: maHoaDon,
            },
            success: function (response) {
                console.log("khách cần trả" + response.tong_tien_sau_giam);
                let ma_hoa_don = response.data;
                let divMaGiamGia = document.querySelector(".wrap-ma-giam-gia");
                let maGiamGia = response.ma_giam_gia; // chứa thông tin mã giảm
                if (maGiamGia.length === 0) {
                    divMaGiamGia.style.display = "none";
                }

                renderDiscountCodes(maGiamGia, ma_hoa_don);
                if (
                    response.chi_tiet_hoa_don == null ||
                    response.chi_tiet_hoa_don.length == 0
                ) {
                    showToast(
                        "Hóa đơn này chưa được thông báo cho bếp, hãy thông báo cho bếp trước!",
                        "warning"
                    );
                    return;
                }

                let offcanvas = new bootstrap.Offcanvas(
                    document.getElementById("offcanvasRight")
                );
                offcanvas.show();
                window.mon_an_cho_xac_nhan = response.mon_an_cho_xac_nhan;
                let hoaDonThanhToan = $("#hoa-don-thanh-toan-body");

                hoaDonThanhToan.empty();

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
                ${parseFloat(item.don_gia).toLocaleString("vi-VN")} VNĐ
                        </td>
<td class="text-start small">
    ${(item.so_luong * item.don_gia).toLocaleString("vi-VN")} VNĐ
</td>
                    </tr>
                `;
                        rows.push(row);
                        tongTien += item.so_luong * item.don_gia;
                        index++;
                    });

                    // Cập nhật bảng bằng cách dùng .html() thay vì .append()
                    hoaDonThanhToan.html(rows.join(""));
                } else {
                    let emptyRow =
                        '<tr><td colspan="5" class="text-center">Chưa có món nào</td></tr>';
                    hoaDonThanhToan.html(emptyRow);
                }

                $("#tong_tien_hang").val(
                    tongTien.toLocaleString("vi-VN") + " VNĐ"
                );

                let khach_can_tra = parseFloat(response.tong_tien_sau_giam);

                $("#khach_can_tra").val(
                    khach_can_tra.toLocaleString("vi-VN") + " VNĐ"
                );
            },
            error: function (xhr) {
                console.error(
                    "🔥 Lỗi khi tải chi tiết hóa đơn:",
                    xhr.responseText
                );
            },
        });
    }

    // Render mã giảm giá
    function renderDiscountCodes(discounts, ma_hoa_don) {
        let discountListHtml = "";

        discounts.forEach((discount) => {
            const isApplied = discount.is_applied;
            const buttonClass = isApplied
                ? "btn-success"
                : "btn-outline-primary";
            const buttonText = isApplied
                ? '<i class="bi bi-check-circle me-1"></i><span style="font-size: 0.8rem;">Đã áp dụng</span>'
                : '<i class="bi bi-ticket-perforated me-1"></i><span style="font-size: 0.8rem;">Áp dụng</span>';
            const isDisabled = isApplied ? "disabled" : "";

            // Định dạng giá trị giảm dựa trên type
            let discountText = "";
            if (discount.type === "percentage") {
                discountText = `Giảm ${Math.round(discount.value)}%`;
            } else if (discount.type === "fixed") {
                discountText = `Giảm ${parseFloat(
                    discount.value
                ).toLocaleString("vi-VN")} VND`;
            }

            discountListHtml += `
            <li class="list-group-item d-flex justify-content-between align-items-center ${
                isApplied ? "applied" : ""
            }">
                <div>
                    <span class="fw-bold text-primary">${discount.code}</span>
                    <p class="mb-0 text-muted" style="font-size: 0.85rem;">
                        ${discountText} cho đơn từ ${
                parseFloat(discount.min_order_value).toLocaleString("vi-VN") +
                " VNĐ"
            }
                    </p>
                </div>
                <button class="btn ${buttonClass} btn-sm apply-discount" 
                        data-ma-hoa-don="${ma_hoa_don}" 
                        data-id="${discount.id}" 
                        ${isDisabled}>
                    ${buttonText}
                </button>
            </li>
        `;
        });

        const discountList = document.querySelector(
            ".discount-list .list-group"
        );
        if (discountList) {
            discountList.innerHTML =
                discountListHtml ||
                '<li class="list-group-item text-center">Không có mã giảm giá khả dụng</li>';
        } else {
            console.error("Không tìm thấy phần tử .discount-list .list-group");
        }
    }

    // Áp mã giảm giá
    $(document).on("click", ".apply-discount", function () {
        const $btn = $(this);
        const idCode = $(this).data("id");
        const maHoaDon = $(this).data("ma-hoa-don");

        $.ajax({
            url: "thu-ngan/apply-discount",
            type: "POST",
            data: {
                code: idCode,
                ma_hoa_don: maHoaDon,
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.success) {
                    showToast("Đã áp dụng mã giảm giá!", "success");

                    // Reset lại tất cả các nút về mặc định
                    $(".apply-discount")
                        .removeClass("btn-success")
                        .addClass("btn-outline-primary")
                        .html(
                            '<i class="bi bi-ticket-perforated me-1"></i><span style="font-size: 0.8rem;">Áp dụng</span>'
                        )
                        .prop("disabled", false);

                    // Cập nhật nút hiện tại
                    $btn.removeClass("btn-outline-primary")
                        .addClass("btn-success")
                        .html(
                            '<i class="bi bi-check-circle me-1"></i><span style="font-size: 0.8rem;">Đã áp dụng</span>'
                        )
                        .prop("disabled", true);

                    // Optional: Thêm class cho li (nếu muốn hiệu ứng khác)
                    $(".discount-list .list-group-item").removeClass("applied");
                    $btn.closest("li").addClass("applied");

                    // Cập nhật tổng tiền sau giảm
                    $("#khach_can_tra").val(
                        response.tong_tien_sau_giam.toLocaleString("vi-VN") +
                            " VNĐ"
                    );

                    // Render lại danh sách mã giảm giá từ response
                    if (response.ma_giam_gia) {
                        renderDiscountCodes(response.ma_giam_gia, maHoaDon);
                    }

                    // Cập nhật mã đã áp dụng trong #applied-code
                    const appliedDiscount = response.ma_giam_gia.find(
                        (d) => d.is_applied
                    );
                    if (appliedDiscount) {
                        let discountText = "";
                        if (appliedDiscount.type === "percentage") {
                            discountText = `Giảm ${Math.round(
                                appliedDiscount.value
                            )}%`;
                        } else if (appliedDiscount.type === "fixed") {
                            discountText = `Giảm ${parseFloat(
                                appliedDiscount.value
                            ).toLocaleString("vi-VN")} VND`;
                        }
                        $("#applied-code-text").text(
                            `${appliedDiscount.code} - ${discountText}`
                        );
                        $("#applied-code").show();
                    } else {
                        $("#applied-code").hide();
                        $("#applied-code-text").text("");
                    }
                } else {
                    alert(response.message || "Không áp dụng được mã giảm.");
                }
            },
            error: function () {
                alert("Lỗi khi áp dụng mã.");
            },
        });
    });
});

window.Echo.channel("bep-channel").listen(".trang-thai-cap-nhat", (data) => {
    console.log(data);

    if (
        !data.monAn ||
        !data.monAn.id ||
        !data.monAn.trang_thai ||
        !data.monAn.mon_an_id
    ) {
        console.error("Dữ liệu sự kiện không đầy đủ:", data);
        return;
    }

    let monAnId = data.monAn.id;
    let trangThaiMoi = data.monAn.trang_thai;
    let monAn = data.monAn.mon_an_id;
    let soLuong = parseInt(data.monAn.so_luong) || 1;
    let donGia = parseFloat(data.monAn.don_gia) || 0; // Lấy don_gia từ server
    let ten_mon = data.monAn.mon_an?.ten || "Không xác định";
    let ten_ban =
        data.monAn.hoa_don?.hoa_don_ban?.ban_an?.ten_ban || "Không xác định";

    // Hàm định dạng số tiền VNĐ
    const formatVND = (amount) => {
        return amount.toFixed(0).replace(/\d(?=(\d{3})+$)/g, "$&.") + " VNĐ";
    };

    // Xác định lớp màu
    let targetColorClass;
    if (trangThaiMoi === "cho_che_bien") {
        targetColorClass = "text-danger";
    } else if (trangThaiMoi === "dang_nau") {
        targetColorClass = "text-warning";
    } else if (trangThaiMoi === "hoan_thanh") {
        targetColorClass = "text-success";
    }

    // Tìm tất cả hàng với mon_an_id
    let rows = $(`tr[data-id-mon="${monAn}"]`);

    // Log lớp màu và HTML
    rows.each(function (index) {
        let colorClasses =
            $(this).find("td:nth-child(2) span").attr("class") ||
            $(this).find("td:nth-child(2)").attr("class");
        let rowHtml = $(this).html();
    });

    if (rows.length) {
        // Hàm gộp các hàng trùng màu
        const mergeRowsByColor = (color, mainRow, serverSoLuong) => {
            let targetRows = rows.filter(function () {
                let colorClasses =
                    $(this).find("td:nth-child(2) span").attr("class") ||
                    $(this).find("td:nth-child(2)").attr("class");
                return colorClasses.includes(color) && this !== mainRow[0];
            });
            let mainSoLuongElement = mainRow.find("td:nth-child(3) .so-luong");
            let mainThanhTienElement = mainRow.find("td.thanh-tien");
            let totalSoLuong = serverSoLuong; // Bắt đầu với số lượng từ server
            if (targetRows.length > 0) {
                targetRows.each(function () {
                    let soLuong =
                        parseInt(
                            $(this)
                                .find("td:nth-child(3) .so-luong")
                                .text()
                                .match(/\d+/)
                        ) || 1;
                    totalSoLuong += soLuong;
                    $(this).remove();
                });
            }
            mainSoLuongElement.text(totalSoLuong);
            // Tính và cập nhật thành tiền
            let thanhTien = totalSoLuong * donGia;
            mainThanhTienElement.text(formatVND(thanhTien));

            // Cập nhật lại rows
            rows = $(`tr[data-id-mon="${monAn}"]`);
            return totalSoLuong;
        };

        // Tìm hàng phù hợp
        let selectedRow = null;
        let tenMonElement = null;
        let soLuongElement = null;

        // Ưu tiên hàng dựa trên trạng thái
        if (trangThaiMoi === "dang_nau") {
            selectedRow = rows
                .filter(function () {
                    let color =
                        $(this).find("td:nth-child(2) span").attr("class") ||
                        $(this).find("td:nth-child(2)").attr("class");

                    return color.includes("text-danger");
                })
                .first();

            if (!selectedRow.length) {
                selectedRow = rows
                    .filter(function () {
                        let color =
                            $(this)
                                .find("td:nth-child(2) span")
                                .attr("class") ||
                            $(this).find("td:nth-child(2)").attr("class");

                        return color.includes("text-warning");
                    })
                    .first();
            }
        } else if (trangThaiMoi === "hoan_thanh") {
            selectedRow = rows
                .filter(function () {
                    let color =
                        $(this).find("td:nth-child(2) span").attr("class") ||
                        $(this).find("td:nth-child(2)").attr("class");

                    return color.includes("text-warning");
                })
                .first();

            if (!selectedRow.length) {
                selectedRow = rows
                    .filter(function () {
                        let color =
                            $(this)
                                .find("td:nth-child(2) span")
                                .attr("class") ||
                            $(this).find("td:nth-child(2)").attr("class");

                        return color.includes("text-success");
                    })
                    .first();
            }
        } else if (trangThaiMoi === "cho_che_bien") {
            selectedRow = rows
                .filter(function () {
                    let color =
                        $(this).find("td:nth-child(2) span").attr("class") ||
                        $(this).find("td:nth-child(2)").attr("class");

                    return (
                        !color.includes("text-warning") &&
                        !color.includes("text-success")
                    );
                })
                .first();
        }

        if (!selectedRow.length) {
            selectedRow = rows
                .filter(function () {
                    let color =
                        $(this).find("td:nth-child(2) span").attr("class") ||
                        $(this).find("td:nth-child(2)").attr("class");
                    return !color.includes("text-success");
                })
                .first();
        }

        if (!selectedRow.length) {
            selectedRow = rows.first();
        }

        tenMonElement = selectedRow.find("td:nth-child(2) span").length
            ? selectedRow.find("td:nth-child(2) span")
            : selectedRow.find("td:nth-child(2)");
        soLuongElement = selectedRow.find("td:nth-child(3) .so-luong");
        let thanhTienElement = selectedRow.find("td.thanh-tien");

        // Xác định trạng thái hiện tại
        let currentColorClass = tenMonElement.attr("class") || "";
        let currentTrangThai;
        if (currentColorClass.includes("text-danger")) {
            currentTrangThai = "cho_che_bien";
        } else if (currentColorClass.includes("text-warning")) {
            currentTrangThai = "dang_nau";
        } else if (currentColorClass.includes("text-success")) {
            currentTrangThai = "hoan_thanh";
        } else {
            currentTrangThai = "none";
        }

        // Kiểm tra tính hợp lệ
        let isValidTransition = false;
        if (currentTrangThai === "none" || currentTrangThai === trangThaiMoi) {
            isValidTransition = true;
        } else if (
            currentTrangThai === "cho_che_bien" &&
            (trangThaiMoi === "dang_nau" || trangThaiMoi === "hoan_thanh")
        ) {
            isValidTransition = true;
        } else if (
            currentTrangThai === "dang_nau" &&
            trangThaiMoi === "hoan_thanh"
        ) {
            isValidTransition = true;
        }

        if (!isValidTransition) {
            return;
        }

        // Gộp hàng trùng màu và cập nhật số lượng từ server
        if (trangThaiMoi === "cho_che_bien") {
            mergeRowsByColor("text-danger", selectedRow, soLuong);
        } else if (trangThaiMoi === "dang_nau") {
            mergeRowsByColor("text-warning", selectedRow, soLuong);
        } else if (trangThaiMoi === "hoan_thanh") {
            if (currentTrangThai === "dang_nau") {
                let successRow = rows
                    .filter(function () {
                        let color =
                            $(this)
                                .find("td:nth-child(2) span")
                                .attr("class") ||
                            $(this).find("td:nth-child(2)").attr("class");
                        return (
                            color.includes("text-success") &&
                            this !== selectedRow[0]
                        );
                    })
                    .first();
                if (successRow.length) {
                    successRow.remove(); // Xóa hàng text-success cũ
                }
                soLuongElement.text(soLuong); // Đặt số lượng từ server
                // Tính và cập nhật thành tiền
                let thanhTien = soLuong * donGia;
                thanhTienElement.text(formatVND(thanhTien));
            } else {
                mergeRowsByColor("text-success", selectedRow, soLuong);
            }
        }

        // Cập nhật màu sắc và kiểu chữ
        if (currentTrangThai !== trangThaiMoi) {
            // Xóa lớp small từ <td> cha
            selectedRow.find("td:nth-child(2)").removeClass("small");
            // Cập nhật tenMonElement
            tenMonElement
                .removeClass("text-danger text-warning text-success small")
                .addClass(targetColorClass);
            if (
                trangThaiMoi === "cho_che_bien" ||
                trangThaiMoi === "dang_nau"
            ) {
                tenMonElement.addClass("small");
            } else if (trangThaiMoi === "hoan_thanh") {
                tenMonElement.addClass("small"); // Giữ small theo code bạn gửi
            }
        }

        // Hiển thị thông báo
        if (trangThaiMoi === "dang_nau" && currentTrangThai !== "dang_nau") {
            var message =
                "Món ăn " + ten_mon + " (" + ten_ban + ") đã bắt đầu nấu";
            showToast(message, "success");
        } else if (
            trangThaiMoi === "hoan_thanh" &&
            currentTrangThai !== "hoan_thanh"
        ) {
            var dingSound = new Audio(dingSoundUrl);
            dingSound.play().catch((err) => console.error("Audio error:", err));
            var message =
                "Món ăn " + ten_mon + " (" + ten_ban + ") đã được cung ứng";
            showToast(message, "success");
        }

        // Kiểm tra bảng
        let updatedRows = $(`tr[data-id-mon="${monAn}"]`);

        updatedRows.each(function (index) {
            let colorClasses =
                $(this).find("td:nth-child(2) span").attr("class") ||
                $(this).find("td:nth-child(2)").attr("class");
        });

        // Kiểm tra DOM

        // Giám sát DOM
        const table = selectedRow.closest("table");
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.removedNodes.length) {
                    mutation.removedNodes.forEach((node) => {
                        if (
                            node.nodeName === "TR" &&
                            $(node).data("id-mon") === monAn
                        ) {
                        }
                    });
                }
            });
        });
        observer.observe(table[0], { childList: true, subtree: true });
    } else {
        console.log(`No row found for monAn: ${monAn}`);
    }
});

window.Echo.channel("bep-channel").listen(".mon-moi-duoc-them", (data) => {
    // Tìm tất cả các món có nút xóa hoặc nút tăng/giảm trước khi xóa
    $(
        ".xoa-mon, .xoa-mon-an, .tang-soluong, .giam-soluong, .toggle-ghi-chu"
    ).each(function () {
        let row = $(this).closest("tr"); // Lấy dòng (tr) chứa nút
        let statusCell = row.find("td:eq(1)");
        // Đổi màu trạng thái thành "chờ chế biến"
        statusCell.removeClass("text-danger text-warning text-success");
        statusCell.addClass("text-danger");
    });

    // Xóa nút xóa và nút tăng/giảm
    $(
        ".xoa-mon, .xoa-mon-an, .tang-soluong, .giam-soluong, .toggle-ghi-chu"
    ).remove();

    // Gộp các dòng có cùng monAnId và class text-danger
    let processedMonAnIds = new Set(); // Lưu danh sách monAnId đã xử lý
    $("tr[data-id-mon]").each(function () {
        let row = $(this);
        let monAnId = row.data("id-mon");
        let hasDangerClass = row.find(".text-danger").length > 0;

        // Chỉ xử lý các dòng có text-danger và chưa được xử lý
        if (hasDangerClass && !processedMonAnIds.has(monAnId)) {
            // Tìm tất cả các dòng có cùng monAnId và text-danger
            let matchingRows = $(`tr[data-id-mon="${monAnId}"]`).filter(
                function () {
                    return $(this).find(".text-danger").length > 0;
                }
            );

            if (matchingRows.length > 1) {
                // Tính tổng số lượng
                let totalQuantity = 0;
                let giaMon = 0;
                matchingRows.each(function () {
                    let soLuong =
                        parseInt($(this).find(".so-luong").text()) || 0;
                    totalQuantity += soLuong;
                    // Lấy đơn giá từ dòng đầu tiên
                    if (!giaMon) {
                        giaMon =
                            parseInt(
                                $(this)
                                    .find(".don-gia")
                                    .text()
                                    .replace(/[^0-9]/g, "")
                            ) || 0;
                    }
                });

                // Cập nhật dòng đầu tiên
                let firstRow = matchingRows.first();
                firstRow.find(".so-luong").text(totalQuantity);
                firstRow
                    .find(".thanh-tien")
                    .text(
                        (totalQuantity * giaMon).toLocaleString("vi-VN") +
                            " VNĐ"
                    );

                // Xóa các dòng còn lại
                matchingRows.not(firstRow).remove();
            }

            // Đánh dấu monAnId đã được xử lý
            processedMonAnIds.add(monAnId);
        }
    });

    $("#new-dish-alert").fadeOut();
});
