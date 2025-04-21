{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" /> --}}
<link href="{{ asset('admin/css/swiper-bundle.min.css') }}" rel="stylesheet" />
<style>
.table-dat-ban {
        font-size: 14px;
    }
    .ban {
        position: relative;
    }
    .info-icon {
        position: absolute;
        top: 5px;
        right: 5px;
        font-size: 16px;
        color: #6c757d; /* Màu xám đậm thay cho xanh mặc định */
        cursor: pointer;
    }
    .info-icon:hover {
        color: #5a6268; /* Màu xám đậm hơn khi hover */
    }
</style>

<div class="swiper mySwiper">
    <div class="swiper-wrapper">
        @foreach ($data->chunk(18) as $chunk)
            <div class="swiper-slide">
                <div class="row">
                    @foreach ($chunk as $banAn)
                        <div class="col-md-2 col-4 mb-2">
                            <div class="ban cardd card text-center p-1 position-relative" data-id="{{ $banAn->id }}"
                                id="ban-{{ $banAn->id }}" onclick="setActive(this)">

                                <div class="card-body p-2">
                                    <h5><i class="fas fa-utensils" style="font-size: 24px;"></i></h5>
                                    <h6 class="card-title" style="font-size: 12px;">{{ $banAn->ten_ban }}</h6>

                                    @if ($banAn->trang_thai == 'trong')
                                        <p class="badge badge-success custom-badge">Có sẵn</p>
                                    @elseif ($banAn->trang_thai == 'co_khach')
                                        <p class="badge badge-warning custom-badge">Có khách</p>
                                    @elseif ($banAn->trang_thai == 'da_dat_truoc')
                                        <p class="badge badge-success custom-badge">Có sẵn</p>
                                    @else
                                        <p class="badge badge-secondary custom-badge">Không xác định</p>
                                    @endif

                                    <!-- Icon thông tin đặt bàn -->
                                    <i class="fas fa-info-circle info-icon" data-ban-id="{{ $banAn->id }}"
                                        onclick="showDatBanInfo(event, {{ $banAn->id }})"></i>
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

<!-- Modal hiển thị thông tin đặt bàn -->
<div class="modal fade" id="datBanModal" tabindex="-1" role="dialog" aria-labelledby="datBanModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="datBanModalLabel">Thông tin đặt bàn</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="datBanInfo">
                <!-- Nội dung sẽ được điền bằng AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
{{-- <script src="{{ asset('admin/js/listBanAn.js') }}"></script> --}}
<script src="{{ asset('admin/js/copylistban.js') }}"></script>

<script>
    function showDatBanInfo(event, banId) {
        event.stopPropagation(); // Ngăn sự kiện onclick của thẻ card

        // Gửi yêu cầu AJAX để lấy thông tin đặt bàn
        fetch(`/api/dat-ban/${banId}`)
            .then(response => response.json())
            .then(data => {
                const modalBody = document.getElementById('datBanInfo');
                if (data.dat_ban) {
                    const datBan = data.dat_ban;
                    modalBody.innerHTML = `
                        <p><strong>Mã đặt bàn:</strong> ${datBan.ma_dat_ban}</p>
                        <p><strong>Số người:</strong> ${datBan.so_nguoi}</p>
                        <p><strong>Thời gian đặt:</strong> ${datBan.thoi_gian_dat}</p>
                        <p><strong>Thời gian đến:</strong> ${datBan.thoi_gian_den}</p>
                        <p><strong>Trạng thái:</strong> ${datBan.trang_thai}</p>
                        <p><strong>Số điện thoại:</strong> ${datBan.so_dien_thoai}</p>
                        <p><strong>Mô tả:</strong> ${datBan.mo_ta || 'Không có'}</p>
                    `;
                } else {
                    modalBody.innerHTML = '<p>Không có thông tin đặt bàn cho bàn này.</p>';
                }

                // Hiển thị modal
                $('#datBanModal').modal('show');
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('datBanInfo').innerHTML = '<p>Đã xảy ra lỗi khi lấy thông tin.</p>';
                $('#datBanModal').modal('show');
            });
    }
</script>