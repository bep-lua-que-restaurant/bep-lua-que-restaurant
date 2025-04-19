
{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" /> --}}
<style>
    /* Thêm một chút kiểu dáng để "ô chữ" hiển thị lơ lửng */
    /* Kiểu dáng cho ô thông tin món ăn */
    /* Kiểu dáng cho ô thông tin món ăn */
    .info-wrapper {
        display: none;
        position: absolute;
        background-color: #E6F0FA;
        /* Màu nền xanh nhạt */
        color: #333;
        /* Màu chữ chính */
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        z-index: 10;
        top: 0;
        left: 0%;
        /* Vị trí bên phải */
        width: 150px;
        /* Chiều rộng của ô thông tin */
        animation: spinIn 0.5s ease-out;
        /* Hiệu ứng spin vào */
    }

    .food-info-card {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .food-info-card:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .cooking-time {
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        color: #333;
    }

    .icon {
        color: #ff6f61;
        /* Màu cam nhẹ nhàng, có thể thay đổi */
        font-size: 16px;
    }

    .label {
        font-weight: 600;
        color: #555;
    }

    .value {
        background: #fff;
        padding: 4px 8px;
        border-radius: 4px;
        border: 1px solid #eee;
    }

    /* Hiệu ứng trượt vào khi hiển thị */
    @keyframes slideIn {
        0% {
            transform: translateX(10px);
            opacity: 0;
        }

        100% {
            transform: translateX(0);
            opacity: 1;
        }
    }

    /* Hiệu ứng ẩn dần khi rời đi */
    @keyframes slideOut {
        0% {
            transform: translateX(0);
            opacity: 1;
        }

        100% {
            transform: translateX(10px);
            opacity: 0;
        }
    }

    /* Khi ô thông tin bị ẩn */
    .info-wrapper.hide {
        display: none;
        animation: slideOut 0.3s ease-out;
    }
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
                                <div class="card-body p-2 " style="cursor: pointer;">
                                    <!-- Hình ảnh món ăn -->
                                    <img src="{{ asset('storage/' . optional($monAn->hinhAnhs->first())->hinh_anh) }}"
                                        class="img-thumbnail toggle-info"
                                        style="width: 100px; height: 100px; object-fit: cover;">
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

                                    <!-- Ô thông tin món ăn, ẩn ban đầu -->
                                    <div class="info-wrapper mt-1" id="info-wrapper-{{ $monAn->id }}"
                                        style="display: none;">
                                        <!-- Ô hiển thị thông tin món -->
                                        <div class="food-info-card">
                                            <p class="cooking-time">
                                                <i class="fas fa-clock icon"></i>
                                                <span class="label">Thời gian nấu:</span>
                                                <span class="value">
                                                    @php
                                                        $time = $monAn->thoi_gian_nau;
                                                        if ($time >= 1) {
                                                            // Nếu là phút (thời gian >= 1)
                                                            echo number_format($time, 0) . ' phút';
                                                        } else {
                                                            // Nếu là giây (thời gian < 1)
                                                            $seconds = round($time * 60);
                                                            echo $seconds . ' giây';
                                                        }
                                                    @endphp
                                                </span>
                                            </p>
                                        </div>
                                    </div>


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

    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true, // Toast mới nhất hiển thị ở trên
        "progressBar": true,
        "positionClass": "toast-top-right", // Vị trí: góc trên bên phải
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000", // Toast tự ẩn sau 5 giây
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    function showToast(message, type) {
        switch (type) {
            case 'success':
                toastr.success(message);
                break;
            case 'danger':
                toastr.error(message);
                break;
            case 'warning':
                toastr.warning(message);
                break;
            default:
                toastr.info(message);
                break;
        }
    }
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
                    let tenMon = response.ten_mon
                    console.log(response)
                    window.luuIdHoaDon = response.data.id;
                    var maHoaDonElement = document.getElementById("maHoaDon");
                    maHoaDonElement.innerText = response.data.ma_hoa_don;
                    maHoaDonElement.style.color = "#28a745";
                    nutHoaDon.style.display = "block";
                    showToast("🍽️ Đã thêm món " + tenMon + " vào hóa đơn", "success");
                    // Tìm ID chi tiết hóa đơn tương ứng với món ăn
                    let timMon = response.data.chi_tiet_hoa_dons.find(item => item
                        .mon_an_id == monAnId);
                    if (timMon) {
                        // Gán ID chi tiết hóa đơn vào nút xóa
                        $(`tr[data-id-mon="${monAnId}"] .xoa-mon-an`).attr("data-id-xoa",
                            timMon.id);
                        // Lấy tất cả các nút ghi chú có data-id là monAnId và thay đổi data-id của nó
                        $(`i[data-id="${monAnId}"].toggle-ghi-chu`).attr("data-id", timMon
                            .id);

                        $(`input[data-id="${monAnId}"].ghi-chu-input`).attr("data-id",
                            timMon.id);


                        $(`i[data-id="${monAnId}"].save-ghi-chu`).attr("data-id", timMon
                            .id);
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
                    tongTienCell.text((soLuongMoi * giaMon).toLocaleString("vi-VN") + " VNĐ");

                    existingRow.addClass("table-primary");
                    setTimeout(() => {
                        existingRow.removeClass("table-primary");
                    }, 400);
                } else {
                    // Nếu món chưa có, thêm mới vào bảng hóa đơn
                    let row = $(`
            <tr class="table-primary" data-id-mon="${monAnId}">
                <td class="small">${tbody.children().length + 1}</td>
                <td class="small">
                     <i class="bi bi-pencil-square text-primary toggle-ghi-chu" style="cursor: pointer;" data-id="${monAnId}"></i>
                    ${tenMon}
                            <!-- Ô nhập ghi chú, ẩn ban đầu -->
<div class="ghi-chu-wrapper mt-1" style="display: none;">
    <div class="d-flex align-items-center gap-2">
        <!-- Ô nhập ghi chú -->
        <input type="text" class="form-control ghi-chu-input form-control-sm ghi-chu-input"
               placeholder="Nhập ghi chú..." 
               value=" ""}" 
               data-id="${monAnId}" style="flex: 1;">
        
        <!-- Nút lưu (biểu tượng V) -->
        <i class="bi bi-check-circle-fill text-success save-ghi-chu" style="cursor: pointer; font-size: 20px;" data-id="${monAnId}"></i>
    </div>
                    </td>
                <td class="text-center">
                    <i class="bi bi-dash-circle text-danger giam-soluong" data-id="${monAnId}" style="cursor: pointer; font-size: 20px;"></i>
                    <span class="so-luong mx-2 small">1</span>
                    <i class="bi bi-plus-circle text-success tang-soluong" data-id="${monAnId}" style="cursor: pointer; font-size: 20px;"></i>
                </td>
<td class="text-end small don-gia">
    ${giaMon.toLocaleString("vi-VN")} VNĐ
</td>
<td class="text-end small thanh-tien">
    ${(1 * giaMon).toLocaleString("vi-VN")} VNĐ
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
            // Chỉ áp dụng sự kiện trong container có ID "hoa-don-body"
            $("#hoa-don-body").off("click", ".xoa-mon-an").on("click", ".xoa-mon-an", function() {
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
                        ).toLocaleString("vi-VN") + " VNĐ";

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
                            parseFloat(tongTien).toLocaleString("vi-VN") + " VNĐ"
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

                // Đảm bảo định dạng lại số tiền đúng cách
                $("#tong-tien").text(
                    parseFloat(tongTien).toLocaleString("vi-VN") + " VNĐ"
                );
            }

            function deleteMonAn(monAnId, xoaTr = null) {
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
                    success: function(response) {
                        isRequesting = false;

                        if (!response.success) {
                            Swal.fire("Lỗi!", response.error ||
                                "Không thể lấy trạng thái món ăn.", "error");
                            return;
                        }

                        const {
                            trang_thai,
                            message
                        } = response;

                        if (trang_thai === "cho_xac_nhan") {
                            Swal.fire({
                                title: "Bạn có chắc chắn?",
                                text: "Bạn muốn xóa món này?",
                                icon: "question",
                                showCancelButton: true,
                                confirmButtonText: "Xóa",
                                cancelButtonText: "Thoát",
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    sendDeleteRequest(monAnId, null, true, xoaTr);
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
                                        inputValidator: (value) => (!value ?
                                            "Vui lòng nhập lý do!" :
                                            null),
                                        showCancelButton: true,
                                        confirmButtonText: "Xác nhận",
                                        cancelButtonText: "Thoát",
                                    }).then((inputResult) => {
                                        if (inputResult.isConfirmed) {
                                            sendDeleteRequest(monAnId,
                                                inputResult.value, true,
                                                xoaTr);
                                        }
                                    });
                                }
                            });
                        }
                    },
                    error: function(xhr) {
                        isRequesting = false;
                        Swal.fire("Lỗi!", "Không thể lấy trạng thái món ăn.", "error");
                        console.error("Lỗi khi lấy trạng thái món:", xhr);
                    },
                });
            }

            function sendDeleteRequest(monAnId, lyDo = null, forceDelete = false, xoaTr = null) {
                if (isRequesting) return;

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
                    success: function(response) {
                        isRequesting = false;

                        if (response.requires_confirmation) return;

                        // Xóa dòng món ăn khỏi bảng
                        if (xoaTr) {
                            xoaTr.remove();
                        } else {
                            $(`#mon-${monAnId}`).remove();
                        }

                        // Cập nhật tổng tiền
                        $("#tong-tien").text(
                            parseFloat(response.tong_tien).toLocaleString("vi-VN") +
                            " VNĐ"
                        );

                        // Thông báo thành công
                        Swal.fire("OK!", lyDo ? "Món đã được hủy." : "Món đã bị xóa.",
                            "success");
                    },
                    error: function(xhr) {
                        isRequesting = false;
                        Swal.fire("Lỗi!", "Không thể xử lý món ăn.", "error");
                        console.error("Lỗi AJAX:", xhr.responseText);
                    },
                });
            }


        });
    });

    $(document).ready(function() {
        // Khi nhấp vào toàn bộ món ăn (thẻ card-body)
        $('.toggle-info').click(function(event) {
            var id = $(this).closest('.card').data(
                'banan-id'); // Lấy ID của món ăn từ attribute data-banan-id
            var infoWrapper = $('#info-wrapper-' + id); // Lấy phần thông tin của món ăn

            // Ẩn tất cả các ô thông tin trước khi hiển thị ô thông tin của món ăn hiện tại
            $('.info-wrapper').not(infoWrapper).slideUp(200);

            // Hiển thị hoặc ẩn ô thông tin của món ăn hiện tại
            infoWrapper.toggle(); // Thay đổi trạng thái hiển thị của ô thông tin

            // Ngừng sự kiện để không làm nó bắn ra ngoài
            event.stopPropagation();
        });

        // Đóng ô thông tin khi click ra ngoài
        $(document).on("click", function(event) {
            // Kiểm tra nếu click ra ngoài phần tử thông tin và không phải món ăn
            if (!$(event.target).closest('.info-wrapper').length) {
                // Ẩn tất cả các ô thông tin
                $('.info-wrapper').slideUp(200);
            }
        });
    });
</script>
