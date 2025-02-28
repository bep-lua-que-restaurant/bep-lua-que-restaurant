<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<div class="swiper mySwiper">
    <div class="swiper-wrapper">
        @foreach ($data->chunk(12) as $chunk)
            <!-- Mỗi slide chứa 12 món -->
            <div class="swiper-slide">
                <div class="row">
                    @foreach ($chunk as $monAn)
                        <div class="col-md-2 col-4 mb-2">
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


<!-- Nút điều hướng TÙY CHỈNH -->
<div class="text-centerr mt-3">
    <button id="prevBtn" class="btn btn-primary btn-sm px-4">⬅ </button>
    <button id="nextBtn" class="btn btn-primary btn-sm px-4"> ➡</button>
</div>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    var swiper = new Swiper(".mySwiper", {
        slidesPerView: 1, // Hiển thị 1 nhóm 12 món/lượt
        spaceBetween: 20, // Khoảng cách giữa các nhóm
        allowTouchMove: false, // Không cho vuốt tay, chỉ dùng nút
    });

    // Xử lý sự kiện nút bấm
    document.getElementById("nextBtn").addEventListener("click", function() {
        swiper.slideNext();
    });
    document.getElementById("prevBtn").addEventListener("click", function() {
        swiper.slidePrev();
    });


    // tạo hóa đơn 

    $(document).ready(function() {
        $('.add-mon-btn').on('click', function() {
            var card = $(this).closest('.card'); // Lấy phần tử card chứa món ăn
            var banId = $('#ten-ban').data('currentBan'); // Lấy ID bàn hiện tại
            var monAnId = card.data('banan-id'); // Lấy ID món ăn
            var giaMon = parseInt(card.find('.card-text').text().replace(/[^0-9]/g, "")); // Lấy giá món

            // Kiểm tra nếu chưa chọn bàn
            if (!banId) {
                alert("Vui lòng chọn bàn trước khi thêm món!");
                return;
            }

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
                    console.log("Món đã thêm vào hóa đơn!");
                    showToast("Đã thêm một món vào hóa đơn",
                        "success"); // Thông báo thành công
                },
                error: function(error) {
                    console.error("Lỗi khi thêm món vào hóa đơn:", error);
                }
            });
        });
    });


</script>
