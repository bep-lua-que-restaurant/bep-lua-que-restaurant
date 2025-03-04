<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<!-- Thêm Bootstrap Icons -->


<style>
    .ban.active {
        border: 2px solid #007bff;
        /* Viền màu xanh */
        background-color: rgba(0, 123, 255, 0.1);
        /* Nền nhạt */
        box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);
        /* Đổ bóng */
    }
</style>

<div class="swiper mySwiper">
    <div class="swiper-wrapper">
        @foreach ($data->chunk(12) as $chunk)
            <div class="swiper-slide">
                <div class="row">
                    @foreach ($chunk as $banAn)
                        <div class="col-md-2 col-4 mb-2">

                            <div class="ban cardd card text-center p-1" data-id="{{ $banAn->id }}"
                                id="ban-{{ $banAn->id }}" onclick="setActive(this)">

                                <div class="card-body p-2">
                                    <h5><i class="fas fa-utensils" style="font-size: 24px;"></i></h5>
                                    <h6 class="card-title" style="font-size: 12px;">{{ $banAn->ten_ban }}
                                        ({{ $banAn->so_ghe }} ghế)
                                    </h6>

                                    @if ($banAn->trang_thai == 'trong')
                                        <p class="badge badge-success" style="font-size: 10px;">Có sẵn</p>
                                    @elseif ($banAn->trang_thai == 'co_khach')
                                        <p class="badge badge-warning" style="font-size: 10px;">Có khách</p>
                                    @elseif ($banAn->trang_thai == 'da_dat_truoc')
                                        <p class="badge badge-info" style="font-size: 10px;">Đã đặt trước</p>
                                    @else
                                        <p class="badge badge-secondary" style="font-size: 10px;">Không xác định</p>
                                    @endif


                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach



    </div>
</div>


<!-- Nút điều hướng TÙY CHỈNH -->
<div class="text-centerr mt-3">
    <button id="prevBtn" class="btn btn-primary btn-sm px-4">⬅ </button>
    <button id="nextBtn" class="btn btn-primary btn-sm px-4"> ➡</button>
</div>



<!-- Thêm jQuery -->


<script>
    function setActive(element) {
        // Xóa class 'active' khỏi tất cả các bàn
        document.querySelectorAll('.ban').forEach(el => el.classList.remove('active'));

        // Thêm class 'active' vào bàn được chọn
        element.classList.add('active');
    }


    var swiper = new Swiper(".mySwiper", {
        slidesPerView: 1, // Mỗi lần hiển thị 1 nhóm 12 sản phẩm
        spaceBetween: 20, // Khoảng cách giữa các nhóm
        allowTouchMove: false, // Không cho trượt bằng tay, chỉ dùng nút
    });

    // Xử lý sự kiện nút bấm
    document.getElementById("nextBtn").addEventListener("click", function() {
        swiper.slideNext();
    });

    document.getElementById("prevBtn").addEventListener("click", function() {
        swiper.slidePrev();
    });


    $(document).ready(function() {
        $('.ban').on('click', function() {
            var banId = $(this).data('id'); // Lấy ID bàn
            var tenBan = $(this).find('.card-title').text(); // Lấy tên bàn

            // console.log("🔥 Bàn được chọn:", banId);
            // Lưu ID bàn vào dataset để sử dụng khi thêm món
            $('#ten-ban').data('currentBan', banId);
            $('#ten-ban').text(tenBan);
            $('#tableInfo').text(tenBan)
            // Gọi AJAX để lấy hóa đơn ID của bàn này
            $.ajax({
                url: "/hoa-don/get-id",
                method: "GET",
                data: {
                    ban_an_id: banId
                },
                success: function(response) {
                    if (response.hoa_don_id) {
                        // console.log("🔥 Hóa đơn ID:", response.hoa_don_id);

                        $('#ten-ban').data('hoaDonId', response.hoa_don_id);

                        // Gọi API để lấy chi tiết hóa đơn
                        loadChiTietHoaDon(response.hoa_don_id);
                    } else {
                        // console.log("🔥 Bàn này chưa có hóa đơn.");
                        $('#ten-ban').data('hoaDonId', null);
                        $('#hoa-don-body').html(
                            '<tr><td colspan="5" class="text-center">Chưa có món nào trong đơn</td></tr>'
                        );
                        $('#tong-tien').text("0 VNĐ");
                        $('.so-nguoi').text("👥 0");
                    }
                },
                error: function(xhr) {
                    console.error("🔥 Lỗi khi lấy hóa đơn ID:", xhr.responseText);
                }
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
                    _token: $('meta[name="csrf-token"]').attr("content") // Nếu dùng Laravel
                },
                success: function(response) {
                    loadChiTietHoaDon(response
                        .hoa_don_id);
                },
                error: function(xhr) {
                    console.error("❌ Lỗi khi cập nhật số lượng:", xhr.responseText);
                },
            });
        }

        function loadChiTietHoaDon(hoaDonId) {
            $.ajax({
                url: "/hoa-don/get-details",
                method: "GET",
                data: {
                    hoa_don_id: hoaDonId
                },
                success: function(response) {
                    let hoaDonBody = $("#hoa-don-body");
                    hoaDonBody.empty();

                    let offcanvasBody = $(".offcanvas-body tbody"); // Lấy phần bảng trong offcanvas
                    offcanvasBody.empty(); // Xóa nội dung cũ
                    var soNguoi = response.so_nguoi
                    let tongTien = 0;
                    if (response.chi_tiet_hoa_don.length > 0) {
                        let index = 1;
                        response.chi_tiet_hoa_don.forEach((item) => {
                            let row = `
                            <tr id="mon-${item.id}">
    <td class="small">${index}</td>
    <td class="small">
        <!-- Thêm điều kiện để thay đổi màu tên món tùy theo trạng thái -->
        <span class="${item.trang_thai === 'cho_che_bien' ? 'text-danger' : 
                     item.trang_thai === 'dang_nau' ? 'text-warning' : 
                     item.trang_thai === 'hoan_thanh' ? 'text-success' : ''}">
            ${item.tenMon}
        </span>
    </td>
    <td class="text-center">
        <!-- Nút giảm số lượng -->
        <button class="btn btn-sm btn-outline-danger giam-soluong" data-id="${item.id}">
            <i class="bi bi-dash"></i> <!-- Sử dụng biểu tượng cho nút giảm -->
        </button>
        <!-- Hiển thị số lượng với chữ nhỏ hơn -->
        <span class="so-luong mx-2 small">${item.so_luong}</span>
        <!-- Nút tăng số lượng -->
        <button class="btn btn-sm btn-outline-success tang-soluong" data-id="${item.id}">
            <i class="bi bi-plus"></i> <!-- Sử dụng biểu tượng cho nút tăng -->
        </button>
    </td>
    <td class="text-end small">
        ${parseFloat(item.don_gia).toLocaleString('vi-VN', { style: 'currency', currency: 'VND' })}
    </td>

    <td class="text-end small">
        ${(item.so_luong * item.don_gia).toLocaleString('vi-VN', { style: 'currency', currency: 'VND' })}
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
                        $("#ten-ban").text(response.ten_ban_an + " (Đã ghép)");
                    }

                    if (response.ma_hoa_don) {
                        $("#ma_hoa_don").text(response.ma_hoa_don);

                    }

                    $("#tong-tien").text(tongTien.toLocaleString() + " VNĐ");
                    $('.so-nguoi').text(`👥 ${soNguoi}`);
                    $("#totalAmount").val(tongTien.toLocaleString() +
                        " VND"); // Cập nhật tổng tiền trong offcanvas

                    if (response.ten_ban) {
                        $("#tableInfo").text(`Bàn ${response.ten_ban}`);
                    }

                    // Thêm sự kiện cho nút tăng giảm số lượng
                    $(".tang-soluong").click(function() {
                        let monAnId = $(this).data("id");
                        updateSoLuong(monAnId, 1);
                    });

                    $(".giam-soluong").click(function() {
                        let monAnId = $(this).data("id");
                        updateSoLuong(monAnId, -1);
                    });
                },
                error: function(xhr) {
                    console.error("🔥 Lỗi khi tải chi tiết hóa đơn:", xhr.responseText);
                }
            });
        }

    });
</script>
