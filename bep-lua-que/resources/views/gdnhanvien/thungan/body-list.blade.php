<div class="swiper mySwiper">
    <div class="swiper-wrapper">
        @foreach ($data->chunk(12) as $chunk)


            <div class="swiper-slide">
                <div class="row">
                    @foreach ($chunk as $banAn)
                        <div class="col-md-2 col-4 mb-2">

                            <div class="ban cardd card text-center p-1" data-id="{{ $banAn->id }}"
                                id="ban-{{ $banAn->id }}">

                                <div class="card-body p-2">
                                    <h5><i class="fas fa-utensils" style="font-size: 24px;"></i></h5>
                                    <h6 class="card-title" style="font-size: 12px;">{{ $banAn->ten_ban }}</h6>
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




<script>
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

            console.log("🔥 Bàn được chọn:", banId);

            // Lưu ID bàn vào dataset để sử dụng khi thêm món
            $('#ten-ban').data('currentBan', banId);
            $('#ten-ban').text(tenBan);

            // Gọi AJAX để lấy hóa đơn ID của bàn này
            $.ajax({
                url: "/hoa-don/get-id",
                method: "GET",
                data: {
                    ban_an_id: banId
                },
                success: function(response) {
                    if (response.hoa_don_id) {
                        console.log("🔥 Hóa đơn ID:", response.hoa_don_id);
                        $('#ten-ban').data('hoaDonId', response.hoa_don_id);

                        // Gọi API để lấy chi tiết hóa đơn
                        loadChiTietHoaDon(response.hoa_don_id);
                    } else {
                        console.log("🔥 Bàn này chưa có hóa đơn.");
                        $('#ten-ban').data('hoaDonId', null);
                        $('#hoa-don-body').html(
                            '<tr><td colspan="5" class="text-center">Chưa có món nào trong đơn</td></tr>'
                        );
                        $('#tong-tien').text("0 VNĐ");
                    }
                },
                error: function(xhr) {
                    console.error("🔥 Lỗi khi lấy hóa đơn ID:", xhr.responseText);
                }
            });
        });

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

                    let tongTien = 0;
                    if (response.length > 0) {
                        response.forEach((item) => {
                            let row = `
                        <tr id="mon-${item.id}">
                            <td>#</td>
                            <td>${item.tenMon}</td>
                            <td class="text-center">${item.so_luong}</td>
                            <td class="text-end">${item.don_gia.toLocaleString()} VNĐ</td>
                            <td class="text-end">${(item.so_luong * item.don_gia).toLocaleString()} VNĐ</td>
                        </tr>`;
                            hoaDonBody.append(row);
                            tongTien += item.so_luong * item.don_gia;
                        });
                    } else {
                        hoaDonBody.html(
                            '<tr><td colspan="5" class="text-center">Chưa có món nào trong đơn</td></tr>'
                        );
                    }

                    $("#tong-tien").text(tongTien.toLocaleString() + " VNĐ");
                },
                error: function(xhr) {
                    console.error("🔥 Lỗi khi tải chi tiết hóa đơn:", xhr.responseText);
                }
            });
        }
    });

</script>
