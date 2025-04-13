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
 <i class="bi bi-pencil-square text-primary toggle-ghi-chu" style="cursor: pointer;" data-id="${
     item.id
 }"></i>


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

<td class="text-end small don-gia">
    ${parseFloat(item.don_gia).toLocaleString("vi-VN", {
        style: "currency",
        currency: "VND",
    })}
</td>

<td class="text-end small thanh-tien">
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

                            let formattedThanhTien = Number(
                                response.thanh_tien
                            ).toLocaleString("vi-VN", {
                                style: "currency",
                                currency: "VND",
                            });

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
                console.log("tien trc giam" + response.tong_tien);
                let ma_hoa_don = response.data;
                let divMaGiamGia = document.querySelector(".wrap-ma-giam-gia");
                let maGiamGia = response.ma_giam_gia; // chứa thông tin mã giảm
                // if (maGiamGia.length == 0) {
                //     divMaGiamGia.style.display = "none";
                // }
                // console.log("Mã giảm giá:", maGiamGia);
                renderDiscountCodes(maGiamGia, ma_hoa_don);
                if (
                    response.chi_tiet_hoa_don == null ||
                    response.chi_tiet_hoa_don.length == 0
                ) {
                    alert("Không có món nào trong hóa đơn này!");
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
                } else {
                    let emptyRow =
                        '<tr><td colspan="5" class="text-center">Chưa có món nào</td></tr>';
                    hoaDonThanhToan.html(emptyRow);
                }

                // $("#tong-tien").text(tongTien.toLocaleString() + " VNĐ");
                // $(".so-nguoi").text(`👥 ${soNguoi}`);
                $("#tong_tien_hang").val(tongTien.toLocaleString() + " VND");
                let khach_can_tra = parseFloat(response.tong_tien_sau_giam);


                $("#khach_can_tra").val(
                    khach_can_tra.toLocaleString() + " VND"
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

    function renderDiscountCodes(discounts, ma_hoa_don) {
        console.log(discounts);
        let discountListHtml = "";

        discounts.forEach((discount) => {
            const isApplied = discount.is_applied; // <-- thêm biến này
            const buttonClass = isApplied
                ? "btn-success"
                : "btn-outline-primary";
            const buttonText = isApplied
                ? '<i class="bi bi-check-circle me-1"></i><span style="font-size: 0.8rem;">Đã áp dụng</span>'
                : '<i class="bi bi-ticket-perforated me-1"></i><span style="font-size: 0.8rem;">Áp dụng</span>';
            const isDisabled = isApplied ? "disabled" : "";

            discountListHtml += `
                <li class="list-group-item d-flex justify-content-between align-items-center ${
                    isApplied ? "applied" : ""
                }">
                    <div>
                        <span class="fw-bold text-primary">${
                            discount.code
                        }</span>
                        <p class="mb-0 text-muted" style="font-size: 0.85rem;">
                            Giảm ${discount.value}% cho đơn từ ${
                discount.min_order_value
            } VNĐ
                        </p>
                    </div>
                    <button class="btn ${buttonClass} btn-sm apply-discount"  data-ma-hoa-don="${ma_hoa_don}"  data-id="${
                discount.id
            }" ${isDisabled}>
                        ${buttonText}
                    </button>
                </li>
            `;
        });

        document.querySelector(".discount-list .list-group").innerHTML =
            discountListHtml;
    }

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
                    console.log(response);

                    showToast("Đã áp dụng mã giảm giá!", "success");

                    // 👉 Reset lại tất cả các nút về mặc định
                    $(".apply-discount")
                        .removeClass("btn-success")
                        .addClass("btn-outline-primary")
                        .html(
                            '<i class="bi bi-ticket-perforated me-1"></i><span style="font-size: 0.8rem;">Áp dụng</span>'
                        )
                        .prop("disabled", false);

                    // 👉 Cập nhật nút hiện tại
                    $btn.removeClass("btn-outline-primary")
                        .addClass("btn-success")
                        .html(
                            '<i class="bi bi-check-circle me-1"></i><span style="font-size: 0.8rem;">Đã áp dụng</span>'
                        )
                        .prop("disabled", true);

                    // 👉 Optional: Thêm class cho li (nếu muốn hiệu ứng khác)
                    $(".discount-list .list-group-item").removeClass("applied");
                    $btn.closest("li").addClass("applied");

                    $("#khach_can_tra").val(
                        response.tong_tien_sau_giam.toLocaleString() + " VND"
                    );
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
    let monAnId = data.monAn.id;
    let trangThaiMoi = data.monAn.trang_thai;
    let monAn = data.monAn.mon_an_id;
    // console.log(monAnId,trangThaiMoi,monAn)
    let row = $(`tr[data-id-mon="${monAn}"]`);
    if (row.length) {
        let tenMonElement = row.find("td:nth-child(2)"); // Cột chứa tên món ăn

        if (trangThaiMoi === "cho_che_bien") {
            tenMonElement.removeClass().addClass("small text-danger"); // Đỏ
        } else if (trangThaiMoi === "dang_nau") {
            tenMonElement.removeClass().addClass("small text-warning"); // Vàng
        } else if (trangThaiMoi === "hoan_thanh") {
            tenMonElement.removeClass().addClass("small text-success"); // Xanh
        }
    }
});

// // Lấy các phần tử
// const applyButtons = document.querySelectorAll('.apply-discount');
// const appliedCodeDiv = document.getElementById('applied-code');
// const appliedCodeText = document.getElementById('applied-code-text');
// const cancelButton = document.querySelector('.cancel-discount');

// // Hàm khôi phục trạng thái ban đầu
// function resetDiscount() {
//     applyButtons.forEach(button => {
//         button.innerHTML = '<i class="bi bi-ticket-perforated me-1"></i><span style="font-size: 0.8rem;">Áp dụng</span>';
//         button.classList.remove('btn-applied');
//         button.disabled = false;
//     });
//     appliedCodeDiv.style.display = 'none';
//     appliedCodeText.textContent = '';
// }

// // Xử lý nút áp dụng
// applyButtons.forEach(button => {
//     button.addEventListener('click', function() {
//         const code = this.getAttribute('data-code');

//         // Chọn mã
//         resetDiscount(); // Xóa trạng thái cũ
//         this.innerHTML = '<i class="bi bi-check-circle me-1"></i><span style="font-size: 0.8rem;">Đã dùng</span>';
//         this.classList.add('btn-applied');
//         this.disabled = true;

//         // Vô hiệu hóa các nút khác
//         applyButtons.forEach(otherButton => {
//             if (otherButton !== this) {
//                 otherButton.disabled = true;
//             }
//         });

//         // Hiển thị trạng thái
//         appliedCodeText.textContent = code;
//         appliedCodeDiv.style.display = 'block';
//     });
// });

// // Xử lý nút hủy
// cancelButton.addEventListener('click', resetDiscount);
