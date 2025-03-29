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

        if (!banMoiId) {
            alert("Vui lòng chọn bàn để tách sang.");
            return;
        }

        if (danhSachTach.length === 0) {
            alert("Vui lòng chọn ít nhất một món để tách.");
            return;
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
                if (res.xac_nhan_xoa) {
                    // Nếu hóa đơn gốc trống, hỏi xác nhận xóa
                    if (confirm("Hóa đơn này đã trống, bạn có muốn xóa không?")) {
                        $.ajax({
                            url: "/thu-ngan-xoa-hoa-don",
                            method: "POST",
                            contentType: "application/json",
                            headers: {
                                "X-CSRF-TOKEN": $(
                                    'meta[name="csrf-token"]'
                                ).attr("content"),
                            },
                            data: JSON.stringify({
                                ma_hoa_don: res.hoa_don_goc.ma_hoa_don,
                            }),
                            success: function (deleteRes) {
                                showToast(deleteRes.message, "success");
                            },
                            error: function (err) {
                                console.log(err.responseJSON);
                                showToast("Lỗi khi xóa hóa đơn!", "error");
                            },
                        });
                    } else {
                        showToast("Hóa đơn trống nhưng chưa bị xóa!", "info");
                    }
                } else {
                    showToast("Đã tách bàn và tạo hóa đơn mới!", "success");
                }
            },
            error: function (err) {
                console.log(err.responseJSON);
            },
        });
    });
});
