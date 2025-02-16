<div class="swiper mySwiper">
    <div class="swiper-wrapper">
        @foreach ($data->chunk(12) as $chunk)
            <!-- Mỗi slide chứa 12 bàn -->
            <div class="swiper-slide">
                <div class="row">
                    @foreach ($chunk as $banAn)
                        <div class="col-md-2 col-4 mb-2">
                            <div class="cardd card text-center p-1" onclick="createHoaDon({{ $banAn->id }})"
                                data-banan-id="{{ $banAn->id }}">
                                <div class="card-body p-2">
                                    <h5><i class="fas fa-utensils" style="font-size: 24px;"></i></h5>
                                    <h6 class="card-title" style="font-size: 12px;">{{ $banAn->ten_ban }}</h6>
                                    @if ($banAn->trang_thai == 'trong')
                                        <p class="badge badge-success" style="font-size: 10px;">Có sẵn</p>
                                    @else
                                        <p class="badge badge-danger" style="font-size: 10px;">Đang sử dụng</p>
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
</script>
