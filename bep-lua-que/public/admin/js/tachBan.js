$(document).ready(function () {
    $("#banGoc").select2({
        placeholder: "Chọn bàn...",
        allowClear: true,
    });
    $("#modalTachBan").on("shown.bs.modal", function () {
        let maHoaDonEl = document.querySelector("#maHoaDon");
        let maHoaDon = maHoaDonEl.textContent;
        let tenHoaDon = document.querySelector("#tenHoaDon");
        tenHoaDon.textContent = maHoaDon;

        $.ajax({
            url: "/thu-ngan-thong-tin-don",
            method: "GET",
            data: { ma_hoa_don: maHoaDon },
            success: function (response) {
                let danhSachBan = response.data; // Lấy danh sách bàn
                let selectBan = $("#banGoc");
                let danhSachMon = response.mon_an;
                let tbody = $("#hoa-don-tach-body");
                tbody.empty();
                selectBan.empty(); // Xóa các option cũ

                // bàn ăn
                if (danhSachBan.length > 0) {
                    danhSachBan.forEach(function (ban) {
                        let option = `<option value="${ban.id}">${ban.ten_ban}-${ban.trang_thai}</option>`;
                        selectBan.append(option);
                    });
                } else {
                    selectBan.append(
                        '<option value="">Không có bàn nào</option>'
                    );
                }

                // món ăn
                if (danhSachMon.length === 0) {
                    tbody.append(
                        '<tr><td colspan="5" class="text-center">Chưa có hóa đơn</td></tr>'
                    );
                } else {
                    danhSachMon.forEach((mon, index) => {
                        tbody.append(`
                           <tr data-index="${index}" data-id-mon="${
                            mon.id_mon
                        }">
                                <td>${index + 1}</td>
                                <td>${mon.ten_mon}</td>
                                <td class="so-luong-goc">${mon.so_luong}</td>
                                <td class="input-group">
                                    <button type="button" class="btn btn-sm btn-outline-secondary giam">-</button>
                                   <span class="so-luong-tach">0</span>
                                    <button type="button" class="btn btn-sm btn-outline-secondary tang">+</button>
                                </td>
                            </tr>
                        `);
                    });

                    // Gán sự kiện cho nút tăng/giảm (dùng delegate để áp dụng cho phần tử động)
                    $(document).on("click", ".giam", function () {
                        let row = $(this).closest("tr");
                        let index = row.data("index");
                        let slGocEl = row.find(".so-luong-goc");
                        let slTachEl = row.find(".so-luong-tach");

                        let slGoc = parseInt(slGocEl.text());
                        let slTach = parseInt(slTachEl.text());

                        if (slTach > 0) {
                            slTach--;
                            slGoc++;
                            danhSachMon[index].so_luong = slGoc;
                        }

                        slGocEl.text(slGoc);
                        slTachEl.text(slTach); // Cập nhật số lượng hiển thị
                    });

                    $(document).on("click", ".tang", function () {
                        let row = $(this).closest("tr");
                        let index = row.data("index");
                        let slGocEl = row.find(".so-luong-goc");
                        let slTachEl = row.find(".so-luong-tach");

                        let slGoc = parseInt(slGocEl.text());
                        let slTach = parseInt(slTachEl.text());

                        if (slGoc > 0) {
                            slTach++;
                            slGoc--;
                            danhSachMon[index].so_luong = slGoc;
                        }

                        slGocEl.text(slGoc);
                        slTachEl.text(slTach); // Cập nhật số lượng hiển thị
                    });
                }
            },
        });
    });

    // Hàm reset modal Tách Bàn
    function resetTachBanModal() {
        // Xóa danh sách bàn
        $("#banGoc")
            .empty()
            .append('<option value="">Chọn bàn...</option>')
            .trigger("change");
        // Xóa danh sách món
        $("#hoa-don-tach-body")
            .empty()
            .append(
                '<tr><td colspan="5" class="text-center">Chưa có hóa đơn</td></tr>'
            );
        // Reset tên hóa đơn
        $("#tenHoaDon").text("");
        // Ẩn thông báo lỗi
        $("#banGoc-error").hide();
        $("#monTach-error").hide();
        // Xóa trạng thái lỗi của select
        $("#banGoc").removeClass("is-invalid");
        $("#banGoc")
            .next(".select2-container")
            .find(".select2-selection")
            .css("border-color", "");
        // Reset select bàn mới
        $("#banMoi").val("new");
    }

    $(document).on("click", "#xacNhanTach-btn", function () {
        let maHoaDon = $("#maHoaDon").text().trim(); // Mã hóa đơn gốc
        let banMoiId = $("#banGoc").val(); // Bàn mới (tách món sang đây)
        let danhSachTach = [];

        $("#hoa-don-tach-body tr").each(function () {
            let tenMon = $(this).find("td:nth-child(2)").text().trim();
            let slTach = parseInt($(this).find(".so-luong-tach").text().trim());

            let idMon = $(this).data("id-mon");
            if (slTach > 0) {
                danhSachTach.push({
                    id_mon: idMon,
                    ten_mon: tenMon,
                    so_luong_tach: slTach,
                });
            }
        });

        // Validation
        const banGocSelect = $("#banGoc");
        const errorElement = $("#banGoc-error");
        if (!banMoiId || banMoiId.length === 0) {
            errorElement.show(); // Hiển thị thông báo lỗi

            return;
        } else {
            errorElement.hide(); // Ẩn thông báo lỗi
        }

        // Validation món
        const monErrorElement = $("#monTach-error");
        if (danhSachTach.length === 0) {
            if (monErrorElement.length) {
                monErrorElement.show(); // Hiển thị thông báo lỗi món
            } else {
                console.error("Phần tử #monTach-error không tồn tại trong DOM");
            }
            return;
        } else {
            if (monErrorElement.length) {
                monErrorElement.hide(); // Ẩn thông báo lỗi món
            }
        }

        // 🧾 Gửi dữ liệu lên server qua AJAX
        $.ajax({
            url: "/thu-ngan-tach-mon",
            method: "POST",
            contentType: "application/json",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: JSON.stringify({
                ma_hoa_don: maHoaDon,
                ban_moi_id: banMoiId,
                mon_tach: danhSachTach,
            }),
            success: function (res) {
                console.log(res);
                if (res.xac_nhan_xoa) {
                    // Nếu hóa đơn gốc trống, hỏi xác nhận xóa
                    Swal.fire({
                        title: "Hóa đơn trống",
                        text: "Hóa đơn này đã trống, bạn có muốn xóa không?",
                        icon: "question",
                        showCancelButton: true,
                        confirmButtonText: "Xóa",
                        cancelButtonText: "Giữ lại",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "/thu-ngan-xoa-hoa-don",
                                method: "POST",
                                contentType: "application/json",
                                headers: {
                                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                                },
                                data: JSON.stringify({
                                    ma_hoa_don: res.hoa_don_goc.ma_hoa_don,
                                }),
                                success: function (deleteRes) {
                                    showToast(deleteRes.message, "success");
                                    // Reset giao diện hóa đơn chính
                                    resetGiaoDienHoaDon();
                                },
                                error: function (err) {
                                    console.log(err.responseJSON);
                                    showToast("Lỗi khi xóa hóa đơn!", "error");
                                },
                            });
                        } else {
                            showToast("Hóa đơn trống nhưng chưa bị xóa!", "info");
                        }
                    });
                } else {
                    showToast("Đã tách bàn và tạo hóa đơn mới!", "success");
                }

                // Reset và đóng modal
                resetTachBanModal();
                $("#modalTachBan").modal("hide");

                // Cập nhật giao diện hóa đơn chính (tùy thuộc vào phản hồi từ server)
                if (res.hoa_don_goc_id) {
                    // console.log(res.hoa_don_goc_id);
                    loadChiTietHoaDon(res.hoa_don_goc_id)

                }
            },
            error: function (err) {
                console.log(err.responseJSON);
            },
        });
    });

    // Xóa lỗi khi người dùng chọn bàn
    $("#banGoc").on("change", function () {
        const banGocSelect = $("#banGoc");
        const errorElement = $("#banGoc-error");
        if (banGocSelect.val() && banGocSelect.val().length > 0) {
            errorElement.hide();
            banGocSelect.removeClass("is-invalid");
            banGocSelect
                .next(".select2-container")
                .find(".select2-selection")
                .css("border-color", "");
        }
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
});
