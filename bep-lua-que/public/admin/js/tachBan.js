$(document).ready(function () {
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
                    danhSachBan.forEach(function (ban, index) {
                        let option = `<option value="${ban.id}" ${
                            index === 0 ? "selected" : ""
                        }>${ban.ten_ban}-${ban.trang_thai}</option>`;
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
                            <tr data-index="${index}">
                                <td>${index + 1}</td>
                                <td>${mon.ten_mon}</td>
                                <td class="so-luong-goc">${mon.so_luong}</td>
                                <td class="input-group">
                                    <button type="button" class="btn btn-sm btn-outline-secondary giam">-</button>
                                    <input type="number" class="form-control so-luong-tach" value="0" min="0" max="${mon.so_luong}">
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
                        let slTach = parseInt(slTachEl.val());

                        if (slTach > 0) {
                            slTach--;
                            slGoc++;
                            danhSachMon[index].so_luong = slGoc;
                        }

                        slGocEl.text(slGoc);
                        slTachEl.val(slTach); // Cập nhật giá trị của input
                    });

                    $(document).on("click", ".tang", function () {
                        let row = $(this).closest("tr");
                        let index = row.data("index");
                        let slGocEl = row.find(".so-luong-goc");
                        let slTachEl = row.find(".so-luong-tach");

                        let slGoc = parseInt(slGocEl.text());
                        let slTach = parseInt(slTachEl.val());

                        if (slGoc > 0) {
                            slTach++;
                            slGoc--;
                            danhSachMon[index].so_luong = slGoc;
                        }

                        slGocEl.text(slGoc);
                        slTachEl.val(slTach); // Cập nhật giá trị của input
                    });
                }
            },
        });
    });
});
