{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" /> --}}
<style>

</style>
<link href="{{ asset('admin/css/swiper-bundle.min.css') }}" rel="stylesheet" />
<div class="swiper mySwiper">
    <div class="swiper-wrapper">
        @foreach ($data->chunk(8) as $chunk)
            <!-- Mỗi slide chứa 12 món -->
            <div class="swiper-slide">
                <div class="row">
                    @foreach ($chunk as $monAn)
                        <div class="col-md-3 col-6 mb-2">
                            <div class="cardd card text-center p-1" data-banan-id="{{ $monAn->id }}">
                                <div class="card-body p-2">
                                    <!-- Hình ảnh món ăn -->
                                    <img src="{{ asset('storage/' . optional($monAn->hinhAnhs->first())->hinh_anh) }}"
                                        class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                    <!-- Tên món -->
                                    <h6 class="card-title" style="font-size: 12px;">{{ $monAn->ten }}</h6>

                                    <p class="card-text badge badge-danger " style="font-size: 10px;">
                                        {{ number_format($monAn->gia) }} VNĐ

                                    </p>
                                    <!-- Nút Thêm món -->
                                    <button type="submit" class="btn btn-primary btn-sm add-mon-btn"
                                        style="padding: 2px 6px; font-size: 10px;">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="text-center mt-2">
    <span id="pageIndicator">1 / 1</span>
</div>
<!-- Nút điều hướng TÙY CHỈNH -->
<div class="text-centerr mt-3">
    <button id="prevBtn" class="btn btn-primary btn-sm px-4">⬅ </button>
    <button id="nextBtn" class="btn btn-primary btn-sm px-4"> ➡</button>
</div>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    var swiper = new Swiper(".mySwiper", {
        slidesPerView: 1, // Mỗi lần hiển thị 1 nhóm sản phẩm
        spaceBetween: 20, // Khoảng cách giữa các nhóm
        allowTouchMove: true, // Cho phép kéo bằng chuột/tay
        grabCursor: true

    });

    // Hàm cập nhật số trang
    function updatePageIndicator() {
        var currentPage = swiper.activeIndex + 1; // Trang hiện tại (bắt đầu từ 1)
        var totalPages = swiper.slides.length; // Tổng số trang
        document.getElementById("pageIndicator").textContent =
            currentPage + " / " + totalPages;
    }

    // Lắng nghe sự kiện khi đổi trang bằng nút bấm
    document.getElementById("nextBtn").addEventListener("click", function() {
        swiper.slideNext();
    });

    document.getElementById("prevBtn").addEventListener("click", function() {
        swiper.slidePrev();
    });

    // Lắng nghe sự kiện khi kéo/swipe bằng chuột hoặc tay
    swiper.on("slideChange", function() {
        updatePageIndicator();
    });

    // Cập nhật số trang ban đầu
    updatePageIndicator();


    // tạo hóa đơn 
    window.luuIdHoaDon = null;
    $(document).ready(function() {
        $('.add-mon-btn').on('click', function() {
            var card = $(this).closest('.card'); // Lấy phần tử card chứa món ăn
            var banId = $('#ten-ban').data('currentBan'); // Lấy ID bàn hiện tại
            var monAnId = card.data('banan-id'); // Lấy ID món ăn
            var giaMon = parseInt(card.find('.card-text').text().replace(/[^0-9]/g, "")); // Lấy giá món
            let nutHoaDon = document.querySelector(".nut-hoa-don");
            // Kiểm tra nếu chưa chọn bàn
            if (!banId) {
                showToast("🚨 Vui lòng chọn bàn trước khi thêm món", "warning");
                return;
            }

            capNhatHoaDon(monAnId, card.find('.card-title').text(), giaMon);

            // Gửi AJAX để tạo hóa đơn và thêm món ăn
            $.ajax({
                url: "{{ route('thungan.createHoaDon') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    ban_an_id: banId,
                    mon_an_id: monAnId,
                    gia: giaMon
                },
                success: function(response) {
                    window.luuIdHoaDon = response.data.id;
                    var maHoaDonElement = document.getElementById("maHoaDon");
                    maHoaDonElement.innerText = response.data.ma_hoa_don;
                    maHoaDonElement.style.color = "#28a745";
                    nutHoaDon.style.display = "block";
                    // Tìm ID chi tiết hóa đơn tương ứng với món ăn
                    let timMon = response.data.chi_tiet_hoa_dons.find(item => item
                        .mon_an_id == monAnId);
                    if (timMon) {
                        // Gán ID chi tiết hóa đơn vào nút xóa
                        $(`tr[data-id-mon="${monAnId}"] .xoa-mon-an`).attr("data-id-xoa",
                            timMon.id);
                    }

                },
                error: function(xhr, status, error) {
                    console.error("Lỗi khi thêm món vào hóa đơn:", xhr);
                    console.log("Trạng thái lỗi:", status);
                    console.log("Chi tiết lỗi:", error);
                    console.log("Response:", xhr.responseText);
                }

            });

            function capNhatHoaDon(monAnId, tenMon, giaMon) {
                let tbody = $("#hoa-don-body");
                let existingRow = $(`tr[data-id-mon="${monAnId}"]`);

                // Xóa dòng "Chưa có món nào trong đơn" nếu có
                if ($(".empty-invoice").length) {
                    $(".empty-invoice").closest("tr").remove();
                }

                if (existingRow.length) {
                    // Nếu món đã có, tăng số lượng và cập nhật tổng giá
                    let soLuongSpan = existingRow.find(".so-luong").first();

                    let soLuongMoi = parseInt(soLuongSpan.text()) + 1;
                    soLuongSpan.text(soLuongMoi);

                    let tongTienCell = existingRow.find(".text-end:last");
                    tongTienCell.text((soLuongMoi * giaMon).toLocaleString("vi-VN", {
                        style: "currency",
                        currency: "VND",
                    }));

                    existingRow.addClass("table-primary");
                    setTimeout(() => {
                        existingRow.removeClass("table-primary");
                    }, 400);
                } else {
                    // Nếu món chưa có, thêm mới vào bảng hóa đơn
                    let row = $(`
            <tr class="table-primary" data-id-mon="${monAnId}">
                <td class="small">${tbody.children().length + 1}</td>
                <td class="small">${tenMon}</td>
                <td class="text-center">
                    <i class="bi bi-dash-circle text-danger giam-soluong" data-id="${monAnId}" style="cursor: pointer; font-size: 20px;"></i>
                    <span class="so-luong mx-2 small">1</span>
                    <i class="bi bi-plus-circle text-success tang-soluong" data-id="${monAnId}" style="cursor: pointer; font-size: 20px;"></i>
                </td>
                <td class="text-end small don-gia">
                    ${giaMon.toLocaleString("vi-VN", { style: "currency", currency: "VND" })}
                </td>
                <td class="text-end small thanh-tien">
                    ${(1 * giaMon).toLocaleString("vi-VN", { style: "currency", currency: "VND" })}
                </td>
                <td class="text-center">
                    <button class="btn btn-sm btn-outline-danger xoa-mon-an" data-id="${monAnId}">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
        `);
                    tbody.append(row);
                    setTimeout(() => {
                        row.removeClass("table-primary");
                    }, 400);
                }

                tinhTongHoaDon(); // Cập nhật tổng hóa đơn
            }

            function tinhTongHoaDon() {
                let tongTien = 0;
                $("#hoa-don-body tr").each(function() {
                    let tongTienMon = $(this).find("td.text-end:last").text().replace(/[^0-9]/g,
                        "");
                    tongTien += parseInt(tongTienMon);
                });

                $("#tong-tien").text(
                    tongTien.toLocaleString("vi-VN") + " VNĐ"
                );
            }
            let isRequesting = false;
            $(document).on("click", ".xoa-mon-an", function() {
                let monAnId = $(this).data("id-xoa");
                let xoaTr = $(this).closest("tr");
                deleteMonAn(monAnId, xoaTr);
            });

            $(".tang-soluong, .giam-soluong").off("click").on("click", function() {
                let monAnId = $(this).data("id");
                let thayDoi = $(this).hasClass('tang-soluong') ? 1 : -1;
                updateSoLuong($(this), monAnId, thayDoi);
            });


            // Hàm cập nhật số lượng món ăn
            function updateSoLuong(nutDuocClick, monAnId, thayDoi) {
                let dongChuaNo = nutDuocClick.closest("tr");

                let nutXoa = dongChuaNo.find(".xoa-mon-an"); // Tìm nút xóa trong hàng đó
                let monUpdate = nutXoa.data("id-xoa"); // Lấy giá trị data-id-xoa
                if (monUpdate == undefined) {
                    monUpdate = dongChuaNo.attr("id").replace("mon-", "");;
                }

                let soLuongSpan = dongChuaNo.find(".so-luong").first();
                let soLuong = parseInt(soLuongSpan.text()) + thayDoi;
                if (soLuong < 1) {
                    soLuong = 1; // Đảm bảo số lượng không nhỏ hơn 1
                }
                soLuongSpan.text(soLuong);
                let thanhTien = dongChuaNo.find(".thanh-tien").first();
                $.ajax({
                    url: "/hoa-don/update-quantity",
                    method: "POST",
                    data: {
                        mon_an_id: monUpdate,
                        thay_doi: thayDoi,
                        _token: $('meta[name="csrf-token"]').attr(
                            "content"), // Nếu dùng Laravel
                    },
                    success: function(response) {
                        // Cập nhật tổng tiền

                        let formattedThanhTien = Number(
                            response.thanh_tien
                        ).toLocaleString("vi-VN", {
                            style: "currency",
                            currency: "VND",
                        });

                        thanhTien.text(formattedThanhTien);

                        let tongTien = 0;
                        $("#hoa-don-body tr").each(function() {
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
                    error: function(xhr) {
                        console.error("❌ Lỗi khi cập nhật số lượng:", xhr.responseText);
                    },
                });
            }

            function tinhTongHoaDon() {
                let tongTien = 0;
                $("#hoa-don-body tr").each(function() {
                    let tongTienMon = $(this).find("td.text-end:last").text().replace(/[^0-9]/g,
                        "");
                    tongTien += parseInt(tongTienMon);
                });

                $("#tong-tien").text(
                    tongTien.toLocaleString("vi-VN") + " VNĐ"
                );
            }

            function deleteMonAn(monAnId, xoaTr) {
                if (isRequesting) return;

                Swal.fire({
                    title: "Bạn có chắc chắn?",
                    text: "Món ăn này sẽ bị xóa khỏi hóa đơn!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#6c757d",
                    confirmButtonText: "Xóa ngay",
                    cancelButtonText: "Hủy",
                }).then((result) => {
                    if (result.isConfirmed) {
                        isRequesting = true;
                        $.ajax({
                            url: '/hoa-don/delete',
                            method: "POST",
                            data: {
                                mon_an_id: monAnId,
                                _token: $('meta[name="csrf-token"]').attr("content"),
                            },
                            success: function(response) {
                                isRequesting = false;
                                // Xóa dòng món ăn khỏi bảng
                                xoaTr.remove();

                                // Cập nhật tổng tiền
                                $("#tong-tien").text(
                                    response.tong_tien.toLocaleString("vi-VN", {
                                        style: "currency",
                                        currency: "VND",
                                    })
                                );

                                // Hiển thị thông báo thành công
                                Swal.fire(
                                    "Đã xóa!",
                                    "Món ăn đã được xóa khỏi hóa đơn.",
                                    "success"
                                );
                            },
                            error: function() {
                                isRequesting = false;
                                Swal.fire("Lỗi!", "Không thể xóa món ăn.", "error");
                            },
                        });
                    }
                });
            }


        });
    });
</script>
